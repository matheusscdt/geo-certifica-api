<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assinatura', function (Blueprint $table) {
            $table->ipAddress();
            $table->string('dispositivo');
        });
    }

    public function down(): void
    {
        Schema::table('assinatura', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'dispositivo']);
        });
    }
};
