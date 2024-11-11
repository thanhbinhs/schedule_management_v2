@extends('layouts.pdt')
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
<h2>Sửa Thông Tin Khoa</h2>

@if ($errors->any())
<div class="error-message">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pdt.departments.update', $department->DepartmentID) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <label for="DepartmentName">Tên Khoa:</label>
        <input type="text" id="DepartmentName" name="DepartmentName" class="input-field" value="{{ old('DepartmentName', $department->DepartmentName) }}" required />
    </div>

    <div>
        <label for="DepartmentAddress">Địa Chỉ:</label>
        <input type="text" id="DepartmentAddress" name="DepartmentAddress" class="input-field" value="{{ old('DepartmentAddress', $department->DepartmentAddress) }}" required />
    </div>

    <!-- Leader Department được chọn từ danh sách Professor -->

    <div>
    <label for="LeaderDepartmentID">Trưởng khoa:</label>
    <select id="LeaderDepartmentID" name="LeaderDepartmentID" class="input-field">
        <option value="">-- Chọn Trưởng khoa --</option>
        @foreach ($professors as $professor)
            <option value="{{ $professor->ProfessorID }}" 
                {{ old('LeaderDepartmentID', $department->LeaderDepartmentID) == $professor->ProfessorID ? 'selected' : '' }}>
                {{ $professor->ProfessorName }}
            </option>
        @endforeach
    </select>
    <small>Giá trị này là tùy chọn và phải là một Giảng viên đã tồn tại.</small>
</div>

    <button type="submit" class="create-button">Cập Nhật Khoa</button>
</form>
@endsection