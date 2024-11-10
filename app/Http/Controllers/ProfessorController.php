<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\Account; // Import model Account
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth; // Import Auth facade


class ProfessorController extends Controller
{
     // Hiển thị danh sách các professor
     public function index()
     {
         $departmentID = Auth::user()->username; // Lấy username của tài khoản đăng nhập làm DepartmentID
         $department = Department::find($departmentID);
         $departmentName = $department->DepartmentName;
         $professors = Professor::where('DepartmentID', $departmentID)->get();
         return view('department.professors.index', compact('professors', 'departmentName'));
     }
 
     // Hiển thị form tạo mới professor
     public function create()
     {
        $departmentID = Auth::user()->username; // Lấy username của tài khoản đăng nhập làm DepartmentID
        $department = Department::find($departmentID);
        $departmentName = $department->DepartmentName;
         return view('department.professors.create', compact('departmentName'));
     }
 
     // Lưu thông tin professor mới vào cơ sở dữ liệu
     public function store(Request $request)
     {
         // Xác thực dữ liệu nhập vào
         $validated = $request->validate([
             'ProfessorName' => 'required|string',
             'ProfessorPhone' => 'required|string|unique:professors,ProfessorPhone',
         ]);
 
         // Sinh tự động ProfessorID theo format 2400xxxx
         $lastProfessor = Professor::where('ProfessorID', 'LIKE', '2400%')
             ->orderBy('ProfessorID', 'desc')
             ->first();
 
         $newIDNumber = 0;
         if ($lastProfessor) {
             $newIDNumber = (int)substr($lastProfessor->ProfessorID, 4) + 1;
         }
         $newProfessorID = '2400' . str_pad($newIDNumber, 4, '0', STR_PAD_LEFT);
 
         // Tự động tạo Gmail theo format ProfessorID@gmail.com
         $generatedEmail = $newProfessorID . '@gmail.com';
 
         // Lấy DepartmentID từ username của tài khoản đăng nhập
         $departmentID = Auth::user()->username;

             // Chuyển đổi giá trị của isLeaderDepartment sang kiểu boolean/integer
        $isLeaderDepartment = $request->has('isLeaderDepartment') ? 1 : 0;
 
         // Tạo mới Professor
         $professor = Professor::create([
             'ProfessorID' => $newProfessorID,
             'ProfessorName' => $validated['ProfessorName'],
             'ProfessorGmail' => $generatedEmail,
             'ProfessorPhone' => $validated['ProfessorPhone'],
             'DepartmentID' => $departmentID,
            'isLeaderDepartment' => $isLeaderDepartment,
         ]);
 
         // Tạo tài khoản cho Professor mới
         Account::create([
             'username' => $newProfessorID,
             'password' => Hash::make('123456'),
             'role' => 'professor',
         ]);
 
         return redirect()->route('department.professors.index')->with('success', 'Professor đã được thêm thành công.');
     }

    // Hiển thị form chỉnh sửa professor
    public function edit(Professor $professor)
    {
        $departmentID = Auth::user()->username; // Lấy username của tài khoản đăng nhập làm DepartmentID
        $department = Department::find($departmentID);
        $departmentName = $department->DepartmentName;
        return view('department.professors.edit', compact('professor', 'departmentName'));
    }

    // Cập nhật thông tin professor trong cơ sở dữ liệu
    public function update(Request $request, Professor $professor)
    {
        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'ProfessorName' => 'required|string',
            'ProfessorGmail' => 'required|email|unique:professors,ProfessorGmail,' . $professor->ProfessorID . ',ProfessorID',
            'ProfessorPhone' => 'required|string|unique:professors,ProfessorPhone,' . $professor->ProfessorID . ',ProfessorID',
        ]);

        // Cập nhật Professor
        $professor->update([
            'ProfessorName' => $validated['ProfessorName'],
            'ProfessorGmail' => $validated['ProfessorGmail'],
            'ProfessorPhone' => $validated['ProfessorPhone'],
        ]);

        return redirect()->route('department.professors.index')->with('success', 'Professor đã được cập nhật thành công.');
    }

    // Xóa một professor khỏi cơ sở dữ liệu
    public function destroy(Professor $professor)
    {
        // Xóa tài khoản liên quan đến professor trước
        Account::where('username', $professor->ProfessorID)->delete();

        // Xóa professor
        $professor->delete();
        return redirect()->route('department.professors.index')->with('success', 'Professor đã được xóa thành công.');
    }
}
