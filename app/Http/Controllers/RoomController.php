<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Hiển thị danh sách phòng học
    public function index()
    {
        $rooms = Room::all();
        return view('pdt.rooms.index', compact('rooms'));
    }

    // Hiển thị form tạo mới phòng học
    public function create()
    {
        return view('pdt.rooms.create');
    }

    // Lưu thông tin phòng học mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'RoomID' => 'required|string|unique:rooms,RoomID',
            'scale' => 'required|integer',
        ]);

        $RoomID = $validated['RoomID'];
        $parts = explode('-', $RoomID);

        $building = $parts[0];
        $floor = substr($parts[1], 0, 1);
        $floor = (int)$floor;

        // Tạo mới Room
        Room::create([
            'RoomID' => $validated['RoomID'],
            'floor' => $floor,
            'building' => $building,
            'scale' => $validated['scale'],
        ]);

        return redirect()->route('pdt.rooms.index')->with('success', 'Phòng học đã được thêm thành công.');
    }

    public function edit(Room $room)
    {
        return view('pdt.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'scale' => 'required|integer',
        ]);
        $RoomID = $validated['RoomID'];
        $parts = explode('-', $RoomID);
        $building = $parts[0];
        $floor = substr($parts[1], 0, 1);
        $floor = (int)$floor;
        Room::where('RoomID', $room->RoomID)->update([
            'building'=> $building,
            'floor' => $floor,
            'scale' => $validated['scale'],
        ]);
        return redirect()->route('pdt.rooms.index')->with('success', 'Phòng học đã được cập nhật thành công.');
    }

    // Các phương thức khác: edit, update, destroy...
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('pdt.rooms.index')->with('success', 'Phòng học đã được xóa thành công.');
    }
}
