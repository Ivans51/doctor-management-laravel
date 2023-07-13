<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'payment_id',
        'healthcare_provider',
        'description',
        'notes',
        'file',
        'status',
    ];

    // relation with patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // relation with doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // relation with schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
