<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Professor;
use App\Models\Department;
use Illuminate\Http\Request;

use Carbon\Carbon;

class ScheduleDepartmentController extends Controller
{
    /**
     * Hiển thị danh sách thời khóa biểu cho khoa.
     */
    public function index(Request $request)
    {
        $departmentId = auth()->user()->username;
        $department = Department::find($departmentId);
        $departmentName = $department->DepartmentName;
        // Lọc các lịch học theo khoa đã đăng nhập
        $schedules = Schedule::with(['room', 'subject', 'professor'])
            ->whereHas('subject', function ($query) use ($departmentId) {
                $query->where('DepartmentID', $departmentId);
            })
            ->orderBy('date')
            ->orderBy('session_number')
            ->get();

        // Thông tin cần cho giao diện như tháng, năm
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $currentDate = Carbon::create($year, $month, 1);
        $daysInMonth = $currentDate->daysInMonth;
        $startDayOfWeek = $currentDate->dayOfWeek;

        // Lấy danh sách giảng viên của khoa
        $professors = Professor::where('DepartmentID', $departmentId)->get();

        // Chuẩn bị lịch theo ngày
        $calendar = [];
        foreach ($schedules as $schedule) {
            $date = Carbon::parse($schedule->date)->format('Y-m-d');
            if (!isset($calendar[$date])) {
                $calendar[$date] = [];
            }
            $calendar[$date][] = [
                'session' => $schedule->session_number,
                'room' => $schedule->room->RoomID,
                'subject' => $schedule->subject ? $schedule->subject->SubjectName : 'N/A',
                'professor' => $schedule->professor ? $schedule->professor->ProfessorName : 'N/A',
                'edit_url' => route('department.schedules.edit', $schedule),
                'delete_url' => route('department.schedules.destroy', $schedule),
            ];
        }

        return view('department.schedules.index', compact('schedules', 'calendar', 'currentDate', 'daysInMonth', 'startDayOfWeek', 'professors', 'departmentName'));
    }

    /**
     * Hiển thị form tạo mới thời khóa biểu.
     */
    public function create($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        $departmentName = $department->DepartmentName;
        $rooms = Room::all();
        $subjects = Subject::where('departmentID', $departmentId)->get();
        $professors = Professor::where('departmentID', $departmentId)->get();
        $sessions = [
            1 => '6h45-9h25',
            2 => '9h30-12h10',
            3 => '1h-3h40',
            4 => '3h45-6h25',
        ];

        return view('department.schedules.create', compact('rooms', 'subjects', 'professors', 'sessions', 'departmentName'));
    }

    /**
     * Hiển thị form chỉnh sửa thời khóa biểu.
     */
    public function edit(Schedule $schedule)
    {
        $departmentId = auth()->user()->username;
        $department = Department::find($departmentId);
        $departmentName = $department->DepartmentName;
                // Lấy ngày và ca học từ lịch hiện tại
                $date = $schedule->date;
                $sessionNumber = $schedule->session_number;

               // Lấy danh sách các phòng trống cho ngày và ca học đó
               $occupiedRooms = Schedule::where('date', $date)
               ->where('session_number', $sessionNumber)
               ->pluck('RoomID');
       
           // Lấy tất cả các phòng không bị chiếm
           $rooms = Room::whereNotIn('RoomID', $occupiedRooms)->get();
        $subjects = Subject::where('departmentID', $departmentId)->get();
        $professors = Professor::where('departmentID', $departmentId)->get();
        $sessions = [
            1 => '6h45-9h25',
            2 => '9h30-12h10',
            3 => '1h-3h40',
            4 => '3h45-6h25',
        ];

        return view('department.schedules.edit', compact('schedule', 'rooms', 'subjects', 'professors', 'sessions', 'departmentName'));
    }

    /**
     * Cập nhật thông tin thời khóa biểu trong cơ sở dữ liệu.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $departmentId = auth()->user()->username;
        // Kiểm tra xem thời khóa biểu có thuộc khoa này không
        if ($schedule->subject->departmentID !== $departmentId) {
            return redirect()->route('department.schedules.index', ['departmentId' => $departmentId])
                ->with('error', 'Bạn không có quyền chỉnh sửa thời khóa biểu này.');
        }

        $validated = $request->validate([
            'RoomID' => 'required|string|exists:rooms,RoomID',
            'date' => 'required|date|after_or_equal:2024-12-01',
            'session_number' => 'required|integer|between:1,4',
            'subject_id' => 'nullable|exists:subjects,SubjectID',
            'professor_id' => 'nullable|exists:professors,ProfessorID',
        ]);

        // Kiểm tra các xung đột về lịch học
        $existing = Schedule::where('RoomID', $validated['RoomID'])
            ->where('date', $validated['date'])
            ->where('session_number', $validated['session_number'])
            ->where('id', '!=', $schedule->id)
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Thời khóa biểu cho phòng, ngày và ca học này đã tồn tại.');
        }

        $schedule->update($validated);

        return redirect()->route('department.schedules.index', ['departmentId' => $departmentId])
            ->with('success', 'Thời khóa biểu đã được cập nhật thành công.');
    }
}
