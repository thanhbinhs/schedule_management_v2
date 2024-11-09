<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'DepartmentID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'DepartmentID',
        'DepartmentName',
        'DepartmentAddress',
        'LeaderDepartmentID',
    ];

    // Mối quan hệ với Account
    public function account()
    {
        return $this->belongsTo(Account::class, 'DepartmentID', 'username');
    }

    // Mối quan hệ với Professor Leader
    public function leader()
    {
        return $this->belongsTo(Professor::class, 'LeaderDepartmentID', 'ProfessorID');
    }

    // Mối quan hệ với Majors
    public function majors()
    {
        return $this->hasMany(Major::class, 'DepartmentID', 'DepartmentID');
    }

    // Mối quan hệ với Subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'DepartmentID', 'DepartmentID');
    }
}
