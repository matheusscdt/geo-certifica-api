<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('convite', function (Blueprint $table) {
            $table->boolean('gestor')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('convite', function (Blueprint $table) {
            $table->dropColumn('gestor');
        });
    }
};
