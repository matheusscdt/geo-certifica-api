<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracao', function (Blueprint $table) {
            $table->date('data_limite_lembrete')->default(now()->addDays());
        });
    }

    public function down(): void
    {
        Schema::table('configuracao', function (Blueprint $table) {
            $table->dropColumn('data_limite_lembrete');
        });
    }
};
