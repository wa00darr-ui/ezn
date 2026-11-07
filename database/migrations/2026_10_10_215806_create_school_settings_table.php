
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('ministerial_number')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('logo_path')->nullable();

            $table->time('open_time')->default('07:00:00');
            $table->time('close_time')->default('13:30:00');

            $table->unsignedSmallInteger('code_validity_minutes')
                ->default(30);

            $table->unsignedSmallInteger('daily_limit')
                ->default(1);

            $table->boolean('require_identity_image')
                ->default(true);

            $table->boolean('require_signature')
                ->default(true);

            $table->boolean('allow_requests')
                ->default(true);

            $table->boolean('notify_staff')
                ->default(true);

            $table->boolean('notify_decision')
                ->default(true);

            $table->boolean('notify_checkout')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
