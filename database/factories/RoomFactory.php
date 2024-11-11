<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Room;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        $roomCounters = []; // Lưu trữ các RoomID đã tạo

        do {
            $building = 'A' . $this->faker->numberBetween(1, 8);
            $floor = $this->faker->numberBetween(1, 7);

            // Sử dụng một bộ đếm tĩnh để theo dõi số lượng phòng đã tạo trên mỗi tầng của từng building
            if (!isset($roomCounters[$building][$floor])) {
                $roomCounters[$building][$floor] = 1;
            } else {
                $roomCounters[$building][$floor]++;
            }

            // Giới hạn số phòng tối đa cho mỗi building-floor
            if ($roomCounters[$building][$floor] > 9) { // Điều chỉnh phạm vi nếu cần
                throw new \Exception("Exceeded the maximum number of rooms for {$building}-{$floor}");
            }

            $roomNumber = sprintf('%02d', $roomCounters[$building][$floor]);
            $roomID = "{$building}-{$floor}{$roomNumber}";

        } while (in_array($roomID, array_column($roomCounters, 'RoomID')));

        return [
            'RoomID' => $roomID,
            'floor' => $floor,
            'building' => $building,
            'scale' => $this->faker->numberBetween(50, 100),
        ];
    }
}
