@extends('layouts.pdt')

@section('head')
    <!-- Thêm Bootstrap CSS nếu chưa có -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sửa Phòng</title>
    <style>
        .container {
            width: 50%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], input[type="number"] {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #233871;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        a {
            margin-top: 10px;
            text-decoration: none;
            color: #233871;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1>Sửa Phòng</h1>
        <form action="{{ route('pdt.rooms.update', $room) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="RoomID">Room ID</label>
                <input type="text" id="RoomID" name="RoomID" value="{{ $room->RoomID }}" readonly>
            </div>
            <div class="form-group">
                <label for="scale">Sức Chứa</label>
                <input type="number" id="scale" name="scale" value="{{ $room->scale }}" required>
            </div>
            <button type="submit">Cập Nhật</button>
        </form>
    </div>
@endsection
