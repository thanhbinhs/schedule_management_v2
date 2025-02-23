<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Professor;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Correct the Log import
use App\Jobs\GenerateSchedulesJob;

class ScheduleController extends Controller
{
    /**
     * Hiển thị danh sách thời khóa biểu.
     */
    public function index(Request $request)
    {
        // Validate inputs
        $validated = $request->validate([
            'departmentID' => 'nullable|exists:departments,DepartmentID',
            'professorID' => 'nullable|exists:professors,ProfessorID',
            'subjectID' => 'nullable|exists:subjects,SubjectID',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:3000',
        ]);

        // Get filter parameters
        $selectedDepartment = $validated['departmentID'] ?? null;
        $selectedProfessor = $validated['professorID'] ?? null;
        $selectedSubject = $validated['subjectID'] ?? null;
        $month = $validated['month'] ?? Carbon::now()->month;
        $year = $validated['year'] ?? Carbon::now()->year;

        // Determine if new schedules can be inserted
        $subjectIdsWithSchedules = Schedule::pluck('subject_id')->unique();
        $canInsertNewSchedule = Subject::whereNotIn('SubjectID', $subjectIdsWithSchedules)->exists();

        // Fetch all departments
        $departments = Department::all();

        // Fetch professors based on selected Department
        if ($selectedDepartment) {
            $professors = Professor::where('DepartmentID', $selectedDepartment)->get();
        } else {
            $professors = Professor::all();
        }

        // Fetch subjects based on selected Department and Professor
        if ($selectedDepartment && $selectedProfessor) {
            // Subjects from the selected Department and taught by the selected Professor
            $subjects = Subject::where('DepartmentID', $selectedDepartment)
                ->whereHas('professors', function ($query) use ($selectedProfessor) {
                    // Specify table name to resolve ambiguity
                    $query->where('professors.ProfessorID', $selectedProfessor);
                })
                ->get();
        } elseif ($selectedDepartment) {
            // Subjects from the selected Department
            $subjects = Subject::where('DepartmentID', $selectedDepartment)->get();
        } elseif ($selectedProfessor) {
            // Subjects taught by the selected Professor
            $subjects = Subject::whereHas('professors', function ($query) use ($selectedProfessor) {
                // Specify table name to resolve ambiguity
                $query->where('professors.ProfessorID', $selectedProfessor);
            })->get();
        } else {
            // All subjects
            $subjects = Subject::all();
        }

        // Get month and year from request or default to current
        $currentDate = Carbon::create($year, $month, 1);

        // Get number of days in the month
        $daysInMonth = $currentDate->daysInMonth;

        // Get start and end of the month
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // Build the query with filters
        $query = Schedule::with(['room', 'subject', 'professor'])
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('date')
            ->orderBy('session_number');

        // Apply Department filter if selected
        if ($selectedDepartment) {
            $query->where(function ($q) use ($selectedDepartment) {
                $q->whereHas('subject', function ($q) use ($selectedDepartment) {
                    $q->where('DepartmentID', $selectedDepartment);
                })
                ->orWhereHas('professor', function ($q) use ($selectedDepartment) {
                    $q->where('DepartmentID', $selectedDepartment);
                });
            });
        }

        // Apply Professor filter if selected
        if ($selectedProfessor) {
            $query->where('professor_id', $selectedProfessor);
        }

        // Apply Subject filter if selected
        if ($selectedSubject) {
            $query->where('subject_id', $selectedSubject);
        }

        // Execute the query
        $schedules = $query->get();

        // Prepare the calendar data
        $calendar = [];
        foreach ($schedules as $schedule) {
            $date = Carbon::parse($schedule->date)->format('Y-m-d');
            if (!isset($calendar[$date])) {
                $calendar[$date] = [];
            }

            // Create session time string
            $sessionTime = $this->getSessionTime($schedule->session_number);

            $calendar[$date][] = [
                'session' => $schedule->session_number,
                'session_time' => $sessionTime,
                'room' => $schedule->room->RoomID,
                'subject' => $schedule->subject ? $schedule->subject->SubjectName : 'N/A',
                'professor' => $schedule->professor ? $schedule->professor->ProfessorName : 'N/A',
                'edit_url' => route('pdt.schedules.edit', $schedule),
                'delete_url' => route('pdt.schedules.destroy', $schedule),
            ];
        }

        // Determine the day of the week for the first day of the month
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sunday) - 6 (Saturday)

