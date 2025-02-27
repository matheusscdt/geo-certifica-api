<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_perfil', function (Blueprint $table) {
            $table->boolean('ativo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('user_perfil', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
};
