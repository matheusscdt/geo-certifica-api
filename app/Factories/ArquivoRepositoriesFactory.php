<?php

namespace App\Factories;

use App\Enums\RelatedUploadEnum;
use App\Repositories\CertificadoRepository;
use App\Repositories\DocumentoRepository;
use App\Repositories\PerfilRepository;
use App\Repositories\RegistroAssinaturaInternaRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArquivoRepositoriesFactory
{
    protected static array $classes = [
        RelatedUploadEnum::perfil->value => PerfilRepository::class,
        RelatedUploadEnum::documentos->value => DocumentoRepository::class,
        RelatedUploadEnum::certificados->value => CertificadoRepository::class,
        RelatedUploadEnum::registroAssinaturaInterna->value => RegistroAssinaturaInternaRepository::class
    ];

    public static function make(RelatedUploadEnum $relacionamento)
    {
        if (!array_key_exists($relacionamento->value, self::$classes)) {
            throw new ModelNotFoundException();
        }

        return app(self::$classes[$relacionamento->value]);
    }
}
