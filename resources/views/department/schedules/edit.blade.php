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
    <h2>Chỉnh Sửa Thời Khóa Biểu</h2>

    <!-- Form chỉnh sửa thời khóa biểu -->
    <form action="{{ route('department.schedules.update', [ $schedule->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="RoomID" class="form-label">Phòng</label>
            <select id="RoomID" name="RoomID" class="form-select" required>
                <option value="">-- Chọn phòng --</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->RoomID }}" {{ $room->RoomID == $schedule->RoomID ? 'selected' : '' }}>{{ $room->RoomID }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Ngày</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ $schedule->date }}" required>
        </div>

        <div class="mb-3">
            <label for="session_number" class="form-label">Ca Học</label>
            <select id="session_number" name="session_number" class="form-select" required>
                <option value="">-- Chọn ca học --</option>
                @foreach($sessions as $number => $time)
                    <option value="{{ $number }}" {{ $number == $schedule->session_number ? 'selected' : '' }}>Ca {{ $number }} ({{ $time }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="subject_id" class="form-label">Môn Học</label>
            <select id="subject_id" name="subject_id" class="form-select">
                <option value="">-- Chọn môn học --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->SubjectID }}" {{ $subject->SubjectID == $schedule->subject_id ? 'selected' : '' }}>{{ $subject->SubjectName }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="professor_id" class="form-label">Giảng Viên</label>
            <select id="professor_id" name="professor_id" class="form-select">
                <option value="">-- Chọn giảng viên --</option>
                @foreach($professors as $professor)
                    <option value="{{ $professor->ProfessorID }}" {{ $professor->ProfessorID == $schedule->professor_id ? 'selected' : '' }}>{{ $professor->ProfessorName }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật Thời Khóa Biểu</button>
        <a href="{{ route('department.schedules.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>

@endsection
