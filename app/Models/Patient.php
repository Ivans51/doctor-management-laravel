<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Patient extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'name',
        'phone',
        'address',
        'status',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // many to many doctor patient
    public function doctorPatient()
    {
        return $this->hasMany(PatientDoctor::class, 'patient_id');
    }
}
