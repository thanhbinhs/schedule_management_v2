<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Professor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleProfessorController extends Controller
{
    /**
     * Display a listing of the schedules for the professor.
     */
    public function index(Request $request)
    {
        // Get the currently logged-in professor
        $professorID = auth()->user()->username;

        // Get month and year from request, default to current month and year
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Create a Carbon instance for the selected month and year
        $currentDate = Carbon::create($year, $month, 1);

        // Get number of days in the month
        $daysInMonth = $currentDate->daysInMonth;

        // Get start and end dates of the month
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // Get schedules for the professor in the selected month
        $schedules = Schedule::with(['room', 'subject'])
            ->where('professor_id', $professorID)
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('date')
            ->orderBy('session_number')
            ->get();

        // Prepare schedule data by date
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
                'edit_url' => route('professor.schedules.edit', $schedule),
            ];
        }

        // Get the day of the week for the first day of the month
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sunday) - 6 (Saturday)

        return view('professor.schedules.index', compact('calendar','schedules', 'currentDate', 'daysInMonth', 'startDayOfWeek'));
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule)
    {
        // Get the currently logged-in professor
        $professorID = auth()->user()->username;
    
        // Kiểm tra xem giáo viên có phải là người được chỉ định cho lịch học không
        if ($schedule->professor_id !== $professorID) {
            abort(403, 'Unauthorized action.');
        }
    
        // Lấy ngày và ca học từ lịch hiện tại
        $date = $schedule->date;
        $sessionNumber = $schedule->session_number;
    
        // Lấy danh sách các phòng trống cho ngày và ca học đó
        $occupiedRooms = Schedule::where('date', $date)
            ->where('session_number', $sessionNumber)
            ->pluck('RoomID');
    
        // Lấy tất cả các phòng không bị chiếm
        $rooms = Room::whereNotIn('RoomID', $occupiedRooms)->get();
    
        // Lấy các môn học trong khoa đó
        $datpartmentID = Professor::where('ProfessorID', $professorID)->value('DepartmentID');
        $subjects = Subject::where('DepartmentID', $datpartmentID)->get();
    
        // Các ca học
        $sessions = [
            1 => '6h45-9h25',
            2 => '9h30-12h10',
            3 => '1h-3h40',
            4 => '3h45-6h25',
        ];
    
        return view('professor.schedules.edit', compact('schedule', 'rooms', 'subjects', 'sessions'));
    }
    

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Schedule $schedule)
{
    // Get the currently logged-in professor
    $professorID = auth()->user()->username;

    // Kiểm tra nếu lịch này không phải của giảng viên đang đăng nhập
    if ($schedule->professor_id !== $professorID) {
        abort(403, 'Unauthorized action.');
    }

    try {
        // Validate the input
        $validated = $request->validate([
            'RoomID' => 'required|string|exists:rooms,RoomID',
            'date' => 'required|date|after_or_equal:2024-12-01',
            'session_number' => 'required|integer|between:1,4',
            'subject_id' => 'nullable|exists:subjects,SubjectID',
        ]);

        // Check if the schedule already exists for the given room, date, and session
        $existing = Schedule::where('RoomID', $validated['RoomID'])
            ->where('date', $validated['date'])
            ->where('session_number', $validated['session_number'])
            ->where('id', '!=', $schedule->id)
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Thời khóa biểu cho phòng, ngày và ca học này đã tồn tại.');
        }

        // Update the schedule
        $schedule->update($validated);

        return redirect()->route('professor.schedules.index')->with('success', 'Thời khóa biểu đã được cập nhật thành công.');
    } catch (\Exception $e) {
        // Nếu có lỗi xảy ra, chuyển hướng về lại và thông báo lỗi
        return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi khi cập nhật thời khóa biểu: ' . $e->getMessage());
    }
}

}
