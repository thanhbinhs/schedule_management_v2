<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khoa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('department.professors.index') }}">Xin chào {{$departmentName}}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.professors.index') }}">Giảng viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.majors.index') }}">Ngành học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.education_programs.index') }}">Chương trình đào tạo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.subjects.index') }}">Môn học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('department.schedules.index') }}">Thời khóa biểu</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <a class="btn btn-outline-light" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Container -->
    <div class="container">
        <!-- Display Success and Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Content -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
