<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum LembreteDocumentoEnum: int
{
    use EnumTrait;

    case ZeroDias = 0;
    case UmDia = 1;
    case DoisDias = 2;
    case TresDias = 3;
    case CincoDias = 5;
    case SeteDias = 7;

    public function label(): string
    {
        return match ($this) {
            self::ZeroDias => 'Nunca',
            self::UmDia => '1 dia',
            self::DoisDias => '2 dias',
            self::TresDias => '3 dias',
            self::CincoDias => '5 dias',
            self::SeteDias => '7 dias'
        };
    }
}
