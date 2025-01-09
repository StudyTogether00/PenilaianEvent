<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "mstuser";
    protected $primaryKey = 'userid';

    protected $fillable = [
        'userid',
        'username',
        'password',
        'fullname',
        "flag_active",
        "created_at",
        "updated_at",
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
