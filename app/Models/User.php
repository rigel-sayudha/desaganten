<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nik',
        'wilayah_id',
        'rt',
        'rw',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Validation rules untuk NIK
     */
    public static function nikValidationRules()
    {
        return [
            'nik' => [
                'required',
                'string',
                'size:16',
                'regex:/^[0-9]{16}$/',
                'unique:users,nik'
            ]
        ];
    }
    
    /**
     * Check if user has completed NIK
     */
    public function hasCompletedNik()
    {
        return !empty($this->nik) && strlen($this->nik) === 16;
    }
    
    /**
     * Relasi dengan Wilayah
     */
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }
}
