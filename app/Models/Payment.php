<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Payment extends Model
{
    use HasFactory, HasUuid;

    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_date',
        'transaction_id',
    ];

    // relation with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