        // Return the view with all necessary data
        return view('pdt.schedules.index', compact(
            'schedules',
            'calendar',
            'currentDate',
            'startDayOfWeek',
            'daysInMonth',
            'departments',
            'professors',
            'subjects',
            'selectedDepartment',
            'selectedProfessor',
            'selectedSubject',
            'canInsertNewSchedule'
        ));
    }

    /**
     * Helper function to get session time based on session number.
     */
    private function getSessionTime($session_number)
    {
        $sessions = [
            1 => '6h45-9h25',
            2 => '9h30-12h10',
            3 => '13h00-15h40',
            4 => '15h45-18h25',
        ];

        return $sessions[$session_number] ?? '00:00-00:00';
    }

     /**
     * Fetch professors based on Department via AJAX.
     */
    public function getProfessors(Request $request)
    {
        $departmentID = $request->input('departmentID');

        if ($departmentID) {
            $professors = Professor::where('DepartmentID', $departmentID)->get(['ProfessorID', 'ProfessorName']);
        } else {
            $professors = Professor::all(['ProfessorID', 'ProfessorName']);
        }

        return response()->json(['professors' => $professors]);
    }

    /**
     * Fetch subjects based on Department and Professor via AJAX.
     */
    public function getSubjects(Request $request)
    {
        $departmentID = $request->input('departmentID');
        $professorID = $request->input('professorID');

        if ($departmentID && $professorID) {
            // Subjects from the Department taught by the Professor
            $subjects = Subject::where('DepartmentID', $departmentID)
                ->whereHas('professors', function ($query) use ($professorID) {
                    $query->where('ProfessorID', $professorID);
                })
                ->get(['SubjectID', 'SubjectName']);
        } elseif ($departmentID) {
            // Subjects from the Department
            $subjects = Subject::where('DepartmentID', $departmentID)->get(['SubjectID', 'SubjectName']);
        } elseif ($professorID) {
            // Subjects taught by the Professor
            $subjects = Subject::whereHas('professors', function ($query) use ($professorID) {
                $query->where('ProfessorID', $professorID);
            })->get(['SubjectID', 'SubjectName']);
        } else {
            // All subjects
            $subjects = Subject::all(['SubjectID', 'SubjectName']);
        }

        return response()->json(['subjects' => $subjects]);
    }

    /**
     * Chuyển đổi số ca học thành thời gian bắt đầu.
     */
    private function convertSessionToTime($session_number)
    {
        $sessions = [
            1 => '06:45:00',
            2 => '09:30:00',
            3 => '13:00:00',
            4 => '15:45:00',
        ];

        return $sessions[$session_number] ?? '00:00:00';
    }

    /**
     * Chuyển đổi số ca học thành thời gian kết thúc.
     */
    private function convertSessionToEndTime($session_number)
    {
        $sessions = [
            1 => '09:25:00',
            2 => '12:10:00',
            3 => '15:40:00',
            4 => '18:25:00',
        ];

        return $sessions[$session_number] ?? '00:00:00';
    }

    /**
     * Hiển thị form tạo mới thời khóa biểu.
     */
    public function create()
    {
        $rooms = Room::all();
        $subjects = Subject::with('department')->get();
        $professors = Professor::with('department')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $sessions = [
            1 => '6h45-9h25',
            2 => '9h30-12h10',
            3 => '1h-3h40',
            4 => '3h45-6h25',
        ];

        return view('pdt.schedules.create', compact('rooms', 'subjects', 'professors', 'days', 'sessions'));
    }

    /**
     * Lưu thông tin thời khóa biểu mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'RoomID' => 'required|string|exists:rooms,RoomID',
            'date' => 'required|date|after_or_equal:2024-12-01',
            'session_number' => 'required|integer|between:1,4',
            'subject_id' => 'nullable|exists:subjects,SubjectID',
            'professor_id' => 'nullable|exists:professors,ProfessorID',
        ]);

        // Nếu subject_id và professor_id được cung cấp, kiểm tra DepartmentID
        if ($validated['subject_id'] && $validated['professor_id']) {
            $subject = Subject::find($validated['subject_id']);
            $professor = Professor::find($validated['professor_id']);

            if ($subject->DepartmentID !== $professor->DepartmentID) {
                return redirect()->back()->withInput()->with('error', 'Giảng viên phải thuộc khoa của môn học.');
            }
        }

        // Kiểm tra xem lịch đã tồn tại cho phòng, ngày và ca học chưa
        $existing = Schedule::where('RoomID', $validated['RoomID'])
            ->where('date', $validated['date'])
            ->where('session_number', $validated['session_number'])
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Thời khóa biểu cho phòng, ngày và ca học này đã tồn tại.');
        }

        Schedule::create($validated);

        return redirect()->route('pdt.schedules.index')->with('success', 'Thời khóa biểu đã được thêm thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa thời khóa biểu.
     */
    public function edit(Schedule $schedule)
    {
        $rooms = Room::all();
        $subjects = Subject::with('department')->get();
        $professors = Professor::with('department')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $sessions = [
            1 => '6h45-9h25',
            2 => '9h30-12h10',
            3 => '1h-3h40',
            4 => '3h45-6h25',
        ];

        return view('pdt.schedules.edit', compact('schedule', 'rooms', 'subjects', 'professors', 'days', 'sessions'));
    }

    /**
     * Cập nhật thông tin thời khóa biểu trong cơ sở dữ liệu.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'RoomID' => 'required|string|exists:rooms,RoomID',
            'date' => 'required|date|after_or_equal:2024-12-01',
            'session_number' => 'required|integer|between:1,4',
            'subject_id' => 'nullable|exists:subjects,SubjectID',
            'professor_id' => 'nullable|exists:professors,ProfessorID',
        ]);

        // Nếu subject_id và professor_id được cung cấp, kiểm tra DepartmentID
        if ($validated['subject_id'] && $validated['professor_id']) {
            $subject = Subject::find($validated['subject_id']);
            $professor = Professor::find($validated['professor_id']);

            if ($subject->DepartmentID !== $professor->DepartmentID) {
                return redirect()->back()->withInput()->with('error', 'Giảng viên phải thuộc khoa của môn học.');
            }
        }

        // Kiểm tra xem lịch đã tồn tại cho phòng, ngày và ca học chưa (trừ bản ghi hiện tại)
        $existing = Schedule::where('RoomID', $validated['RoomID'])
            ->where('date', $validated['date'])
            ->where('session_number', $validated['session_number'])
            ->where('id', '!=', $schedule->id)
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Thời khóa biểu cho phòng, ngày và ca học này đã tồn tại.');
        }

        // Kiểm tra xem giáo viên có trùng lịch trong ca học đó không (trừ bản ghi hiện tại)
        if ($validated['professor_id']) {
            $existingProfessorSchedule = Schedule::where('professor_id', $validated['professor_id'])
                ->where('date', $validated['date'])
                ->where('session_number', $validated['session_number'])
                ->where('id', '!=', $schedule->id)
                ->first();

            if ($existingProfessorSchedule) {
                return redirect()->back()->withInput()->with('error', 'Giảng viên này đã có ca học trong thời gian này.');
            }
        }

        $schedule->update($validated);

        return redirect()->route('pdt.schedules.index')->with('success', 'Thời khóa biểu đã được cập nhật thành công.');
    }

    /**
     * Xóa thời khóa biểu khỏi cơ sở dữ liệu.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('pdt.schedules.index')->with('success', 'Thời khóa biểu đã được xóa thành công.');
    }

    /**
     * Tạo thời khóa biểu tự động cho tất cả các phòng.
     */
  /**
     * Tạo thời khóa biểu tự động cho tất cả các phòng.
     **/
    public function generate(Request $request)
    {
        $option = $request->input('option');
    
        // Define scheduling period
        $startDate = Carbon::create(2025, 1, 1); // 1/12/2024
        $endDate = Carbon::create(2025, 2, 31);   // 31/1/2025
    
        // Dispatch the job
        GenerateSchedulesJob::dispatch($option, $startDate, $endDate);
    
        // Optionally, you can provide immediate feedback to the user
        return redirect()->route('pdt.schedules.index')->with('success', 'Thời khóa biểu đang được tạo. Vui lòng kiểm tra lại sau.');
    }
    

    /**
     * Xóa tất cả các thời khóa biểu khỏi cơ sở dữ liệu.
     */
    public function deleteAll()
    {
        // Xóa tất cả các bản ghi trong bảng Schedule
        Schedule::truncate();

        // Chuyển hướng về trang danh sách thời khóa biểu với thông báo
        return redirect()->route('pdt.schedules.index')->with('success', 'Tất cả thời khóa biểu đã được xóa thành công.');
    }

}
