<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string id
 * @property string firstname
 * @property string lastname
 * @property string email
 * @property string activation_code
 * @property string email_verified_at
 * @property string password
 * @property string created_at
 * @property string updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TraitUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Create a unique activation code as string.
     * @return string
     */
    public static function getUniqueActivationCode()
    {
        $code = Str::random(30) . time();
        while (User::where('activation_code', $code)->exists()) {
            $code = Str::random(30) . time();
        }
        return $code;
    }
}
