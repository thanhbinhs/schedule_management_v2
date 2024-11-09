<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Mối quan hệ với Department
    public function department()
    {
        return $this->hasOne(Department::class, 'DepartmentID', 'username');
    }

    // Mối quan hệ với Professor
    public function professor()
    {
        return $this->hasOne(Professor::class, 'ProfessorID', 'username');
    }
}
