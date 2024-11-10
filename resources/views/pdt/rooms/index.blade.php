@extends('layouts.pdt')

@section('head')
<!-- Thêm Bootstrap CSS nếu chưa có -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Quản lý Phòng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #233871;
        }

        a, button {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #233871;
            color: white;
        }

        .btn-secondary {
            background-color: #ccc;
            color: black;
        }

        .btn-edit {
            background-color: #4caf50;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .action-btns {
            display: flex;
            gap: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1>Quản lý Phòng</h1>
        <a href="{{ route('pdt.rooms.create') }}" class="create-button">Thêm Phòng Mới</a>
        <table>
            <thead>
                <tr>
                    <th>Room ID</th>
                    <th>Tầng</th>
                    <th>Tòa Nhà</th>
                    <th>Sức Chứa</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr>
                        <td>{{ $room->RoomID }}</td>
                        <td>{{ $room->floor }}</td>
                        <td>{{ $room->building }}</td>
                        <td>{{ $room->scale }}</td>
                        <td class="action-buttons">
                            <a href="{{ route('pdt.rooms.edit', $room) }}" class="edit-button">Sửa</a>
                            <form action="{{ route('pdt.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng này không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
