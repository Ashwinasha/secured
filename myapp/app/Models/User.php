<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationship with EmailVerificationCode
    public function emailVerificationCodes()
    {
        return $this->hasMany(EmailVerificationCode::class);
    }

    // Relationship with PasswordResetCode
    public function passwordResetCodes()
    {
        return $this->hasMany(PasswordResetCode::class);
    }
}
