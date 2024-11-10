@extends('department.layout')
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
    <h2>Sửa thông tin giảng viên</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Đã xảy ra lỗi!</strong> Thông tin bạn nhập vào có vấn đề<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('department.professors.update', $professor->ProfessorID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="ProfessorName" class="form-label">Họ và tên</label>
            <input type="text" name="ProfessorName" class="form-control" id="ProfessorName" value="{{ old('ProfessorName', $professor->ProfessorName) }}" required>
        </div>

        <div class="mb-3">
            <label for="ProfessorGmail" class="form-label">Gmail</label>
            <input type="email" name="ProfessorGmail" class="form-control" id="ProfessorGmail" value="{{ old('ProfessorGmail', $professor->ProfessorGmail) }}" required>
        </div>

        <div class="mb-3">
            <label for="ProfessorPhone" class="form-label">Số điện thoại</label>
            <input type="text" name="ProfessorPhone" class="form-control" id="ProfessorPhone" value="{{ old('ProfessorPhone', $professor->ProfessorPhone) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('department.professors.index') }}" class="btn btn-secondary">Trở về</a>
    </form>
@endsection
