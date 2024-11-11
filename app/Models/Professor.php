<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professor extends Model
{
    use HasFactory;
    protected $primaryKey = 'ProfessorID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ProfessorID',
        'ProfessorName',
        'ProfessorGmail',
        'ProfessorPhone',
        'DepartmentID', // Thêm DepartmentID
        'isLeaderDepartment',
    ];

    // Mối quan hệ với Account
    public function account()
    {
        return $this->belongsTo(Account::class, 'ProfessorID', 'username');
    }

    // Mối quan hệ với Department nếu là leader
    public function departmentLeader()
    {
        return $this->hasOne(Department::class, 'LeaderDepartmentID', 'ProfessorID');
    }

    public function department()
{
    return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID');
}

// Define relationship with Subject
public function subjects()
{
    return $this->belongsToMany(Subject::class, 'subject_professor', 'ProfessorID', 'SubjectID');
}
}
