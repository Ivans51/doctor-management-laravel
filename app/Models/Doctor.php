<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Doctor extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'name',
        'speciality',
        'phone',
        'address',
        'photo',
        'status',
        'user_id',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // relation with doctor medical specialty
    public function medicalSpecialty(): HasMany
    {
        return $this->hasMany(DoctorMedicalSpecialty::class);
    }

    // many to many doctor patient
    public function patientDoctor()
    {
        return $this->hasMany(PatientDoctor::class, 'doctor_id');
    }
}
