<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->unique()
                ->constrained('medical_tickets')
                ->cascadeOnDelete()->restrictOnUpdate();

            $table->foreignId('doctor_id')
                ->constrained('doctor_profiles')
                ->restrictOnDelete()->restrictOnUpdate();

            $table->date('appointment_date');

            $table->time('start_time');

            $table->time('end_time');

            $table->string('status', 30)
                ->default('PENDING_CONFIRMATION');

            $table->foreignId('confirmed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
