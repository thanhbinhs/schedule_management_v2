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

class ScheduleController extends Controller
{
    /**
     * Hiển thị danh sách thời khóa biểu.
     */
    public function index(Request $request)
    {
        $schedules = Schedule::with(['room', 'subject', 'professor'])->orderBy('date')->orderBy('session_number')->get();
        // Lấy các tham số lọc từ request
        $selectedDepartment = $request->input('departmentID');
        $selectedProfessor = $request->input('professorID');

        // Kiểm tra nếu có môn học chưa được gán lịch học
    $subjectIdsWithSchedules = Schedule::pluck('subject_id')->unique();
    $canInsertNewSchedule = Subject::whereNotIn('SubjectID', $subjectIdsWithSchedules)->exists();

        // Lấy tất cả các Khoa và Giảng viên để hiển thị trong dropdown lọc
        $departments = Department::all();
        $professors = Professor::all();

        // Lấy tháng và năm từ request, nếu không có thì dùng tháng và năm hiện tại
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Tạo một đối tượng Carbon để làm cơ sở
        $currentDate = Carbon::create($year, $month, 1);

        // Lấy số ngày trong tháng
        $daysInMonth = $currentDate->daysInMonth;

        // Lấy thông tin ngày đầu tiên và cuối cùng của tháng
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // Xây dựng truy vấn với các tham số lọc
        $query = Schedule::with(['room', 'subject', 'professor'])
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('date')
            ->orderBy('session_number');

        // Áp dụng lọc theo Khoa nếu được chọn
        if ($selectedDepartment) {
            // Lọc theo Khoa thông qua Department của Subject hoặc Department của Professor
            $query->where(function ($q) use ($selectedDepartment) {
                $q->whereHas('subjects', function ($q) use ($selectedDepartment) {
                    $q->where('DepartmentID', $selectedDepartment);
                })
                    ->orWhereHas('professors', function ($q) use ($selectedDepartment) {
                        $q->where('DepartmentID', $selectedDepartment);
                    });
            });
        }

        // Áp dụng lọc theo Giảng viên nếu được chọn
        if ($selectedProfessor) {
            $query->where('ProfessorID', $selectedProfessor);
        }

        // Thực thi truy vấn
        $schedules = $query->get();

        // Chuẩn bị dữ liệu lịch học theo ngày
        $calendar = [];
        foreach ($schedules as $schedule) {
            $date = Carbon::parse($schedule->date)->format('Y-m-d');
            if (!isset($calendar[$date])) {
                $calendar[$date] = [];
            }

            $calendar[$date][] = [
                'session' => $schedule->session_number,
                'session_time' => $schedule->session_time, // Sử dụng accessor
                'room' => $schedule->room->RoomID,
                'subject' => $schedule->subject ? $schedule->subject->SubjectName : 'N/A',
                'professor' => $schedule->professor ? $schedule->professor->ProfessorName : 'N/A',
                'edit_url' => route('pdt.schedules.edit', $schedule),
                'delete_url' => route('pdt.schedules.destroy', $schedule),
            ];
        }

        // Lấy số thứ trong tuần của ngày đầu tiên để định vị cột bắt đầu
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sunday) - 6 (Saturday)

        // Xây dựng các tham số query
$queryParams = ['DepartmentID' => $selectedDepartment, 'ProfessorID' => $selectedProfessor];

// Chuyển hướng nếu cần thiết (chỉ khi điều kiện yêu cầu, hoặc nếu bạn cần thiết lập trạng thái URL)
if ($request->isMethod('post')) { // Hoặc một điều kiện khác phù hợp
    $url = route('pdt.schedules.index', $queryParams);
    return redirect($url);
}

