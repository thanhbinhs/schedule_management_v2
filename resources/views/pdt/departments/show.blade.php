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
    <h2>Thông Tin Khoa</h2>

    <p><strong>Mã Khoa:</strong> {{ $department->DepartmentID }}</p>
    <p><strong>Tên Khoa:</strong> {{ $department->DepartmentName }}</p>
    <p><strong>Địa Chỉ:</strong> {{ $department->DepartmentAddress }}</p>
    <p><strong>Trưởng khoa:</strong>
        @if($department->leader)
            {{ $department->leader->ProfessorName }}
        @else
            Không có
        @endif
    </p>
    <p><strong>Số Lượng Giảng viên:</strong> {{ $department->majors->count() }}</p>

    <a href="{{ route('pdt.departments.edit', $department->DepartmentID) }}" class="edit-button">Sửa</a>
    <a href="{{ route('pdt.departments.index') }}" class="create-button">Quay Lại Danh Sách</a>
@endsection
