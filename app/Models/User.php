<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'blood_group',
        'state',
        'gender',
        'dob',
        'phone',
        'doj',
        'designation',
        'role',
        'employee_id',
        'status',
        'profile_image',
        'remember_token',
        'is_verified',
        'is_active',
        'is_deleted',
        'created_by',
        'updated_by',
    'deleted_by',
    'must_change_password',
    'password_expires_at',
    'last_password_changed_at',
    'password_last_reminder_sent_at',
    'two_factor_enabled',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'two_factor_confirmed_at',
    'preferred_2fa_method',
    'pending_otp_code',
    'pending_otp_expires_at'
    ];
    
    protected $attributes = [
        'totp_enabled' => false,
        'email_otp_enabled' => false,
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'pending_otp_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_expires_at' => 'datetime',
            'last_password_changed_at' => 'datetime',
            'password_last_reminder_sent_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'pending_otp_expires_at' => 'datetime',
            'totp_enabled' => 'boolean',
            'email_otp_enabled' => 'boolean',
        ];
    }
}
