
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permission_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('permission_requests', 'status')) {
            $table->string('status', 30)
                    ->default('pending')
                    ->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('permission_requests', function (Blueprint $table) {
            if (Schema::hasColumn('permission_requests', 'status')) {
            $table->dropColumn('status');
            }
        });
    }
};
