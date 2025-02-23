@extends('layouts.app')

@section('head')
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome cho icons -->
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

        /* Calendar styling */
        .calendar {
            border-collapse: collapse;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px var(--shadow-color);
            border: 1px solid var(--border-color);
        }
        .calendar th, .calendar td {
            border: 1px solid var(--border-color);
            width: 14.28%;
            height: 160px;
            vertical-align: top;
            position: relative;
            padding: 12px;
            background: var(--card-background);
            transition: background-color 0.3s ease;
        }
        .calendar th {
            background: var(--primary-color);
            color: var(--text-color-light);
            text-align: center;
            font-weight: 600;
            padding: 18px 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }
        .calendar td:hover {
            background-color: #f0f0f0;
        }
        .date-number {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            opacity: 0.8;
        }
        .session {
            margin-top: 35px;
            padding: 15px;
            background: rgba(var(--primary-color-rgb), 0.07);
            border-left: 7px solid var(--primary-color);
            border-radius: 7px;
            box-shadow: 0 3px 10px var(--shadow-color);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            cursor: pointer;
        }
        .session:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            background: rgba(var(--primary-color-rgb), 0.12);
        }
        .session h6,
        .session p {
            margin: 0;
            line-height: 1.5;
            margin-bottom: 5px;
            color: var(--text-color-dark);
        }
        .session h6 {
            font-weight: 600;
            font-size: 1.05rem;
        }
        .session p {
            font-size: 0.95rem;
            color: #555;
        }
        .session-actions {
            margin-top: 10px;
            text-align: right;
        }
        .session-actions a,
        .session-actions button {
            margin-left: 5px;
            font-size: 0.9rem;
            padding: 8px 15px;
        }
        .text-muted {
            color: #6c757d;
            font-size: 0.95rem;
            font-style: italic;
        }
        .btn-secondary {
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
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

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #c3e6cb;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #85640a;
            border-left: 5px solid #ffeeba;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #f5c6cb;
        }

        /* Tooltip */
        .tooltip-inner {
            background-color: var(--text-color-dark);
            color: var(--text-color-light);
            border-radius: 8px;
            padding: 15px;
            font-size: 0.95rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .bs-tooltip-end .tooltip-arrow::before, .bs-tooltip-auto[data-popper-placement^=right] .tooltip-arrow::before {
            border-right-color: var(--text-color-dark);
        }

        .bs-tooltip-start .tooltip-arrow::before, .bs-tooltip-auto[data-popper-placement^=left] .tooltip-arrow::before {
            border-left-color: var(--text-color-dark);
        }

        .bs-tooltip-top .tooltip-arrow::before, .bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before {
            border-top-color: var(--text-color-dark);
        }

        .bs-tooltip-bottom .tooltip-arrow::before, .bs-tooltip-auto[data-popper-placement^=bottom] .tooltip-arrow::before {
            border-bottom-color: var(--text-color-dark);
        }

    </style>
@endsection

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-user-graduate me-2"></i> Xin chào, {{ $professor->ProfessorName }}</h2>
    <!-- Navigation Tháng -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('professor.schedules.index', ['month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year]) }}" class="btn btn-secondary"><i class="fas fa-chevron-left me-2"></i> Tháng Trước</a>
        <h4 class="m-0">{{ $currentDate->format('F Y') }}</h4>
        <a href="{{ route('professor.schedules.index', ['month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year]) }}" class="btn btn-secondary">Tháng Sau <i class="fas fa-chevron-right ms-2"></i></a>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i> {{ session('error') }}</div>
    @endif

    <!-- Calendar Display -->
    @if(!empty($calendar))
        <table class="table calendar">
            <thead>
                <tr>
                    <th class="text-center">Chủ Nhật</th>
                    <th class="text-center">Thứ 2</th>
                    <th class="text-center">Thứ 3</th>
                    <th class="text-center">Thứ 4</th>
                    <th class="text-center">Thứ 5</th>
                    <th class="text-center">Thứ 6</th>
                    <th class="text-center">Thứ 7</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @for ($blank = 0; $blank < $startDayOfWeek; $blank++)
                        <td></td>
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $date = $currentDate->copy()->day($day)->format('Y-m-d');
                            $dayOfWeek = $currentDate->copy()->day($day)->dayOfWeek;
                        @endphp

                        <td>
                            <div class="date-number">{{ $day }}</div>
                            @if(isset($calendar[$date]))
                                @foreach ($calendar[$date] as $session)
                                    <div class="session" data-bs-toggle="tooltip" data-bs-html="true" title="
                                        <strong><i class='fas fa-clock me-1'></i> Ca {{ $session['session'] }}:</strong> {{ $session['session_time'] }}<br>
                                        <strong><i class='fas fa-door-open me-1'></i> Phòng:</strong> {{ $session['room'] }}<br>
                                        <strong><i class='fas fa-book-open me-1'></i> Môn:</strong> {{ $session['subject'] }}<br>
                                    ">
                                        <h6><i class="fas fa-clock me-1"></i> Ca {{ $session['session'] }}: {{ $session['session_time'] }}</h6>
                                        <p><i class="fas fa-door-open me-1"></i> <strong>Phòng:</strong> {{ $session['room'] }}</p>
                                        <p><i class="fas fa-book-open me-1"></i> <strong>Môn:</strong> {{ $session['subject'] }}</p>
                                        <div class="session-actions">
                                            <a href="{{ $session['edit_url'] }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Sửa</a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-muted"><i class="far fa-calendar-times me-1"></i> Không có lịch</div>
                            @endif
                        </td>

                        @if ($dayOfWeek == 6 && $day != $daysInMonth)
                            </tr><tr>
                        @endif
                    @endfor

                    @for ($blank = $currentDate->copy()->day($daysInMonth)->dayOfWeek + 1; $blank <= 6; $blank++)
                        <td></td>
                    @endfor
                </tr>
            </tbody>
        </table>
    @else
        <p class="text-center text-muted"><i class="far fa-calendar-times me-1"></i> Không có lịch học nào.</p>
    @endif
</div>
@endsection

@section('scripts')
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endsection