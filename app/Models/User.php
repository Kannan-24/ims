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
    'password_last_reminder_sent_at'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }
}
