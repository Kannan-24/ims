<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

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
        'profile_photo',
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

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }

    /**
     * Messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Messages received by this user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get unread message count for this user
     */
    public function getUnreadMessageCountAttribute()
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }
}
