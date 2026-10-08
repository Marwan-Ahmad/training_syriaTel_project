<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DoctorProfile extends Model
{
    use HasFactory;

    public const CREATED_AT = null;
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'department_id',
        'specialization',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(
            DoctorSchedule::class,
            'doctor_id'
        );
    }

    public function ticketDepartments(): HasMany
    {
        return $this->hasMany(
            TicketDepartment::class,
            'assigned_doctor_id'
        );
    }

    public function handledTickets(): BelongsToMany
    {
        return $this->belongsToMany(
            MedicalTicket::class,
            'ticket_departments',
            'assigned_doctor_id',
            'ticket_id'
        )
            ->using(TicketDepartment::class)
            ->withPivot([
                'department_id',
                'assigned_at',
                'left_at',
            ]);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(
            Appointment::class,
            'doctor_id'
        );
    }
}
