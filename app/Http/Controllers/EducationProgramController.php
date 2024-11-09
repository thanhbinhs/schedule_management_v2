<?php

namespace App\Http\Controllers;

use App\Models\EducationProgram;
use App\Models\Major;
use App\Models\Subject;
use Illuminate\Http\Request;

class EducationProgramController extends Controller
{
    /**
     * Hiển thị danh sách các Education Program.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departmentID = auth()->user()->username;

        $educationPrograms = EducationProgram::whereHas('major', function ($query) use ($departmentID) {
            $query->where('DepartmentID', $departmentID);
        })->get();
        return view('department.education_programs.index', compact('educationPrograms'));
    }

    /**
     * Hiển thị form tạo mới Education Program.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $department = auth()->user()->username;

        $majors = Major::where('DepartmentID', $department)->get();
        // Lấy danh sách Subject có DepartmentID giống với username
        $subjects = Subject::where('DepartmentID', $department)->get();
        return view('department.education_programs.create', compact('majors', 'subjects'));
    }

    /**
     * Lưu thông tin Education Program mới vào cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'EducationProgramName' => 'required|string|unique:education_programs,EducationProgramName',
            'MajorID' => 'required|integer|exists:majors,MajorID',
            'SubjectList' => 'required|array',
            'SubjectList.*' => 'integer|exists:subjects,SubjectID',
        ]);

        // Lưu SubjectList dưới dạng JSON
        $subjectList = json_encode($validated['SubjectList']);

        // Tạo mới Education Program
        EducationProgram::create([
            'EducationProgramName' => $validated['EducationProgramName'],
            'MajorID' => $validated['MajorID'],
            'SubjectList' => $subjectList,
        ]);

        return redirect()->route('department.education_programs.index')->with('success', 'Education Program đã được thêm thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa Education Program.
     *
     * @param  \App\Models\EducationProgram  $educationProgram
     * @return \Illuminate\Http\Response
     */
    public function edit(EducationProgram $educationProgram)
    {
        $department = auth()->user()->username;

        $majors = Major::where('DepartmentID', $department)->get();
        // Lấy danh sách Subject có DepartmentID giống với username
        $subjects = Subject::where('DepartmentID', $department)->get();
        $selectedSubjects = json_decode($educationProgram->SubjectList, true);
        return view('department.education_programs.edit', compact('educationProgram', 'majors', 'subjects', 'selectedSubjects'));
    }

    /**
     * Cập nhật thông tin Education Program trong cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EducationProgram  $educationProgram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EducationProgram $educationProgram)
    {
        // Xác thực dữ liệu nhập vào
        $validated = $request->validate([
            'EducationProgramName' => 'required|string|unique:education_programs,EducationProgramName,' . $educationProgram->EducationProgramID . ',EducationProgramID',
            'MajorID' => 'required|integer|exists:majors,MajorID',
            'SubjectList' => 'required|array',
            'SubjectList.*' => 'integer|exists:subjects,SubjectID',
        ]);

        // Lưu SubjectList dưới dạng JSON
        $subjectList = json_encode($validated['SubjectList']);

        // Cập nhật Education Program
        $educationProgram->update([
            'EducationProgramName' => $validated['EducationProgramName'],
            'MajorID' => $validated['MajorID'],
            'SubjectList' => $subjectList,
        ]);

        return redirect()->route('department.education_programs.index')->with('success', 'Education Program đã được cập nhật thành công.');
    }

    /**
     * Xóa một Education Program khỏi cơ sở dữ liệu.
     *
     * @param  \App\Models\EducationProgram  $educationProgram
     * @return \Illuminate\Http\Response
     */
    public function destroy(EducationProgram $educationProgram)
    {
        $educationProgram->delete();
        return redirect()->route('department.education_programs.index')->with('success', 'Education Program đã được xóa thành công.');
    }
}
