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
    <h2>Add Subject</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('department.subjects.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="SubjectName" class="form-label">Subject Name</label>
            <input type="text" name="SubjectName" class="form-control" id="SubjectName" value="{{ old('SubjectName') }}" required>
        </div>

        <div class="mb-3">
            <label for="DepartmentID" class="form-label">Department</label>
            <select name="DepartmentID" id="DepartmentID" class="form-select" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->DepartmentID }}" {{ old('DepartmentID') == $department->DepartmentID ? 'selected' : '' }}>
                        {{ $department->DepartmentName }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="SubjectCredit" class="form-label">Credit</label>
            <input type="number" name="SubjectCredit" class="form-control" id="SubjectCredit" value="{{ old('SubjectCredit') }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="SubjectLessons" class="form-label">Lessons</label>
            <input type="number" name="SubjectLessons" class="form-control" id="SubjectLessons" value="{{ old('SubjectLessons') }}" min="0" required>
        </div>

        <button type="submit" class="btn btn-success">Add Subject</button>
        <a href="{{ route('department.subjects.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
