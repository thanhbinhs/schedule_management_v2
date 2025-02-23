@extends('layouts.app')

@section('head')
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom styles for a grand and modern interface -->
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
            font-family: 'Nunito', sans-serif; /* Phông chữ hiện đại */
            font-weight: 400;
            line-height: 1.6;
            overflow-x: hidden; /* Ngăn thanh cuộn ngang */
            padding-bottom: 50px; /* Khoảng cách dưới để không bị sát chân trang */
        }

        .container {
            background: var(--card-background);
            border-radius: 15px; /* Bo tròn mạnh hơn */
            padding: 2.5rem; /* Tăng padding */
            box-shadow: 0 15px 40px var(--shadow-color); /* Bóng đổ sâu hơn */
            margin-top: 3rem; /* Tăng margin top */
            margin-bottom: 3rem;
        }

        h2 {
            font-family: 'Playfair Display', serif; /* Phông chữ tiêu đề sang trọng */
            font-weight: 700;
            color: var(--text-color-dark);
            margin-bottom: 2rem; /* Tăng margin bottom */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.05); /* Bóng đổ chữ tinh tế */
            letter-spacing: 0.5px; /* Khoảng cách chữ rộng hơn một chút */
            text-transform: uppercase; /* Chữ in hoa */
            border-bottom: 3px solid var(--accent-color); /* Gạch chân tiêu đề bằng màu nhấn */
            padding-bottom: 0.75rem;
            display: inline-block; /* Để gạch chân vừa với độ dài tiêu đề */
        }

        /* Form styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-color-dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
            outline: 0;
        }

        .btn {
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
        }

        .btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25) !important;
            outline: 0 !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--text-color-light);
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: var(--text-color-light);
        }

        .btn-secondary:hover {
            background-color: #545b62;
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 1.5rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 3px 8px var(--shadow-color);
            border: none;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #f5c6cb;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center">Chỉnh Sửa Thời Khóa Biểu</h2>

        <!-- Form chỉnh sửa thời khóa biểu -->
        <form action="{{ route('department.schedules.update', [ $schedule->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="RoomID" class="form-label"><i class="fas fa-door-open me-1"></i> Phòng</label>
                <select id="RoomID" name="RoomID" class="form-control" required>
                    <option value="" disabled>-- Chọn phòng --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->RoomID }}" {{ $room->RoomID == $schedule->RoomID ? 'selected' : '' }}>{{ $room->RoomID }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="date" class="form-label"><i class="far fa-calendar-alt me-1"></i> Ngày</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ $schedule->date }}" required>
            </div>

            <div class="form-group">
                <label for="session_number" class="form-label"><i class="fas fa-list-ol me-1"></i> Ca Học</label>
                <select id="session_number" name="session_number" class="form-control" required>
                    <option value="" disabled>-- Chọn ca học --</option>
                    @foreach($sessions as $number => $time)
                        <option value="{{ $number }}" {{ $number == $schedule->session_number ? 'selected' : '' }}>Ca {{ $number }} ({{ $time }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="subject_id" class="form-label"><i class="fas fa-book-open me-1"></i> Môn Học</label>
                <select id="subject_id" name="subject_id" class="form-control">
                    <option value="">-- Chọn môn học --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->SubjectID }}" {{ $subject->SubjectID == $schedule->subject_id ? 'selected' : '' }}>{{ $subject->SubjectName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="professor_id" class="form-label"><i class="fas fa-chalkboard-teacher me-1"></i> Giảng Viên</label>
                <select id="professor_id" name="professor_id" class="form-control">
                    <option value="">-- Chọn giảng viên --</option>
                    @foreach($professors as $professor)
                        <option value="{{ $professor->ProfessorID }}" {{ $professor->ProfessorID == $schedule->professor_id ? 'selected' : '' }}>{{ $professor->ProfessorName }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Cập Nhật</button>
            <a href="{{ route('department.schedules.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Hủy</a>
        </form>
    </div>

@endsection