<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum DashboardDocumentoPainelEnum: int
{
    use EnumTrait;

    case Todos = 1;
    case EmProcesso = 2;
    case Finalizados = 3;
    case Cancelados = 4;

    public function label(): string
    {
        return match ($this) {
            self::Todos => 'Todos',
            self::EmProcesso => 'Em processo',
            self::Finalizados => 'Finalizados',
            self::Cancelados => 'Cancelados'
        };
    }

    public function iconBgColor(): string
    {
        return match ($this) {
            self::Todos => 'bg-blue-50',
            self::EmProcesso => 'bg-yellow-50',
            self::Finalizados => 'bg-green-50',
            self::Cancelados => 'bg-red-50'
        };
    }

    public function iconTextColor(): string
    {
        return match ($this) {
            self::Todos => 'text-blue-600',
            self::EmProcesso => 'text-yellow-600',
            self::Finalizados => 'text-green-600',
            self::Cancelados => 'text-red-600'
        };
    }
}