        return view('pdt.schedules.index', compact('schedules', 'calendar', 'currentDate', 'startDayOfWeek', 'daysInMonth', 'departments', 'professors', 'selectedDepartment', 'selectedProfessor', 'canInsertNewSchedule'));
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
    public function generate(Request $request)
{
    $option = $request->input('option');

    if ($option === 'create_new') {
        // Xóa toàn bộ lịch cũ
        Schedule::truncate();
    } elseif ($option === 'insert_new') {
        // Kiểm tra xem có môn học mới được thêm vào không
        $newSubjects = Subject::doesntHave('schedules')->get();

        if ($newSubjects->isEmpty()) {
            return redirect()->route('pdt.schedules.index')->with('error', 'Không có môn học mới để chèn thêm lịch.');
        }
    }

    // Định nghĩa ngày bắt đầu và số ngày cần tạo
    $startDate = Carbon::create(2024, 12, 1); // 1/12/2024
    $totalDays = 60; // Số ngày muốn tạo, bạn có thể điều chỉnh

    // Định nghĩa các ca học
    $sessions = [
        1 => '6h45-9h25',
        2 => '9h30-12h10',
        3 => '1h-3h40',
        4 => '3h45-6h25',
    ];

    // Lấy tất cả các phòng
    $rooms = Room::all();

    // Lấy tất cả các môn học cùng với khoa để dễ dàng gán giáo viên
    $subjects = Subject::with('department')->get();

    // Lấy danh sách giáo viên cùng khoa với môn học và nhóm theo DepartmentID
    $professors = Professor::with('department')->get()->groupBy('DepartmentID');

    // Kiểm tra dữ liệu
    if ($subjects->isEmpty()) {
        return redirect()->route('pdt.schedules.index')->with('error', 'Không có môn học để tạo lịch.');
    }

    if ($professors->isEmpty()) {
        return redirect()->route('pdt.schedules.index')->with('error', 'Không có giáo viên để tạo lịch.');
    }

    // Tạo thời khóa biểu cho mỗi phòng, mỗi ngày và mỗi ca học
    DB::transaction(function () use ($rooms, $startDate, $totalDays, $sessions, $subjects, $professors) {
        for ($i = 0; $i < $totalDays; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $dayOfWeek = $currentDate->dayOfWeek; // 0 (Sunday) - 6 (Saturday)

            // Chỉ tạo lịch cho các ngày từ thứ 2 đến thứ 7
            if ($dayOfWeek === Carbon::SUNDAY) {
                continue; // Bỏ qua Chủ Nhật
            }

            foreach ($rooms as $room) {
                foreach ($sessions as $session_number => $session_time) {
                    // Kiểm tra xem lịch cho phòng, ngày, và ca học đã tồn tại chưa
                    $existingSchedule = Schedule::where('RoomID', $room->RoomID)
                        ->where('date', $currentDate->toDateString())
                        ->where('session_number', $session_number)
                        ->first();

                    // Nếu đã tồn tại, bỏ qua để tránh trùng lặp
                    if ($existingSchedule) {
                        continue;
                    }

                    // Chọn ngẫu nhiên một môn học
                    $subject = $subjects->random();

                    // Tìm danh sách giáo viên thuộc cùng khoa với môn học
                    $department_id = $subject->DepartmentID;
                    $availableProfessors = $professors->get($department_id);

                    if ($availableProfessors) {
                        $professor = null;

                        // Tìm giáo viên phù hợp, đảm bảo không dạy quá 3 lớp trong cùng ca học
                        foreach ($availableProfessors as $potentialProfessor) {
                            $classesInSession = Schedule::where('professor_id', $potentialProfessor->ProfessorID)
                                ->where('date', $currentDate->toDateString())
                                ->where('session_number', $session_number)
                                ->count();

                            if ($classesInSession < 1) {
                                $professor = $potentialProfessor;
                                break;
                            }
                        }

                        // Nếu không có giáo viên phù hợp, chuyển sang ca học tiếp theo
                        if (!$professor) {
                            continue;
                        }

                        // Tạo bản ghi thời khóa biểu
                        Schedule::create([
                            'RoomID' => $room->RoomID,
                            'date' => $currentDate->toDateString(),
                            'session_number' => $session_number,
                            'subject_id' => $subject->SubjectID,
                            'professor_id' => $professor->ProfessorID,
                        ]);
                    }
                }
            }
        }
    });

    return redirect()->route('pdt.schedules.index')->with('success', 'Thời khóa biểu đã được tạo tự động thành công.');
}

    
    

    public function deleteAll()
{
    // Xóa tất cả các bản ghi trong bảng Schedule
    Schedule::truncate();

    // Chuyển hướng về trang danh sách thời khóa biểu với thông báo
    return redirect()->route('pdt.schedules.index')->with('success', 'Tất cả thời khóa biểu đã được xóa thành công.');
}

// public function indexForDepartment(Request $request, $departmentId)
// {
//     // Lọc các lịch học theo khoa đã đăng nhập
//     $schedules = Schedule::with(['room', 'subject', 'professor'])
//         ->whereHas('subject', function ($query) use ($departmentId) {
//             $query->where('departmentID', $departmentId);
//         })
//         ->orderBy('date')
//         ->orderBy('session_number')
//         ->get();

//     // Thông tin cần cho giao diện như tháng, năm
//     $month = $request->input('month', Carbon::now()->month);
//     $year = $request->input('year', Carbon::now()->year);
//     $currentDate = Carbon::create($year, $month, 1);
//     $daysInMonth = $currentDate->daysInMonth;
//     $startDayOfWeek = $currentDate->dayOfWeek;

//     // Lấy danh sách giảng viên của khoa
//     $professors = Professor::where('departmentID', $departmentId)->get();

//     // Chuẩn bị lịch theo ngày
//     $calendar = [];
//     foreach ($schedules as $schedule) {
//         $date = Carbon::parse($schedule->date)->format('Y-m-d');
//         if (!isset($calendar[$date])) {
//             $calendar[$date] = [];
//         }
//         $calendar[$date][] = [
//             'session' => $schedule->session_number,
//             'room' => $schedule->room->RoomID,
//             'subject' => $schedule->subject ? $schedule->subject->SubjectName : 'N/A',
//             'professor' => $schedule->professor ? $schedule->professor->ProfessorName : 'N/A',
//             'edit_url' => route('department.schedules.edit', $schedule),
//             'delete_url' => route('department.schedules.destroy', $schedule),
//         ];
//     }

//     return view('department.schedules.index', compact('schedules', 'calendar', 'currentDate', 'daysInMonth', 'startDayOfWeek', 'professors'));
// }

// public function createForDepartment($departmentId)
// {
//     $rooms = Room::all();
//     $subjects = Subject::where('departmentID', $departmentId)->get();
//     $professors = Professor::where('departmentID', $departmentId)->get();
//     $sessions = [
//         1 => '6h45-9h25',
//         2 => '9h30-12h10',
//         3 => '1h-3h40',
//         4 => '3h45-6h25',
//     ];

//     return view('department.schedules.create', compact('rooms', 'subjects', 'professors', 'sessions'));
// }

// public function editForDepartment(Schedule $schedule, $departmentId)
// {
//     $rooms = Room::all();
//     $subjects = Subject::where('departmentID', $departmentId)->get();
//     $professors = Professor::where('departmentID', $departmentId)->get();
//     $sessions = [
//         1 => '6h45-9h25',
//         2 => '9h30-12h10',
//         3 => '1h-3h40',
//         4 => '3h45-6h25',
//     ];

//     return view('department.schedules.edit', compact('schedule', 'rooms', 'subjects', 'professors', 'sessions'));
// }

}
