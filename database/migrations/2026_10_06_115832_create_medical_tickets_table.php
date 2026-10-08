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
        Schema::create('medical_tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('patient_profiles')
                ->restrictOnDelete()->restrictOnUpdate();

            $table->foreignId('suggested_department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->text('symptoms');

            $table->string('symptom_duration', 100);

            $table->string('lab_result', 100)->nullable();

            $table->string('risk_level', 30)->nullable();

            $table->string('priority', 30)->nullable();

            $table->string('status', 50)
                ->default('SUBMITTED');

            $table->timestamps();

            $table->timestamp('closed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_tickets');
    }
};
