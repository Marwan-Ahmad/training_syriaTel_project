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
        Schema::create('ticket_departments', function (Blueprint $table) {

            $table->foreignId('ticket_id')
                ->constrained('medical_tickets')
                ->cascadeOnDelete()->restrictOnUpdate();

            $table->foreignId('department_id')
                ->constrained('departments')
                ->restrictOnDelete()->restrictOnUpdate();

            $table->foreignId('assigned_doctor_id')
                ->constrained('doctor_profiles')
                ->restrictOnDelete()->restrictOnUpdate();

            $table->timestamp('assigned_at');

            $table->timestamp('left_at')->nullable();

            $table->primary([
                'ticket_id',
                'department_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_departments');
    }
};
