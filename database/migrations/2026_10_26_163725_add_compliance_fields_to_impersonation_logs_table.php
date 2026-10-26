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
        Schema::table('impersonation_logs', function (Blueprint $table) {
            $table->string('ticket_id')->nullable()->after('user_id');
            $table->string('reason')->nullable()->after('ticket_id');
            $table->string('access_mode')->default('read_only')->after('reason'); // 'read_only' or 'full_access'
            $table->integer('duration_minutes')->default(15)->after('access_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('impersonation_logs', function (Blueprint $table) {
            $table->dropColumn(['ticket_id', 'reason', 'access_mode', 'duration_minutes']);
        });
    }
};
