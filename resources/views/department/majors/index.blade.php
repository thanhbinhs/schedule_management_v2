@extends('department.layout')
@section('head')
<!-- Thêm Bootstrap CSS nếu chưa có -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Thông tin Ngành Học</title>
<style>
    /* Nếu bạn vẫn "yêu" các style của lịch, giữ nguyên để tái sử dụng ở nơi khác */
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
    
    /* Custom style cho trang ngành học */
    body {
        background-color: #f4f4f4;
        font-family: Arial, sans-serif;
    }
    
    .container {
        background-color: #fff;
        border-radius: 8px;
        padding: 30px;
        margin-top: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    h2 {
        color: #233871;
    }
    
    .table thead th {
        background-color: #233871;
        color: #fff;
    }
    
    .table tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Thông tin ngành học</h2>
        <a href="{{ route('department.majors.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>

    @if($majors->isEmpty())
        <div class="alert alert-info">
            Không tìm thấy ngành học
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ngành học</th>
                        <th>Khoa</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($majors as $major)
                        <tr>
                            <td>{{ $major->MajorID }}</td>
                            <td>{{ $major->MajorName }}</td>
                            <td>{{ $major->department->DepartmentName }}</td>
                            <td>
                                <a href="{{ route('department.majors.edit', $major->MajorID) }}" class="btn btn-sm btn-warning">Sửa</a>
                                <form action="{{ route('department.majors.destroy', $major->MajorID) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this major?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
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
