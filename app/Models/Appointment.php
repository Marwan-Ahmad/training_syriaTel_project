<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'doctor_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'confirmed_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            MedicalTicket::class,
            'ticket_id'
        );
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(
            DoctorProfile::class,
            'doctor_id'
        );
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'confirmed_by'
        );
    }
}
