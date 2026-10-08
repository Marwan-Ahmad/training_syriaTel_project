<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MedicalTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'suggested_department_id',
        'symptoms',
        'symptom_duration',
        'lab_result',
        'risk_level',
        'priority',
        'status',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            PatientProfile::class,
            'patient_id'
        );
    }

    public function suggestedDepartment(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'suggested_department_id'
        );
    }

    public function ticketDepartments(): HasMany
    {
        return $this->hasMany(
            TicketDepartment::class,
            'ticket_id'
        );
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(
            Department::class,
            'ticket_departments',
            'ticket_id',
            'department_id'
        )
            ->using(TicketDepartment::class)
            ->withPivot([
                'assigned_doctor_id',
                'assigned_at',
                'left_at',
            ]);
    }

    public function labReport(): HasOne
    {
        return $this->hasOne(
            LabReport::class,
            'ticket_id'
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(
            TicketMessage::class,
            'ticket_id'
        );
    }

    public function history(): HasMany
    {
        return $this->hasMany(
            TicketHistory::class,
            'ticket_id'
        );
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(
            Appointment::class,
            'ticket_id'
        );
    }
}
