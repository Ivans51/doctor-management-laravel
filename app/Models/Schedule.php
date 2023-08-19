<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Schedule extends Model
{
    use HasFactory, HasUuid;

    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
    ];

    // relation with appointment
    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
