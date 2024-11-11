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
    <div class="container">
        <h2>Thêm Thời Khóa Biểu Mới</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Lỗi!</strong> Hãy kiểm tra lại dữ liệu nhập vào.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pdt.schedules.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="RoomID">Room ID</label>
                <select name="RoomID" id="RoomID" class="form-control" required>
                    <option value="" disabled selected>Chọn Phòng Học</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->RoomID }}" {{ old('RoomID') == $room->RoomID ? 'selected' : '' }}>
                            {{ $room->RoomID }} - {{ $room->building }} (Tầng {{ $room->floor }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="date">Ngày</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}" required>
            </div>

            <div class="form-group">
                <label for="session_number">Ca Học</label>
                <select name="session_number" id="session_number" class="form-control" required>
                    <option value="" disabled selected>Chọn Ca Học</option>
                    @foreach ($sessions as $number => $time)
                        <option value="{{ $number }}" {{ old('session_number') == $number ? 'selected' : '' }}>
                            {{ $number }} ({{ $time }})
                        </option>
                    @endforeach
                </select>
            </div>

            <hr>

            <h4>Môn Học và Giáo Viên</h4>
            <div class="form-group">
                <label for="subject_id">Môn Học</label>
                <select name="subject_id" id="subject_id" class="form-control">
                    <option value="" selected>Không có môn học</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->SubjectID }}" {{ old('subject_id') == $subject->SubjectID ? 'selected' : '' }}>
                            {{ $subject->SubjectName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="professor_id">Giáo Viên</label>
                <select name="professor_id" id="professor_id" class="form-control">
                    <option value="" selected>Không có giáo viên</option>
                    @foreach ($professors as $professor)
                        <option value="{{ $professor->ProfessorID }}" {{ old('professor_id') == $professor->ProfessorID ? 'selected' : '' }}>
                            {{ $professor->ProfessorName }} ({{ $professor->department->DepartmentName ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Thêm Thời Khóa Biểu</button>
            <a href="{{ route('pdt.schedules.index') }}" class="btn btn-secondary">Quay Lại</a>
        </form>
    </div>
@endsection
