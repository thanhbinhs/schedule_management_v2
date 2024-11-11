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
    <h2>Danh Sách Khoa</h2>
    <a href="{{ route('pdt.departments.create') }}" class="create-button">Thêm Khoa Mới</a>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if($departments->isEmpty())
        <p>Không có khoa nào được thêm vào.</p>
    @else
        <table>
            <thead>
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
                        <td data-label="Leader">
                            @if($department->leader)
                                {{ $department->leader->ProfessorName }}
                            @else
                                Không có
                            @endif
                        </td>
                        <td data-label="Hành Động" class="action-buttons">
                            <a href="{{ route('pdt.departments.edit', $department->DepartmentID) }}" class="edit-button">Sửa</a>

                            <form action="{{ route('pdt.departments.destroy', $department->DepartmentID) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khoa này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
