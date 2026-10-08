<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorSchedule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'appointment_duration',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'appointment_duration' => 'integer',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(
            DoctorProfile::class,
            'doctor_id'
        );
    }
}
