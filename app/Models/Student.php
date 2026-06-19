<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $incrementing = false;

    protected $keyType = 'int';

    protected $table = 'students';

    protected $fillable = [
        'id',
        'student_name',
        'date_of_birth',
        'age',
        'guardian_name',
        'mobile_number',
        'remarks',
        'status',
    ];
}
