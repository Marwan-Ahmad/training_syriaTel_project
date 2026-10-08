<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    use HasFactory;

    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'description',
    ];

    public function doctors(): HasMany
    {
        return $this->hasMany(
            DoctorProfile::class,
            'department_id'
        );
    }

    public function suggestedTickets(): HasMany
    {
        return $this->hasMany(
            MedicalTicket::class,
            'suggested_department_id'
        );
    }

    public function ticketDepartments(): HasMany
    {
        return $this->hasMany(
            TicketDepartment::class,
            'department_id'
        );
    }

    public function medicalTickets(): BelongsToMany
    {
        return $this->belongsToMany(
            MedicalTicket::class,
            'ticket_departments',
            'department_id',
            'ticket_id'
        )
            ->using(TicketDepartment::class)
            ->withPivot([
                'assigned_doctor_id',
                'assigned_at',
                'left_at',
            ]);
    }
}
