<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Subject extends Model

{
    use HasFactory;
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

public function getCreditsAttribute()
{
    return $this->attributes['SubjectCredit']; // Adjust if the column name is different
}

 // Define relationship with Professor
 public function professors()
 {
     return $this->belongsToMany(Professor::class, 'subject_professor', 'SubjectID', 'ProfessorID');
 }
}
