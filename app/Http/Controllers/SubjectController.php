<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Hiển thị danh sách các Subject.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Assume you have a `department_id` field in the authenticated user (assuming it's a Department account)
        $departmentID = auth()->user()->username;
        $department = Department::find($departmentID);
        $departmentName = $department->DepartmentName;
    
        // Get subjects that belong to the authenticated department
        $subjects = Subject::where('DepartmentID', $departmentID)->get();
    
        return view('department.subjects.index', compact('subjects', 'departmentName'));
    }

    /**
     * Hiển thị form tạo mới Subject.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Assume you have a `department_id` field in the authenticated user (assuming it's a Department account)
        $departmentID = auth()->user()->username;
        $department = Department::find($departmentID);
        $departmentName = $department->DepartmentName;
        return view('department.subjects.create', compact('department', 'departmentName'));
    }

    /**
     * Lưu thông tin Subject mới vào cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'SubjectName' => 'required|string|unique:subjects,SubjectName',
            'DepartmentID' => 'required|string|exists:departments,DepartmentID',
            'SubjectCredit' => 'required|integer|min:0',
            'SubjectLessons' => 'required|integer|min:0',
        ]);

        // Tạo mới Subject
        Subject::create([
            'SubjectName' => $validated['SubjectName'],
            'DepartmentID' => $validated['DepartmentID'],
            'SubjectCredit' => $validated['SubjectCredit'],
            'SubjectLessons' => $validated['SubjectLessons'],
        ]);

        return redirect()->route('department.subjects.index')->with('success', 'Subject đã được thêm thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa Subject.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        $departmentID = auth()->user()->username;
        $department = Department::find($departmentID);
        $departmentName = $department->DepartmentName;
        return view('department.subjects.edit', compact('subject', 'department', 'departmentName'));
    }

    /**
     * Cập nhật thông tin Subject trong cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'SubjectName' => 'required|string|unique:subjects,SubjectName,' . $subject->SubjectID . ',SubjectID',
            'DepartmentID' => 'required|string|exists:departments,DepartmentID',
            'SubjectCredit' => 'required|integer|min:0',
            'SubjectLessons' => 'required|integer|min:0',
        ]);

        // Cập nhật Subject
        $subject->update([
            'SubjectName' => $validated['SubjectName'],
            'DepartmentID' => $validated['DepartmentID'],
            'SubjectCredit' => $validated['SubjectCredit'],
            'SubjectLessons' => $validated['SubjectLessons'],
        ]);

        return redirect()->route('department.subjects.index')->with('success', 'Subject đã được cập nhật thành công.');
    }

    /**
     * Xóa một Subject khỏi cơ sở dữ liệu.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('department.subjects.index')->with('success', 'Subject đã được xóa thành công.');
    }
}
