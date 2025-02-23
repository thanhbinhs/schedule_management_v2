<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Phòng đào tạo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Biến màu để dễ dàng tùy chỉnh */
        :root {
            --primary-color: #007bff; /* Màu chủ đạo, xanh dương đậm */
            --secondary-color: #6c757d; /* Màu phụ, xám */
            --accent-color: #ffc107; /* Màu nhấn, vàng */
            --background-gradient-start: #1e3c72; /* Màu bắt đầu gradient nền */
            --background-gradient-end: #2a5298; /* Màu kết thúc gradient nền */
            --card-background: #ffffff; /* Màu nền thẻ/container */
            --text-color-dark: #343a40; /* Màu chữ tối */
            --text-color-light: #f8f9fa; /* Màu chữ sáng */
            --shadow-color: rgba(0, 0, 0, 0.15); /* Màu bóng đổ */
            --border-color: #dee2e6; /* Màu đường viền */
        }

        body {
            background: linear-gradient(135deg, var(--background-gradient-start), var(--background-gradient-end));
            color: var(--text-color-dark);
            font-family: 'Nunito', sans-serif;
            font-weight: 400;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .navbar {
            background: var(--card-background); /* Sử dụng nền trắng */
            box-shadow: 0 5px 15px var(--shadow-color); /* Thêm bóng đổ */
            border-bottom: 1px solid var(--border-color); /* Thêm đường viền dưới */
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif; /* Font tiêu đề sang trọng */
            font-weight: 700;
            color: var(--primary-color); /* Màu tiêu đề là màu chủ đạo */
        }

        .navbar-toggler {
            border-color: var(--primary-color);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(0, 123, 255, 0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e"); /* Màu icon toggler */
        }


        .navbar-nav .nav-link {
            color: var(--text-color-dark); /* Màu chữ link */
            font-weight: 500;
            padding: 0.7rem 1rem;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary-color); /* Màu chữ link khi hover hoặc active */
        }

        .btn-outline-light {
            color: var(--text-color-light); /* Text color of logout button */
            border-color: var(--text-color-light); /* Border color of logout button */
        }

        .btn-outline-light:hover {
            background-color: var(--text-color-light); /* Background color on hover */
            color: var(--text-color-dark); /* Text color on hover */
            border-color: var(--text-color-light); /* Border color on hover, keep same for consistency */
        }


        .alert {
            border-radius: 8px;
            margin-bottom: 1.5rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 3px 8px var(--shadow-color);
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #f5c6cb;
        }

        .container {
            background: var(--card-background);
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 15px 40px var(--shadow-color);
            margin-top: 3rem;
            margin-bottom: 3rem;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('pdt.departments.index') }}"><i class="fas fa-shield-alt me-2"></i> Xin chào Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pdt.departments.index') }}"><i class="fas fa-building me-1"></i> Quản lý Khoa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pdt.rooms.index') }}"><i class="fas fa-door-open me-1"></i> Quản lý phòng học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pdt.schedules.index') }}"><i class="far fa-calendar-alt me-1"></i> Thời khóa biểu</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <a class="btn btn-outline-primary" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
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
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
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