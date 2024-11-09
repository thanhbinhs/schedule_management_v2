<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>
<body>
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
                <label for="floor">Tầng</label>
                <input type="number" id="floor" name="floor" value="{{ $room->floor }}" required>
            </div>
            <div class="form-group">
                <label for="building">Tòa Nhà</label>
                <input type="text" id="building" name="building" value="{{ $room->building }}" required>
            </div>
            <div class="form-group">
                <label for="scale">Sức Chứa</label>
                <input type="number" id="scale" name="scale" value="{{ $room->scale }}" required>
            </div>
            <button type="submit">Cập Nhật</button>
        </form>
        <a href="{{ route('pdt.rooms.index') }}">Quay lại</a>
    </div>
</body>
</html>
