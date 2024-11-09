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
    <h2>Add Major</h2>

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

    <form action="{{ route('department.majors.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="MajorName" class="form-label">Major Name</label>
            <input type="text" name="MajorName" class="form-control" id="MajorName" value="{{ old('MajorName') }}" required>
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

        <button type="submit" class="btn btn-success">Add Major</button>
        <a href="{{ route('department.majors.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
