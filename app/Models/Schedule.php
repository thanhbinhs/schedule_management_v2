<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'RoomID',
        'date',
        'session_number',
        'subject_id',
        'professor_id',
    ];

    /**
     * Quan hệ với Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'RoomID', 'RoomID');
    }

    /**
     * Quan hệ với Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'SubjectID');
    }

    /**
     * Quan hệ với Professor
     */
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'ProfessorID');
    }

    /**
     * Accessor để lấy thời gian ca học.
     *
     * @return string
     */
    public function getSessionTimeAttribute()
    {
        $sessions = [
            1 => '6h45-9h25',
            2 => '9h30-12h10',
            3 => '1h-3h40',
            4 => '3h45-6h25',
        ];

        return $sessions[$this->session_number] ?? 'N/A';
    }
}
