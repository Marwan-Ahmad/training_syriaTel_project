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
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('medical_tickets')
                ->cascadeOnDelete()->restrictOnUpdate();

            $table->foreignId('sender_user_id')
                ->constrained('users')
                ->restrictOnDelete()->restrictOnUpdate();

            $table->foreignId('reply_to_id')
                ->nullable();

            $table->string('message_type', 40);

            $table->text('content');

            $table->timestamp('created_at')->nullable();

            $table->foreign('reply_to_id')
                ->references('id')
                ->on('ticket_messages')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
