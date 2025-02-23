@extends('department.layout')
@section('head')
<!-- Thêm Bootstrap CSS nếu chưa có -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Thông tin Giảng viên</title>
<style>
    /* Các style của lịch nếu cần sử dụng ở nơi khác */
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

    /* Custom highlight cho trưởng khoa */
    .highlight-leader {
        background-color: #d1ecf1;
    }

    /* Cải tiến cho bảng */
    .table thead th {
        background-color: #233871;
        color: #fff;
        font-weight: 600;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }
    
    .btn-action {
        margin-right: 5px;
    }
    
    /* Tùy chọn: bo góc cho bảng */
    .table {
        border-radius: 0.5rem;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Thông tin giảng viên</h2>
        <a href="{{ route('department.professors.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>

    @if($professors->isEmpty())
        <div class="alert alert-info">Không tìm thấy giảng viên</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Gmail</th>
                        <th>Số điện thoại</th>
                        <th>Chức danh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($professors as $professor)
                        <tr class="{{ $professor->isLeaderDepartment ? 'highlight-leader' : '' }}">
                            <td>{{ $professor->ProfessorID }}</td>
                            <td>{{ $professor->ProfessorName }}</td>
                            <td>{{ $professor->ProfessorGmail }}</td>
                            <td>{{ $professor->ProfessorPhone }}</td>
                            <td>
                                @if($professor->isLeaderDepartment)
                                    Trưởng khoa
                                @else
                                    Giảng viên
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('department.professors.edit', $professor->ProfessorID) }}" class="btn btn-sm btn-warning btn-action">Sửa</a>
                                <form action="{{ route('department.professors.destroy', $professor->ProfessorID) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa giảng viên này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-action">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
