<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDT Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS tùy chỉnh */
        body {
            font-family: Roboto, sans-serif;
            background-color: #f9f9f9;
            color: #233871;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #233871;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-size: 18px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .action-buttons a, .action-buttons form {
            display: inline-block;
            margin-right: 10px;
        }

        .action-buttons button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-buttons a.edit-button {
            background-color: #007bff;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
        }

        .action-buttons a.edit-button:hover, .action-buttons button:hover {
            opacity: 0.8;
        }

        .create-button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            margin-bottom: 20px;
            display: inline-block;
        }

        .create-button:hover {
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            table tr {
                margin-bottom: 15px;
            }

            table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
        }

        /* Form styling */
        form .form-group {
            margin-bottom: 15px;
        }

        form label {
            font-weight: bold;
        }

        form input[type="text"], form input[type="number"], form select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button[type="submit"] {
            background-color: #233871;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button[type="submit"]:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <header>
        <h1>PDT Dashboard</h1>
        <nav>
            <a href="{{ route('pdt.departments.index') }}">Quản Lý Khoa</a>
            <a href="{{ route('pdt.rooms.index') }}">Quản Lý Phòng</a>
            <a href="{{ route('pdt.schedules.index') }}">Quản Lý Thời Khóa Biểu</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng Xuất</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </header>

    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
