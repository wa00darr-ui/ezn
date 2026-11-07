<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permission_requests', function (Blueprint $table) {

            // بيانات الطالب
            if (!Schema::hasColumn('permission_requests', 'student_id')) {
                $table->foreignId('student_id')->nullable()->index();
            }

            // بيانات ولي الأمر
            if (!Schema::hasColumn('permission_requests', 'parent_name')) {
                $table->string('parent_name', 150)->nullable();
            }

            if (!Schema::hasColumn('permission_requests', 'parent_phone')) {
                $table->string('parent_phone', 30)->nullable();
            }

            if (!Schema::hasColumn('permission_requests', 'guardian_id')) {
                $table->string('guardian_id', 10)->nullable();
            }

            if (!Schema::hasColumn('permission_requests', 'guardian_relation')) {
                $table->string('guardian_relation', 50)->nullable();
            }

            // تفاصيل الاستئذان
            if (!Schema::hasColumn('permission_requests', 'reason')) {
                $table->text('reason')->nullable();
            }

            if (!Schema::hasColumn('permission_requests', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('permission_requests', 'requested_at')) {
                $table->timestamp('requested_at')->nullable();
            }

            // مسارات الملفات المحفوظة بشكل خاص
            if (!Schema::hasColumn('permission_requests', 'identity_path')) {
                $table->string('identity_path')->nullable();
            }

            if (!Schema::hasColumn('permission_requests', 'signature_path')) {
                $table->string('signature_path')->nullable();
            }

            // مراجعة الطلب
            if (!Schema::hasColumn('permission_requests', 'reviewed_by')) {
                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('permission_requests', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }

            if (!Schema::hasColumn('permission_requests', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }

        });
    }

    public function down(): void
    {
        // لا نحذف الأعمدة في التراجع تلقائيًا
        // للمحافظة على بيانات الطلبات.
    }
};