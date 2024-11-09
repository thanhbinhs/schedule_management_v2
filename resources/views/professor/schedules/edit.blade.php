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

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('professor.schedules.update', $schedule) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="date" class="form-label">Ngày</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $schedule->date) }}" required>
        </div>

        <div class="mb-3">
            <label for="session_number" class="form-label">Ca Học</label>
            <select name="session_number" id="session_number" class="form-select" required>
                <option value="1" {{ old('session_number', $schedule->session_number) == 1 ? 'selected' : '' }}>Ca 1 (6h45 - 9h25)</option>
                <option value="2" {{ old('session_number', $schedule->session_number) == 2 ? 'selected' : '' }}>Ca 2 (9h30 - 12h10)</option>
                <option value="3" {{ old('session_number', $schedule->session_number) == 3 ? 'selected' : '' }}>Ca 3 (1h00 - 3h40)</option>
                <option value="4" {{ old('session_number', $schedule->session_number) == 4 ? 'selected' : '' }}>Ca 4 (3h45 - 6h25)</option>
            </select>
        </div>

        <div class="mb-3">
        <label for="RoomID" class="form-label">Phòng</label>
        <select name="RoomID" id="RoomID" class="form-select" required>
            @foreach ($rooms as $room)
                <option value="{{ $room->RoomID }}" {{ old('RoomID', $schedule->RoomID) == $room->RoomID ? 'selected' : '' }}>
                    {{ $room->RoomID }}
                </option>
            @endforeach
        </select>
    </div>

        <div class="mb-3">
            <label for="subject_id" class="form-label">Môn Học</label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                @foreach ($subjects as $subject)
                <option value="{{ $subject->SubjectID }}" {{ old('subject_id', $schedule->subject_id) == $subject->SubjectID ? 'selected' : '' }}>{{ $subject->SubjectName }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <!-- button cancel -->
        <a href="{{ route('professor.schedules.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@endsection