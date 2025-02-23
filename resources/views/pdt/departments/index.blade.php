@extends('layouts.pdt')
@section('head')
<!-- Thêm Bootstrap CSS nếu chưa có -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Một vài điều chỉnh nhỏ để bảng trông thật "đỉnh": */
    .create-button {
        margin-bottom: 15px;
    }
    .success-message {
        margin-top: 15px;
    }
    .table-custom th,
    .table-custom td {
        vertical-align: middle;
    }
    .action-buttons a,
    .action-buttons form {
        display: inline-block;
        margin-right: 5px;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Danh Sách Khoa</h2>
    <div class="d-flex justify-content-end">
        <a href="{{ route('pdt.departments.create') }}" class="btn btn-primary create-button">Thêm Khoa Mới</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success success-message">
            {{ session('success') }}
        </div>
    @endif

    @if($departments->isEmpty())
        <p class="text-muted text-center">Không có khoa nào được thêm vào.</p>
    @else
        <table class="table table-bordered table-hover table-custom">
            <thead class="table-light">
                <tr>
                    <th>Mã Khoa</th>
                    <th>Tên Khoa</th>
                    <th>Địa Chỉ</th>
                    <th>Trưởng khoa</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td data-label="Mã Khoa">{{ $department->DepartmentID }}</td>
                        <td data-label="Tên Khoa">{{ $department->DepartmentName }}</td>
                        <td data-label="Địa Chỉ">{{ $department->DepartmentAddress }}</td>
                        <td data-label="Trưởng khoa">
                            @if($department->leader)
                                {{ $department->leader->ProfessorName }}
                            @else
                                Không có
                            @endif
                        </td>
                        <td data-label="Hành Động" class="action-buttons">
                            <a href="{{ route('pdt.departments.edit', $department->DepartmentID) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('pdt.departments.destroy', $department->DepartmentID) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khoa này?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
