@extends('layouts.pdt')

@section('head')
<!-- Thêm Bootstrap CSS nếu chưa có -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Quản lý Phòng</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        max-width: 1000px;
        margin: 40px auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #233871;
        text-align: center;
    }

    .create-button {
        margin-bottom: 20px;
    }

    table {
        margin-bottom: 0;
    }

    table th {
        background-color: #233871;
        color: #fff;
    }

    .action-buttons a,
    .action-buttons form {
        display: inline-block;
    }

    .action-buttons form {
        margin: 0;
    }

    .btn-edit {
        background-color: #4caf50;
        color: white;
    }

    .btn-delete {
        background-color: #f44336;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1>Quản lý Phòng</h1>
    <div class="d-flex justify-content-end">
        <a href="{{ route('pdt.rooms.create') }}" class="btn btn-primary create-button">Thêm Phòng Mới</a>
    </div>
    <table class="table table-bordered table-hover">
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
                        <a href="{{ route('pdt.rooms.edit', $room) }}" class="btn btn-sm btn-warning btn-edit">Sửa</a>
                        <form action="{{ route('pdt.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng này không?')" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-delete">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
