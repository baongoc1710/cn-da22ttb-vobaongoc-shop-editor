<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Nếu dùng API

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',   // admin, staff, customer
        'status', // active, banned
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Các mối quan hệ
    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function savedDesigns() {
        return $this->hasMany(SavedDesign::class);
    }

    public function uploads() {
        return $this->hasMany(UserUpload::class);
    }
}