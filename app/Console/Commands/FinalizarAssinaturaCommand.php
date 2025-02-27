<?php

namespace App\Console\Commands;

use App\Services\AssinaturaService;
use Illuminate\Console\Command;

class FinalizarAssinaturaCommand extends Command
{
    protected $signature = 'app:finalizar-assinatura';

    protected $description = 'Finalizar assinatura';

    protected function assinaturaService(): AssinaturaService
    {
        return app(AssinaturaService::class);
    }

    public function handle()
    {
        $this->assinaturaService()->finalizarAssinaturasPorDocumento('9e4dc40c-b724-4041-91b9-aa337139710f');
    }
}
