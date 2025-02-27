<?php

use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificado', function (Blueprint $table) {
            $table->dropForeign('certificado_pessoa_id_foreign');
            $table->dropColumn('pessoa_id');
            $table->uuid('perfil_id');
            $table->foreign(['perfil_id'])->references(['id'])->on('perfil')->onDelete('RESTRICT');
        });
    }

    public function down(): void
    {
        Schema::table('certificado', function (Blueprint $table) {
            $table->integer('pessoa_id');
            $table->foreign(['pessoa_id'])->references(['id'])->on('pessoa')->onDelete('RESTRICT');
        });
    }
};
