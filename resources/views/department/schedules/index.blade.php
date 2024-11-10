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
</style>

@endsection

@section('content')

<div class="container mt-4">
    <h2>Quản Lý Thời Khóa Biểu Khoa</h2>
<!-- Quay ve trang truoc-->
    <a href="{{ route('department.professors.index') }}" class="btn btn-primary">Trở về</a>
   

    <!-- Navigation tháng -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <a href="{{ route('department.schedules.index', ['month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year]) }}" class="btn btn-secondary">&laquo; Tháng Trước</a>
        <h4>{{ $currentDate->format('F Y') }}</h4>
        <a href="{{ route('department.schedules.index', ['month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year]) }}" class="btn btn-secondary">Tháng Sau &raquo;</a>
    </div>

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
                                <div class="session">
                                    <strong>Ca {{ $session['session'] }}:</strong> Phòng {{ $session['room'] }}<br>
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
    @else
    <p>Không có lịch học nào.</p>
    @endif
    @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
</div>
@endsection
