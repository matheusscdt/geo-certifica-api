<?php

namespace App\Console\Commands;

use App\Services\AssinaturaService;
use Illuminate\Console\Command;

class FinalizarAssinaturaComPrazoCommand extends Command
{
    protected $signature = 'app:finalizar-assinatura-com-prazo';

    protected $description = 'Finalizar assinaturas com prazo';

    protected function assinaturaService(): AssinaturaService
    {
        return app(AssinaturaService::class);
    }

    public function handle()
    {
        $this->assinaturaService()->finalizarAssinaturasPorPrazo();
    }
}
