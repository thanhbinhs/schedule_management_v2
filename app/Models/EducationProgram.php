<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationProgram extends Model
{
    protected $primaryKey = 'EducationProgramID';
    public $incrementing = true;

    protected $fillable = [
        'EducationProgramName',
        'MajorID',
        'SubjectList',
    ];

    protected $casts = [
        'SubjectList' => 'array',
    ];

    // Mối quan hệ với Major
    public function major()
    {
        return $this->belongsTo(Major::class, 'MajorID', 'MajorID');
    }
}
