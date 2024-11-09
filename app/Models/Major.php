<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $primaryKey = 'MajorID';
    public $incrementing = true;

    protected $fillable = [
        'MajorName',
        'DepartmentID',
    ];

    // Mối quan hệ với Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentID', 'DepartmentID');
    }

    // Mối quan hệ với EducationPrograms
    public function educationPrograms()
    {
        return $this->hasMany(EducationProgram::class, 'MajorID', 'MajorID');
    }
}
