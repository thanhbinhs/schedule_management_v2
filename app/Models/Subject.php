<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $primaryKey = 'SubjectID';
    public $incrementing = true;

    protected $fillable = [
        'SubjectName',
        'DepartmentID',
        'SubjectCredit',
        'SubjectLessons',
    ];

    // Mối quan hệ với Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID');
    }
    // Trong model Subject
public function schedules()
{
    return $this->hasMany(Schedule::class, 'subject_id', 'SubjectID'); // Điều chỉnh tên cột nếu cần thiết
}
}
