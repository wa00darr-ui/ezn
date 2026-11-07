
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permission_requests', function (Blueprint $table) {
            $table->string('pickup_code_digest', 64)
                ->nullable()
                ->unique();

            $table->timestamp('pickup_code_expires_at')
                ->nullable();

            $table->timestamp('identity_verified_at')
                ->nullable();

            $table->foreignId('identity_verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('checked_out_at')
                ->nullable();

            $table->foreignId('checked_out_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('permission_requests', function (Blueprint $table) {
            $table->dropForeign(['identity_verified_by']);
            $table->dropForeign(['checked_out_by']);

            $table->dropColumn([
                'pickup_code_digest',
                'pickup_code_expires_at',
                'identity_verified_at',
                'identity_verified_by',
                'checked_out_at',
                'checked_out_by',
            ]);
        });
    }
};
