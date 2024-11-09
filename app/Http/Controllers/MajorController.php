<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Department;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Hiển thị danh sách các Major.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $majorID = auth()->user()->username;
        $majors = Major::where('DepartmentID', $majorID)->get();
        return view('department.majors.index', compact('majors'));
    }

    /**
     * Hiển thị form tạo mới Major.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $department = auth()->user()->username;
        $departments = Department::where('DepartmentID', $department)->get();
        return view('department.majors.create', compact('departments'));
    }

    /**
     * Lưu thông tin Major mới vào cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'MajorName' => 'required|string|unique:majors,MajorName',
            'DepartmentID' => 'required|string|exists:departments,DepartmentID',
        ]);

        // Tạo mới Major
        Major::create([
            'MajorName' => $validated['MajorName'],
            'DepartmentID' => $validated['DepartmentID'],
        ]);

        return redirect()->route('department.majors.index')->with('success', 'Major đã được thêm thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa Major.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function edit(Major $major)
    {
        $department = auth()->user()->username;
        $departments = Department::where('DepartmentID', $department)->get();
        return view('department.majors.edit', compact('major', 'departments'));
    }

    /**
     * Cập nhật thông tin Major trong cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Major $major)
    {
        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'MajorName' => 'required|string|unique:majors,MajorName,' . $major->MajorID . ',MajorID',
            'DepartmentID' => 'required|string|exists:departments,DepartmentID',
        ]);

        // Cập nhật Major
        $major->update([
            'MajorName' => $validated['MajorName'],
            'DepartmentID' => $validated['DepartmentID'],
        ]);

        return redirect()->route('department.majors.index')->with('success', 'Major đã được cập nhật thành công.');
    }

    /**
     * Xóa một Major khỏi cơ sở dữ liệu.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function destroy(Major $major)
    {
        $major->delete();
        return redirect()->route('department.majors.index')->with('success', 'Major đã được xóa thành công.');
    }
}
