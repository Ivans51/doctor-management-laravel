<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class PatientDoctor extends Model
{
    use HasFactory, HasUuid;

    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $table = 'patients_doctors';

    protected $fillable = [
        'patient_id',
        'doctor_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
