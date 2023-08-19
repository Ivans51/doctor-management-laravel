<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Message extends Model
{
    use HasFactory, HasUuid;

    public $keyType = 'string';
    protected string $uuidColumnName = 'id';

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
