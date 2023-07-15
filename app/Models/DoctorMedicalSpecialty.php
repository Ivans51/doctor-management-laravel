<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorMedicalSpecialty extends Model
{
    use HasFactory;

    protected $table = 'doctor_medical_specialty';

    protected $fillable = [
        'doctor_id',
        'medical_specialty_id',
    ];

    // relation with medical specialty
    public function medicalSpecialty()
    {
        return $this->belongsTo(MedicalSpecialty::class);
    }
}
