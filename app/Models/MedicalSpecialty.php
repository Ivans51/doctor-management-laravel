<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class MedicalSpecialty extends Model
{
    use HasFactory, HasUuid;
    use SoftDeletes;

    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

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
