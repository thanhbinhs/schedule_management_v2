<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản lý thời khóa biểu')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('head') <!-- Thêm section head để chèn CSS từ các view con -->
</head>
<body>
    <header>
        <!-- Nội dung header -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('pdt.schedules.index') }}">Trang Quản Lý Thời Khóa Biểu</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-4">
        @yield('content') <!-- Thêm nội dung chính từ các view con -->
    </main>

    <footer class="text-center py-4">
        <!-- Nội dung footer -->
        <p>Bản quyền © 2024 - Trang Quản Lý Thời Khóa Biểu</p>
    </footer>

    <!-- Thêm Bootstrap JS nếu chưa có -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts') <!-- Thêm section scripts để chèn JS từ các view con -->
</body>
</html>
