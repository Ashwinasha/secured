<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if the reset code is expired
    public function isExpired()
    {
        return $this->expires_at < Carbon::now();
    }
}
