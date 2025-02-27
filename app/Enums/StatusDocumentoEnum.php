<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum StatusDocumentoEnum: int
{
    use EnumTrait;

    case Rascunho = 1;
    case EmProcesso = 2;
    case Finalizado = 3;
    case Cancelado = 4;
    case Baixado = 5;

    public function label(): string
    {
        return match ($this) {
            self::Rascunho => 'Rascunho',
            self::EmProcesso => 'Em processo',
            self::Finalizado => 'Finalizado',
            self::Cancelado => 'Cancelado',
            self::Baixado => 'Baixado'
        };
    }
}
