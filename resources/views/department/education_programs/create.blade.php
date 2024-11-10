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
    <h2>Thêm mới chương trình đào tạo</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
        <strong>Đã xảy ra lỗi!</strong> Thông tin bạn nhập vào có vấn đề<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('department.education_programs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="EducationProgramName" class="form-label">Chương trình đào tạo</label>
            <input type="text" name="EducationProgramName" class="form-control" id="EducationProgramName" value="{{ old('EducationProgramName') }}" required>
        </div>

        <div class="mb-3">
            <label for="MajorID" class="form-label">Ngành học</label>
            <select name="MajorID" id="MajorID" class="form-select" required>
                <option value="">Chọn ngành học</option>
                @foreach($majors as $major)
                    <option value="{{ $major->MajorID }}" {{ old('MajorID') == $major->MajorID ? 'selected' : '' }}>
                        {{ $major->MajorName }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="SubjectList" class="form-label">Môn học</label>
            <select name="SubjectList[]" id="SubjectList" class="form-select" multiple required>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->SubjectID }}" {{ (collect(old('SubjectList'))->contains($subject->SubjectID)) ? 'selected' : '' }}>
                        {{ $subject->SubjectName }}
                    </option>
                @endforeach
            </select>
            <small>Chọn nhiều môn học bằng cách giữ phím Ctrl (windows) / Command (Mac).</small>
        </div>

        <button type="submit" class="btn btn-success">Thêm mới</button>
        <a href="{{ route('department.education_programs.index') }}" class="btn btn-secondary">Trở về</a>
    </form>
@endsection
