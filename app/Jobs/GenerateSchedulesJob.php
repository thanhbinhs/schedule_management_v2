<?php

namespace App\Jobs;

use App\Models\Schedule;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Professor;
use App\Models\Department;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateSchedulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $option;
    protected $startDate;
    protected $endDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($option, Carbon $startDate, Carbon $endDate)
    {
        $this->option = $option;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Xử lý các tùy chọn 'create_new' hoặc 'insert_new'
        if ($this->option === 'create_new') {
            // Xóa tất cả các lịch hiện tại
            Schedule::truncate();
        } elseif ($this->option === 'insert_new') {
            // Kiểm tra các môn học mới chưa được lên lịch
            $newSubjects = Subject::doesntHave('schedules')->get();

            if ($newSubjects->isEmpty()) {
                Log::info('Không có môn học mới để chèn thêm lịch.');
                return;
            }

            // Sử dụng chỉ các môn học mới để lên lịch
            $subjects = $newSubjects;
        } else {
            // Nếu không có tùy chọn, sử dụng tất cả các môn học
            $subjects = Subject::with('department')->get();
        }

        // Định nghĩa các khung thời gian
        $sessions = [
            1 => '6h45-9h25',
            2 => '9h30-12h10',
            3 => '13h00-15h40',
            4 => '15h45-18h25',
        ];

        // Lấy dữ liệu cần thiết
        $rooms = Room::all();
        $professors = Professor::with('department')->get()->groupBy('DepartmentID');

        // Kiểm tra sự tồn tại của dữ liệu
        if ($subjects->isEmpty()) {
            Log::error('Không có môn học để tạo lịch.');
            return;
        }

        if ($professors->isEmpty()) {
            Log::error('Không có giáo viên để tạo lịch.');
            return;
        }

        // Chuẩn bị bản đồ các ca học cho mỗi môn
        $subjectSessionMap = [];
        foreach ($subjects as $subject) {
            $credits = $subject->credits; // Truy cập thông qua accessor
            $sessionsPerWeek = $credits;  // 1 tín chỉ = 1 ca học mỗi tuần
            $totalSessions = ceil($subject->SubjectLessons / 3); // Tổng số ca học

            $subjectSessionMap[$subject->SubjectID] = [
                'total_sessions'       => $totalSessions,
                'sessions_per_week'    => $sessionsPerWeek,
                'scheduled_sessions'   => 0, // Số ca học đã được lên lịch
            ];
        }

        // Xáo trộn môn học để phân bổ ngẫu nhiên
        $shuffledSubjects = $subjects->shuffle();

        // Khởi tạo đếm số ca học đã giao cho mỗi giáo viên để cân bằng khối lượng công việc
        $professorAssignmentCounts = [];
        foreach ($professors as $deptId => $profList) {
            foreach ($profList as $professor) {
                $professorAssignmentCounts[$professor->ProfessorID] = 0;
            }
        }

        // Tải trước các lịch hiện có để giảm số lượng truy vấn
        $existingSchedules = Schedule::whereBetween('date', [$this->startDate->toDateString(), $this->endDate->toDateString()])
            ->get();

        // Tổ chức các lịch theo RoomID, ngày, và session_number
        $roomSchedules = [];
        // Tổ chức các lịch theo ProfessorID, ngày, và session_number
        $professorSchedules = [];

        foreach ($existingSchedules as $schedule) {
            $roomKey = "{$schedule->RoomID}_{$schedule->date}_{$schedule->session_number}";
            $roomSchedules[$roomKey] = true;

            if ($schedule->professor_id) {
                $profKey = "{$schedule->professor_id}_{$schedule->date}_{$schedule->session_number}";
                $professorSchedules[$profKey] = true;
            }
        }

        // Tạo một mảng để theo dõi các ngày đã được lên lịch cho mỗi môn học
        $subjectScheduledDays = [];
        foreach ($subjects as $subject) {
            $subjectScheduledDays[$subject->SubjectID] = [];
        }

        // Bắt đầu lên lịch trong một giao dịch để đảm bảo tính toàn vẹn dữ liệu
        DB::transaction(function () use (
            $rooms,
            $sessions,
            $shuffledSubjects,
            $professors,
            &$subjectSessionMap,
            &$professorAssignmentCounts,
            &$roomSchedules,
            &$professorSchedules,
            &$subjectScheduledDays
        ) {
            // Lặp tuần
            $currentDate = $this->startDate->copy();

            while ($currentDate->lte($this->endDate)) {
                // Xác định phạm vi tuần hiện tại
                $weekStart = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
                $weekEnd = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);

                foreach ($shuffledSubjects as $subject) {
                    $subjectID = $subject->SubjectID;
                    $department_id = $subject->DepartmentID;

                    // Kiểm tra nếu đã lên lịch đủ ca cho môn này
                    if ($subjectSessionMap[$subjectID]['scheduled_sessions'] >= $subjectSessionMap[$subjectID]['total_sessions']) {
                        continue;
                    }

                    // Số ca cần lên lịch trong tuần này
                    $sessionsThisWeek = $subjectSessionMap[$subjectID]['sessions_per_week'];
                    $scheduledThisWeek = 0;

                    // Tính số ca đã lên lịch trong tuần cho môn này
                    foreach ($shuffledSubjects as $subj) {
                        if ($subj->SubjectID === $subjectID) {
                            // Vì chúng ta đang lên lịch, nên không có ca học hiện có ngoài những gì đã được tải trước
                            break;
                        }
                    }

                    $remainingSessionsThisWeek = $sessionsThisWeek - $scheduledThisWeek;

                    if ($remainingSessionsThisWeek <= 0) {
                        continue;
                    }

                    // Số ca sẽ được phân bổ trong tuần này
                    $sessionsToAssign = min($remainingSessionsThisWeek, $subjectSessionMap[$subjectID]['total_sessions'] - $subjectSessionMap[$subjectID]['scheduled_sessions']);

                    for ($s = 0; $s < $sessionsToAssign; $s++) {
                        // Phân bổ ca học theo thứ trong tuần để đảm bảo trải đều từ Thứ 2 đến Thứ 7
                        $dayOffset = $s % 6; // 0 (Thứ 2) đến 5 (Thứ 7)
                        $scheduledDay = $weekStart->copy()->addDays($dayOffset);

                        if ($scheduledDay->lt($this->startDate) || $scheduledDay->gt($this->endDate)) {
                            continue; // Bỏ qua các ngày ngoài phạm vi
                        }

                        // Kiểm tra xem môn này đã có ca học trong ngày này chưa
                        if (in_array($scheduledDay->toDateString(), $subjectScheduledDays[$subjectID])) {
                            continue; // Đã có ca học trong ngày này
                        }

                        // Tìm một session_number có sẵn
                        $availableSessionNumbers = array_keys($sessions);
                        shuffle($availableSessionNumbers);

                        $assigned = false;

                        foreach ($availableSessionNumbers as $session_number) {
                            // Kiểm tra xem phòng có sẵn không bằng cách sử dụng dữ liệu đã tải trước
                            $availableRoom = null;

                            foreach ($rooms->shuffle() as $room) {
                                $roomKey = "{$room->RoomID}_{$scheduledDay->toDateString()}_{$session_number}";

                                if (!isset($roomSchedules[$roomKey])) {
                                    $availableRoom = $room;
                                    // Đánh dấu phòng này đã bị chiếm dụng
                                    $roomSchedules[$roomKey] = true;
                                    break;
                                }
                            }

                            if (!$availableRoom) {
                                continue; // Không có phòng nào sẵn cho ca học này trong ngày này
                            }

                            // Tìm một giáo viên có sẵn trong khoa của môn học
                            $availableProfessors = $professors->get($department_id);

                            if (!$availableProfessors || $availableProfessors->isEmpty()) {
                                Log::warning("Không tìm thấy giáo viên cho DepartmentID: {$department_id}");
                                continue; // Không có giáo viên nào sẵn sàng
                            }

                            // Sắp xếp giáo viên theo số lượng ca học đã được giao để cân bằng khối lượng công việc
                            $availableProfessors = $availableProfessors->sortBy(function($professor) use ($professorAssignmentCounts) {
                                return $professorAssignmentCounts[$professor->ProfessorID];
                            });

                            $professor = null;

                            foreach ($availableProfessors as $potentialProfessor) {
                                $profKey = "{$potentialProfessor->ProfessorID}_{$scheduledDay->toDateString()}_{$session_number}";

                                if (!isset($professorSchedules[$profKey])) {
                                    $professor = $potentialProfessor;
                                    // Đánh dấu giáo viên này đã bị chiếm dụng
                                    $professorSchedules[$profKey] = true;
                                    break;
                                }
                            }

                            if (!$professor) {
                                continue; // Không có giáo viên nào sẵn sàng cho ca học này trong ngày này
                            }

                            // Tạo lịch học
                            Schedule::create([
                                'RoomID'         => $availableRoom->RoomID,
                                'date'           => $scheduledDay->toDateString(),
                                'session_number' => $session_number,
                                'subject_id'     => $subjectID,
                                'professor_id'   => $professor->ProfessorID,
                            ]);

                            // Cập nhật số ca học đã được lên lịch
                            $subjectSessionMap[$subjectID]['scheduled_sessions']++;
                            $professorAssignmentCounts[$professor->ProfessorID]++;
                            $assigned = true;

                            // Đánh dấu ngày này đã được lên lịch cho môn học này
                            $subjectScheduledDays[$subjectID][] = $scheduledDay->toDateString();

                            break; // Đã gán ca học, thoát vòng lặp session_number
                        }

                        if (!$assigned) {
                            // Nếu không thể gán ca học, ghi log để kiểm tra sau
                            Log::warning("Không thể lên lịch cho môn học ID: {$subjectID} vào ngày: {$scheduledDay->toDateString()} trong ca học nào cả.");
                            continue;
                        }
                    }
                }

                // Di chuyển sang tuần tiếp theo
                $currentDate->addWeek();
            }
        });
        
            // Sau khi giao dịch, kiểm tra các ca học chưa được lên lịch
            $unscheduledSubjectsFinal = collect($subjectSessionMap)->filter(function ($data) {
                return $data['scheduled_sessions'] < $data['total_sessions'];
            });

            if ($unscheduledSubjectsFinal->isNotEmpty()) {
                // Ghi log các môn học chưa được lên lịch đủ
                foreach ($unscheduledSubjectsFinal as $subjectID => $data) {
                    Log::warning("Môn học ID: {$subjectID} chưa được lên lịch đủ buổi. Còn lại: " . ($data['total_sessions'] - $data['scheduled_sessions']));
                }

                // Có thể thông báo cho quản trị viên hoặc thực hiện các hành động khác

                return; // Vì đây là một job trong queue, bạn có thể xử lý thông báo khác theo nhu cầu
            }

            Log::info('Thời khóa biểu đã được tạo tự động thành công.');
        
    }
}
