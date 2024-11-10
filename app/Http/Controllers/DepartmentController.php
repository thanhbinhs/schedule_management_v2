<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Account; // Đảm bảo rằng bạn đã tạo model Account
use App\Models\Professor; // Đảm bảo rằng bạn đã tạo model Professor
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Sử dụng Str helper
use Illuminate\Support\Facades\Hash; // Sử dụng Hash để mã hóa password

class DepartmentController extends Controller
{
    /**
     * Hiển thị danh sách các khoa.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::with('leader', 'majors')->get();
        // Truyền professors
        // If role is department, get departmentID from username or role is admin, get all departments
        $departmentID = auth()->user()->role === 'department' ? auth()->user()->username : null;
        $departmentName = $departmentID ? Department::find($departmentID)->DepartmentName : null;
        

        return view('pdt.departments.index', compact('departments', 'departmentName'));
    }

    /**
     * Hiển thị form tạo mới khoa.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pdt.departments.create');
    }

    /**
     * Lưu thông tin khoa mới vào cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'DepartmentName' => 'required|string|unique:departments,DepartmentName',
            'DepartmentAddress' => 'required|string',
        ]);

        // Tạo DepartmentID từ DepartmentName: lowercase, không dấu, viết liền
        $departmentID = Str::slug($validated['DepartmentName'], '');

        // Kiểm tra xem DepartmentID đã tồn tại chưa
        if (Department::where('DepartmentID', $departmentID)->exists()) {
            return back()->withErrors(['DepartmentName' => 'Tên khoa đã tồn tại và không thể chuyển đổi thành DepartmentID duy nhất.'])->withInput();
        }

        // Tạo khoa mới
        $department = Department::create([
            'DepartmentID' => $departmentID,
            'DepartmentName' => $validated['DepartmentName'],
            'DepartmentAddress' => $validated['DepartmentAddress'],
            'LeaderDepartmentID' => null,
        ]);

        // Tạo Account mới cho Khoa
        Account::create([
            'username' => $departmentID, // Sử dụng DepartmentID làm username
            'password' => Hash::make('123456'), // Mã hóa password
            'role' => 'department',
        ]);

        // Chuyển hướng về danh sách khoa với thông báo thành công
        return redirect()->route('pdt.departments.index')->with('success', 'Khoa đã được thêm thành công cùng với tài khoản.');
    }

    /**
     * Hiển thị form chỉnh sửa thông tin khoa.
     *
     * @param  string  $DepartmentID
     * @return \Illuminate\Http\Response
     */
    public function edit($DepartmentID)
    {
        $department = Department::findOrFail($DepartmentID);
        $professors = Professor::where('DepartmentID', $department->DepartmentID)->get();
        return view('pdt.departments.edit', compact('department', 'professors'));
    }

    /**
     * Cập nhật thông tin khoa trong cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $DepartmentID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $DepartmentID)
    {
        $department = Department::findOrFail($DepartmentID);

        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'DepartmentName' => 'required|string|unique:departments,DepartmentName,' . $DepartmentID . ',DepartmentID',
            'DepartmentAddress' => 'required|string',
            'LeaderDepartmentID' => 'nullable|string|exists:professors,ProfessorID',
        ]);



        // Nếu DepartmentName thay đổi, cập nhật DepartmentID tương ứng
        if ($validated['DepartmentName'] !== $department->DepartmentName) {
            $newDepartmentID = Str::slug($validated['DepartmentName'], '');

            // Kiểm tra xem new DepartmentID đã tồn tại chưa
            if (Department::where('DepartmentID', $newDepartmentID)->exists()) {
                return back()->withErrors(['DepartmentName' => 'Tên khoa đã tồn tại và không thể chuyển đổi thành DepartmentID duy nhất.'])->withInput();
            }

            // Cập nhật DepartmentID trong Account
            Account::where('username', $DepartmentID)->update(['username' => $newDepartmentID]);

            // Cập nhật DepartmentID
            $department->DepartmentID = $newDepartmentID;
        }

        $newLeaderId = $validated['LeaderDepartmentID'];

        // Nếu có sự thay đổi về `LeaderDepartmentID`
        if ($newLeaderId != $department->LeaderDepartmentID) {
            // Đặt lại giảng viên cũ (nếu có) không còn là leader
            if ($department->LeaderDepartmentID) {
                $oldLeader = Professor::find($department->LeaderDepartmentID);
                if ($oldLeader) {
                    $oldLeader->isLeaderDepartment = false;
                    $oldLeader->save();
                }
            }
    
            // Cập nhật giảng viên mới thành leader (nếu có)
            if ($newLeaderId) {
                $newLeader = Professor::find($newLeaderId);
                if ($newLeader) {
                    $newLeader->isLeaderDepartment = true;
                    $newLeader->save();
                }
            }
    
            // Cập nhật LeaderDepartmentID cho khoa
            $department->LeaderDepartmentID = $newLeaderId;
        }

        // Cập nhật thông tin khoa
        $department->DepartmentName = $validated['DepartmentName'];
        $department->DepartmentAddress = $validated['DepartmentAddress'];
        $department->LeaderDepartmentID = $validated['LeaderDepartmentID'] ?? null;
        $department->save();

        // Cập nhật Account nếu DepartmentID đã thay đổi
        if (isset($newDepartmentID)) {
            Account::where('username', $newDepartmentID)->update(['username' => $newDepartmentID]);
        }

        // Chuyển hướng về danh sách khoa với thông báo thành công
        return redirect()->route('pdt.departments.index')->with('success', 'Khoa đã được cập nhật thành công.');
    }

    /**
     * Xóa một khoa khỏi cơ sở dữ liệu.
     *
     * @param  string  $DepartmentID
     * @return \Illuminate\Http\Response
     */
    public function destroy($DepartmentID)
    {
        $department = Department::findOrFail($DepartmentID);
        $department->delete();

        // Xóa tài khoản liên kết với khoa
        Account::where('department_id', $DepartmentID)->delete();

        return redirect()->route('pdt.departments.index')->with('success', 'Khoa đã được xóa thành công cùng với tài khoản.');
    }
}
