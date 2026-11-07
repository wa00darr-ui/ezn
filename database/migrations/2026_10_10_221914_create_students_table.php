
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('student_number', 50)->unique();
            $table->string('national_id', 20)->unique();
            $table->string('grade', 100);
            $table->string('classroom', 30);

            $table->string('guardian_name', 255);
            $table->string('guardian_phone', 25);

            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamps();

            $table->index(['grade', 'classroom']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
