@extends('layouts.app')

@section('head')
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome cho icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom styles for a grand and modern interface -->
    <style>
        /* Biến màu để dễ dàng tùy chỉnh */
        :root {
            --primary-color: #007bff; /* Màu chủ đạo, xanh dương đậm */
            --secondary-color: #6c757d; /* Màu phụ, xám */
            --accent-color: #ffc107; /* Màu nhấn, vàng */
            --background-gradient-start: #1e3c72; /* Màu bắt đầu gradient nền */
            --background-gradient-end: #2a5298; /* Màu kết thúc gradient nền */
            --card-background: #ffffff; /* Màu nền thẻ/container */
            --text-color-dark: #343a40; /* Màu chữ tối */
            --text-color-light: #f8f9fa; /* Màu chữ sáng */
            --shadow-color: rgba(0, 0, 0, 0.15); /* Màu bóng đổ */
            --border-color: #dee2e6; /* Màu đường viền */
        }

        body {
            background: linear-gradient(135deg, var(--background-gradient-start), var(--background-gradient-end));
            color: var(--text-color-dark);
            font-family: 'Nunito', sans-serif; /* Phông chữ hiện đại */
            font-weight: 400;
            line-height: 1.6;
            overflow-x: hidden; /* Ngăn thanh cuộn ngang */
            padding-bottom: 50px; /* Khoảng cách dưới để không bị sát chân trang */
        }

        .container {
            background: var(--card-background);
            border-radius: 15px; /* Bo tròn mạnh hơn */
            padding: 2.5rem; /* Tăng padding */
            box-shadow: 0 15px 40px var(--shadow-color); /* Bóng đổ sâu hơn */
            margin-top: 3rem; /* Tăng margin top */
            margin-bottom: 3rem;
        }

        h2 {
            font-family: 'Playfair Display', serif; /* Phông chữ tiêu đề sang trọng */
            font-weight: 700;
            color: var(--text-color-dark);
            margin-bottom: 2rem; /* Tăng margin bottom */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.05); /* Bóng đổ chữ tinh tế */
            letter-spacing: 0.5px; /* Khoảng cách chữ rộng hơn một chút */
            text-transform: uppercase; /* Chữ in hoa */
            border-bottom: 3px solid var(--accent-color); /* Gạch chân tiêu đề bằng màu nhấn */
            padding-bottom: 0.75rem;
            display: inline-block; /* Để gạch chân vừa với độ dài tiêu đề */
        }

        /* Calendar styling */
        /* Calendar styling */
        .calendar {
        border-collapse: collapse;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 8px 20px var(--shadow-color);
        border: 1px solid var(--border-color);
    }

    .calendar th, .calendar td {
        border: 1px solid var(--border-color);
        width: 14.28%;
        /* TĂNG CHIỀU CAO Ô NGÀY ĐỂ RỘNG RÃI HƠN */
        height: 200px; /* Tăng từ 160px lên 200px (hoặc hơn tùy ý) */
        vertical-align: top;
        position: relative;
        padding: 15px; /* Tăng padding một chút cho thoáng hơn */
        background: var(--card-background);
        transition: background-color 0.3s ease;
        font-size: 0.95rem; /* Giảm nhẹ font chữ trong ô ngày nếu cần */
    }

    .calendar th {
        background: var(--primary-color);
        color: var(--text-color-light);
        text-align: center;
        font-weight: 600;
        padding: 18px 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
    }

    .calendar td:hover {
        background-color: #f0f0f0;
    }

    .date-number {
        position: absolute;
        top: 10px;
        right: 15px; /* Chỉnh lại vị trí một chút cho vừa padding mới */
        font-size: 1.4rem; /* Tăng font size số ngày một chút cho cân đối */
        font-weight: 700;
        color: var(--primary-color);
        opacity: 0.8;
    }

    .session {
        margin-top: 25px; /* GIẢM MARGIN TOP CỦA SESSION ĐỂ GẦN VỚI NỘI DUNG TRÊN HƠN */
        padding: 12px; /* Giảm padding một chút cho session */
        background: rgba(var(--primary-color-rgb), 0.07);
        border-left: 7px solid var(--primary-color);
        border-radius: 7px;
        box-shadow: 0 3px 10px var(--shadow-color);
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        cursor: pointer;
    }

    .session:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        background: rgba(var(--primary-color-rgb), 0.12);
    }

    .session h6,
    .session p {
        margin: 0;
        line-height: 1.5;
        margin-bottom: 4px; /* Giảm margin bottom chút xíu */
        color: var(--text-color-dark);
        font-size: 0.95rem; /* Giảm nhẹ font chữ trong session nếu cần */
    }

    .session h6 {
        font-weight: 600;
        font-size: 1rem; /* Giảm nhẹ font size h6 */
    }

    .session p {
        font-size: 0.9rem; /* Giảm nhẹ font size p */
        color: #555;
    }

    .session-actions {
        margin-top: 8px; /* Giảm margin top action */
        text-align: right;
    }

    .session-actions a,
    .session-actions button {
        margin-left: 2px; /* Giảm margin left nút action */
        font-size: 0.85rem; /* Giảm font size nút action */
        padding: 6px 6px; /* Giảm padding nút action */
    }

        /* Filter form style */
        .filter-form {
            background: #f8f9fa; /* Nền form */
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow-color); /* Bóng đổ form */
            margin-bottom: 2rem;
            border: 1px solid var(--border-color); /* Border form */
        }

        .filter-form label {
            font-weight: 600;
            color: var(--text-color-dark);
            margin-bottom: 0.5rem; /* Khoảng cách label */
            display: block; /* Để label xuống dòng */
        }

        .filter-form select {
            border-radius: 8px; /* Bo tròn select */
            padding: 10px 12px;
            border: 1px solid var(--border-color); /* Border select */
            font-size: 1rem;
            transition: border-color 0.2s ease; /* Hiệu ứng border khi focus */
        }

        .filter-form select:focus {
            border-color: var(--primary-color); /* Border khi focus */
            box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25); /* Bóng đổ focus */
            outline: 0; /* Loại bỏ outline mặc định */
        }

        /* Buttons */
        .btn {
            border-radius: 8px; /* Bo tròn nút */
            padding: 12px 25px; /* Padding nút */
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bóng đổ nút */
            transition: transform 0.2s ease, box-shadow 0.2s ease; /* Hiệu ứng nút */
            border: none; /* Loại bỏ border mặc định của Bootstrap nếu muốn */
        }

        .btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25) !important; /* Bóng đổ focus nút */
            outline: 0 !important; /* Loại bỏ outline focus nút */
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--text-color-light);
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Màu hover đậm hơn */
            border-color: #0056b3;
            transform: translateY(-2px); /* Nhấc nút lên khi hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Bóng đổ đậm hơn khi hover */
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: var(--text-color-light);
        }

        .btn-secondary:hover {
            background-color: #545b62; /* Màu hover đậm hơn */
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: var(--text-color-light);
        }

        .btn-success:hover {
            background-color: #1e7e34; /* Màu hover đậm hơn */
            border-color: #1e7e34;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: var(--text-color-light);
        }

        .btn-danger:hover {
            background-color: #c82333; /* Màu hover đậm hơn */
            border-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Navigation Tháng */
        .d-flex.justify-content-between.align-items-center.mb-4 {
            margin-top: 1.5rem; /* Khoảng cách phía trên navigation tháng */
            padding: 1rem 0; /* Padding trên dưới cho navigation tháng */
            border-bottom: 1px solid var(--border-color); /* Đường kẻ dưới navigation tháng */
        }

        .d-flex.justify-content-between.align-items-center.mb-4 h4 {
            font-weight: 600;
            color: var(--text-color-dark);
            margin: 0 1rem; /* Khoảng cách hai bên tiêu đề tháng */
        }

        /* Thông báo */
        .alert {
            border-radius: 8px;
            margin-bottom: 1.5rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 3px 8px var(--shadow-color);
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #c3e6cb;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #85640a;
            border-left: 5px solid #ffeeba;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #f5c6cb;
        }

        /* Tooltip */
        .tooltip-inner {
            background-color: var(--text-color-dark);
            color: var(--text-color-light);
            border-radius: 8px;
            padding: 15px;
            font-size: 0.95rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .bs-tooltip-end .tooltip-arrow::before, .bs-tooltip-auto[data-popper-placement^=right] .tooltip-arrow::before {
            border-right-color: var(--text-color-dark);
        }

        .bs-tooltip-start .tooltip-arrow::before, .bs-tooltip-auto[data-popper-placement^=left] .tooltip-arrow::before {
            border-left-color: var(--text-color-dark);
        }

        .bs-tooltip-top .tooltip-arrow::before, .bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before {
            border-top-color: var(--text-color-dark);
        }

        .bs-tooltip-bottom .tooltip-arrow::before, .bs-tooltip-auto[data-popper-placement^=bottom] .tooltip-arrow::before {
            border-bottom-color: var(--text-color-dark);
        }

    </style>
@endsection

@section('content')
<div class="container">
    <h2 class="text-center">Quản Lý Thời Khóa Biểu</h2>
    <!-- Return Button -->
    <div class="mb-3">
        <a href="{{ route('pdt.departments.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Trở về</a>
    </div>

    <!-- Navigation Tháng -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('pdt.schedules.index', [
            'month' => $currentDate->copy()->subMonth()->month,
            'year' => $currentDate->copy()->subMonth()->year,
            'departmentID' => $selectedDepartment,
            'professorID' => $selectedProfessor,
            'subjectID' => $selectedSubject
        ]) }}" class="btn btn-secondary"><i class="fas fa-chevron-left me-2"></i> Tháng Trước</a>
        <h4 class="m-0">{{ $currentDate->format('F Y') }}</h4>
        <a href="{{ route('pdt.schedules.index', [
            'month' => $currentDate->copy()->addMonth()->month,
            'year' => $currentDate->copy()->addMonth()->year,
            'departmentID' => $selectedDepartment,
            'professorID' => $selectedProfessor,
            'subjectID' => $selectedSubject
        ]) }}" class="btn btn-secondary">Tháng Sau <i class="fas fa-chevron-right ms-2"></i></a>
    </div>

    <!-- Filter Form -->
    <div class="mb-4 filter-form">
        <form action="{{ route('pdt.schedules.index') }}" method="GET" class="row g-3" id="filterForm">
            <div class="col-md-4">
                <label for="departmentID" class="form-label"><i class="fas fa-building me-1"></i> Lọc theo Khoa</label>
                <select id="departmentID" name="departmentID" class="form-select">
                    <option value="">-- Tất cả Khoa --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->DepartmentID }}" {{ (isset($selectedDepartment) && $selectedDepartment == $department->DepartmentID) ? 'selected' : '' }}>
                            {{ $department->DepartmentName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="professorID" class="form-label"><i class="fas fa-chalkboard-teacher me-1"></i> Lọc theo Giảng viên</label>
                <select id="professorID" name="professorID" class="form-select">
                    <option value="">-- Tất cả Giảng viên --</option>
                    @foreach($professors as $professor)
                        <option value="{{ $professor->ProfessorID }}" {{ (isset($selectedProfessor) && $selectedProfessor == $professor->ProfessorID) ? 'selected' : '' }}>
                            {{ $professor->ProfessorName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="subjectID" class="form-label"><i class="fas fa-book me-1"></i> Lọc theo Môn học</label>
                <select id="subjectID" name="subjectID" class="form-select">
                    <option value="">-- Tất cả Môn học --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->SubjectID }}" {{ (isset($selectedSubject) && $selectedSubject == $subject->SubjectID) ? 'selected' : '' }}>
                            {{ $subject->SubjectName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i> Lọc</button>
                <a href="{{ route('pdt.schedules.index') }}" class="btn btn-secondary"><i class="fas fa-undo me-2"></i> Reset</a>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="mb-4 d-flex gap-3">
        <form action="{{ route('pdt.schedules.generate') }}" method="POST" id="insertNewScheduleForm">
            @csrf
            <input type="hidden" name="option" value="insert_new">
            <button type="submit" class="btn btn-success" {{ $canInsertNewSchedule ? '' : 'disabled' }}><i class="fas fa-plus-circle me-2"></i> Chèn Lịch Mới</button>
        </form>
        <form action="{{ route('pdt.schedules.deleteAll') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tất cả thời khóa biểu không?')">
            @csrf
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i> Xóa Tất Cả</button>
        </form>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i> {{ session('error') }}</div>
    @endif

    <!-- Calendar Display -->
    @if(!empty($calendar))
        <table class="table calendar">
            <thead>
                <tr>
                    <th class="text-center">Chủ Nhật</th>
                    <th class="text-center">Thứ 2</th>
                    <th class="text-center">Thứ 3</th>
                    <th class="text-center">Thứ 4</th>
                    <th class="text-center">Thứ 5</th>
                    <th class="text-center">Thứ 6</th>
                    <th class="text-center">Thứ 7</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @for ($blank = 0; $blank < $startDayOfWeek; $blank++)
                        <td></td>
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $date = $currentDate->copy()->day($day)->format('Y-m-d');
                            $dayOfWeek = $currentDate->copy()->day($day)->dayOfWeek;
                        @endphp
                        <td>
                            <div class="date-number">{{ $day }}</div>
                            @if(isset($calendar[$date]))
                                @foreach ($calendar[$date] as $session)
                                    <div class="session" data-bs-toggle="tooltip" data-bs-html="true" title="
                                        <strong><i class='fas fa-clock me-1'></i> Ca {{ $session['session'] }}:</strong> {{ $session['session_time'] }}<br>
                                        <strong><i class='fas fa-door-open me-1'></i> Phòng:</strong> {{ $session['room'] }}<br>
                                        <strong><i class='fas fa-book-open me-1'></i> Môn:</strong> {{ $session['subject'] }}<br>
                                        <strong><i class='fas fa-user-tie me-1'></i> GV:</strong> {{ $session['professor'] }}
                                    ">
                                        <h6><i class="fas fa-clock me-1"></i> Ca {{ $session['session'] }}: {{ $session['session_time'] }}</h6>
                                        <p><i class="fas fa-door-open me-1"></i> <strong>Phòng:</strong> {{ $session['room'] }}</p>
                                        <p><i class="fas fa-book-open me-1"></i> <strong>Môn:</strong> {{ $session['subject'] }}</p>
                                        <p><i class="fas fa-user-tie me-1"></i> <strong>GV:</strong> {{ $session['professor'] }}</p>
                                        <div class="session-actions">
                                            <a href="{{ $session['edit_url'] }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Sửa</a>
                                            <form action="{{ $session['delete_url'] }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thời khóa biểu này không?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-muted"><i class="far fa-calendar-times me-1"></i> Không có lịch</div>
                            @endif
                        </td>
                        @if ($dayOfWeek == 6 && $day != $daysInMonth)
                            </tr><tr>
                        @endif
                    @endfor

                    @for ($blank = $currentDate->copy()->day($daysInMonth)->dayOfWeek + 1; $blank <= 6; $blank++)
                        <td></td>
                    @endfor
                </tr>
            </tbody>
        </table>
    @endif
</div>
@endsection

@section('scripts')
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Fetch dropdown data based on department and professor
        function fetchDropdownData(departmentID, professorID, subjectID) {
            $.ajax({
                url: "{{ route('pdt.schedules.getProfessors') }}",
                type: "GET",
                data: { departmentID: departmentID },
                success: function(response) {
                    var professorSelect = $('#professorID');
                    professorSelect.empty();
                    professorSelect.append('<option value="">-- Tất cả Giảng viên --</option>');
                    $.each(response.professors, function(index, value) {
                        professorSelect.append('<option value="'+ value.ProfessorID +'" '+ (value.ProfessorID == professorID ? 'selected' : '') +'>'+ value.ProfessorName +'</option>');
                    });
                    fetchSubjects(departmentID, professorSelect.val(), subjectID);
                },
                error: function(xhr) {
                    console.error("Lỗi khi lấy danh sách giảng viên: " + xhr.responseText);
                }
            });
        }

        // Fetch subjects based on department and professor
        function fetchSubjects(departmentID, professorID, subjectID) {
            $.ajax({
                url: "{{ route('pdt.schedules.getSubjects') }}",
                type: "GET",
                data: { departmentID: departmentID, professorID: professorID },
                success: function(response) {
                    var subjectSelect = $('#subjectID');
                    subjectSelect.empty();
                    subjectSelect.append('<option value="">-- Tất cả Môn học --</option>');
                    $.each(response.subjects, function(index, value) {
                        subjectSelect.append('<option value="'+ value.SubjectID +'" '+ (value.SubjectID == subjectID ? 'selected' : '') +'>'+ value.SubjectName +'</option>');
                    });
                },
                error: function(xhr) {
                    console.error("Lỗi khi lấy danh sách môn học: " + xhr.responseText);
                }
            });
        }

        $('#departmentID').change(function() {
            var departmentID = $(this).val();
            var professorID = "";
            var subjectID = "";
            fetchDropdownData(departmentID, professorID, subjectID);
        });

        $('#professorID').change(function() {
            var departmentID = $('#departmentID').val();
            var professorID = $(this).val();
            var subjectID = "";
            fetchSubjects(departmentID, professorID, subjectID);
        });

        $(document).ready(function() {
            var departmentID = $('#departmentID').val();
            var professorID = "{{ old('professorID', $selectedProfessor) }}";
            var subjectID = "{{ old('subjectID', $selectedSubject) }}";
            if(departmentID){
                fetchDropdownData(departmentID, professorID, subjectID);
            }
        });
    </script>
@endsection