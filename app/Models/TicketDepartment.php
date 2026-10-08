<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketDepartment extends Pivot
{
    protected $table = 'ticket_departments';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'department_id',
        'assigned_doctor_id',
        'assigned_at',
        'left_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            MedicalTicket::class,
            'ticket_id'
        );
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }

    public function assignedDoctor(): BelongsTo
    {
        return $this->belongsTo(
            DoctorProfile::class,
            'assigned_doctor_id'
        );
    }
}
