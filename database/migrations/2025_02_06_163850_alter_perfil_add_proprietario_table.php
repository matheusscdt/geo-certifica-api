<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfil', function (Blueprint $table) {
            $table->boolean('proprietario')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('perfil', function (Blueprint $table) {
            $table->dropColumn('proprietario');
        });
    }
};
