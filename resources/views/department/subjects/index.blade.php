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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Thông tin môn học</h2>
        <a href="{{ route('department.subjects.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>

    @if($subjects->isEmpty())
        <p>Không tìm thấy môn học</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Môn học</th>
                    <th>Khoa trực thuộc</th>
                    <th>Số tín chỉ</th>
                    <th>Số tiết</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                    <tr>
                        <td>{{ $subject->SubjectID }}</td>
                        <td>{{ $subject->SubjectName }}</td>
                        <td>{{ $subject->department->DepartmentName }}</td>
                        <td>{{ $subject->SubjectCredit }}</td>
                        <td>{{ $subject->SubjectLessons }}</td>
                        <td>
                            <a href="{{ route('department.subjects.edit', $subject->SubjectID) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('department.subjects.destroy', $subject->SubjectID) }}" method="POST" class="d-inline" 
                                onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
@endsection
