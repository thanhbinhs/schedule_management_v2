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
        <h2>Education Programs</h2>
        <a href="{{ route('department.education_programs.create') }}" class="btn btn-primary">Add Education Program</a>
    </div>

    @if($educationPrograms->isEmpty())
        <p>No education programs found.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Education Program ID</th>
                    <th>Name</th>
                    <th>Major</th>
                    <th>Subjects</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($educationPrograms as $program)
                    <tr>
                        <td>{{ $program->EducationProgramID }}</td>
                        <td>{{ $program->EducationProgramName }}</td>
                        <td>{{ $program->major->MajorName }}</td>
                        <td>
                            @php
                                $subjects = json_decode($program->SubjectList, true);
                            @endphp
                            @if(!empty($subjects))
                                <ul>
                                    @foreach($subjects as $subjectId)
                                        @php
                                            $subject = App\Models\Subject::find($subjectId);
                                        @endphp
                                        @if($subject)
                                            <li>{{ $subject->SubjectName }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p>No subjects assigned.</p>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('department.education_programs.edit', $program->EducationProgramID) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('department.education_programs.destroy', $program->EducationProgramID) }}" method="POST" class="d-inline" 
                                onsubmit="return confirm('Are you sure you want to delete this education program?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
