<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // use HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable;

    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';
    protected $fillable = [
        'role_id',
        'full_name',
        'email',
        'phone',
        'password',
        'approval_status'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function patientProfile(): HasOne
    {
        return $this->hasOne(PatientProfile::class);
    }

    public function doctorProfile(): HasOne
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(
            TicketMessage::class,
            'sender_user_id'
        );
    }

    public function ticketHistory(): HasMany
    {
        return $this->hasMany(
            TicketHistory::class,
            'changed_by_user_id'
        );
    }

    public function confirmedAppointments(): HasMany
    {
        return $this->hasMany(
            Appointment::class,
            'confirmed_by'
        );
    }

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(
            Notification::class,
            'notification_users',
            'user_id',
            'notification_id'
        )
            ->using(NotificationUser::class)
            ->withPivot([
                'is_read',
                'read_at',
            ]);
    }
}
