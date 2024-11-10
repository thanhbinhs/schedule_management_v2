@extends('layouts.app')

@section('head')
<!-- Thêm Bootstrap CSS nếu chưa có -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .calendar th,
    .calendar td {
        width: 14.28%;
        vertical-align: top;
        height: 150px;
        border: 1px solid #dee2e6;
        padding: 10px;
    }

    .calendar th {
        background-color: #f8f9fa;
        text-align: center;
        font-weight: bold;
    }

    .date-number {
        font-weight: bold;
        margin-bottom: 10px;
        color: #007bff;
    }

    .session {
        background-color: #f1f1f1;
        border-radius: 4px;
        padding: 5px;
        margin-bottom: 5px;
        font-size: 0.9em;
        border-left: 3px solid #007bff;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        margin-top: 10px;
    }

    .session a {
        margin-right: 5px;
        font-size: 0.85em;
    }

    .session strong {
        display: block;
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.9em;
    }

    .button-flex {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h2>Quản Lý Thời Khóa Biểu</h2>
    <!-- Return Button -->
    <a href="{{ route('pdt.departments.index') }}" class="btn btn-secondary mb-3">Trở về</a>

    <!-- Navigation Tháng -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('pdt.schedules.index', ['month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year, 'department_id' => $selectedDepartment, 'professor_id' => $selectedProfessor]) }}" class="btn btn-secondary">&laquo; Tháng Trước</a>
        <h4>{{ $currentDate->format('F Y') }}</h4>
        <a href="{{ route('pdt.schedules.index', ['month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year, 'department_id' => $selectedDepartment, 'professor_id' => $selectedProfessor]) }}" class="btn btn-secondary">Tháng Sau &raquo;</a>
    </div>
    <!-- Other content -->
    @yield('content')
    <div class="container mt-4 button-flex">
        <form action="{{ route('pdt.schedules.generate') }}" method="POST" id="insertNewScheduleForm">
            @csrf
            <input type="hidden" name="option" value="insert_new">
            <button type="submit" class="btn btn-success" {{ $canInsertNewSchedule ? '' : 'enable' }}>Chèn Lịch Mới</button>
        </form>
        <form action="{{ route('pdt.schedules.deleteAll') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tất cả thời khóa biểu không?')">
            @csrf
            <button type="submit" class="btn btn-danger">Xóa Tất Cả Thời Khóa Biểu</button>
        </form>
    </div>
    <!-- Filter Form
    <div class="mb-3">
        <form action="{{ route('pdt.schedules.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="DepartmentID" class="form-label">Lọc theo Khoa</label>
                <select id="DepartmentID" name="DepartmentID" class="form-select">
                    <option value="">-- Tất cả Khoa --</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->DepartmentID }}" {{ (isset($selectedDepartment) && $selectedDepartment == $department->DepartmentID) ? 'selected' : '' }}>
                        {{ $department->DepartmentName }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="ProfessorID" class="form-label">Lọc theo Giảng viên</label>
                <select id="ProfessorID" name="ProfessorID" class="form-select">
                    <option value="">-- Tất cả Giảng viên --</option>
                    @foreach($professors as $professor)
                    <option value="{{ $professor->ProfessorID }}" {{ (isset($selectedProfessor) && $selectedProfessor == $professor->ProfessorID) ? 'selected' : '' }}>
                        {{ $professor->ProfessorName }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Lọc</button>
                <a href="{{ route('pdt.schedules.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

    </div> -->

    <!-- Các nút tạo mới -->
    <div class="mb-3">
        @if(empty($calendar))
        <form action="{{ route('pdt.schedules.generate') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success mt-3">Tạo Thời Khóa Biểu Tự Động</button>
        </form>
        @else
        <p>Thời khóa biểu đã được tạo.</p>
        <a href="{{ route('pdt.schedules.create') }}" class="btn btn-primary">Thêm Thời Khóa Biểu Mới</a>
        @endif
    </div>

    <!-- Hiển thị thông báo -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Hiển thị lịch học -->
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
                    <td>
                    </td>
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                        $date=$currentDate->copy()->day($day)->format('Y-m-d');
                        $dayOfWeek = $currentDate->copy()->day($day)->dayOfWeek; // 0 (Sunday) - 6 (Saturday)
                        @endphp

                        <td>
                            <div class="date-number">{{ $day }}</div>
                            @if(isset($calendar[$date]))
                            @foreach ($calendar[$date] as $session)
                            <div class="session" data-bs-toggle="tooltip" data-bs-html="true" title="<strong>Ca {{ $session['session'] }}:</strong> {{ $session['session_time'] }}<br><strong>Phòng:</strong> {{ $session['room'] }}<br><strong>Môn:</strong> {{ $session['subject'] }}<br><strong>GV:</strong> {{ $session['professor'] }}">
                                <strong>Ca {{ $session['session'] }}:</strong> {{ $session['session_time'] }}<br>
                                <strong>Phòng:</strong> {{ $session['room'] }}<br>
                                <strong>Môn:</strong> {{ $session['subject'] }}<br>
                                <strong>GV:</strong> {{ $session['professor'] }}<br>
                                <a href="{{ $session['edit_url'] }}" class="btn btn-sm btn-primary mt-1">Sửa</a>
                                <form action="{{ $session['delete_url'] }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thời khóa biểu này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mt-1">Xóa</button>
                                </form>


                            </div>
                            @endforeach
                            @else
                            <div class="text-muted">Không có lịch</div>
                            @endif

                            <!-- Kết thúc hàng nếu là cuối tuần -->
                            @if ($dayOfWeek == 6 && $day != $daysInMonth)
            </tr>
            <tr>
                @endif
                </td>
                @endfor

                <!-- Đổ đầy các ô trống sau khi kết thúc tháng -->
                @for ($blank = $currentDate->copy()->day($daysInMonth)->dayOfWeek + 1; $blank <= 6; $blank++)
                    <td>
                    </td>
                    @endfor
            </tr>
        </tbody>
    </table>
    @endif
</div>
@endsection

@section('scripts')
<!-- Include scripts -->
@yield('scripts')
<!-- Thêm Bootstrap JS nếu chưa có -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Khởi tạo Tooltip cho các phần tử có data-bs-toggle="tooltip"
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection