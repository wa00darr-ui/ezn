<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('employee_name');
            $table->string('action');
            $table->string('entity', 50);

            $table->string('record_reference')->nullable();
            $table->string('record_name')->nullable();

            $table->ipAddress('ip_address')->nullable();

            $table->json('details')->nullable();

            $table->timestamps();

            $table->index(['entity', 'created_at']);
            $table->index(['employee_name', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
