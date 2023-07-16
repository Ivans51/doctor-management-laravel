<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalSpecialty extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'medical_specialties';

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
    ];

    // relation with doctor medical specialty
    public function doctorMedicalSpecialty()
    {
        return $this->hasMany(DoctorMedicalSpecialty::class);
    }
}
