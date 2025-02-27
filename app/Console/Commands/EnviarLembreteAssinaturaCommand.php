<?php

namespace App\Console\Commands;

use App\Services\DocumentoService;
use Illuminate\Console\Command;

class EnviarLembreteAssinaturaCommand extends Command
{
    protected $signature = 'app:enviar-lembrete-assinatura';

    protected $description = 'Enviar lembrete de assinatura';

    protected function documentoService(): DocumentoService
    {
        return app(DocumentoService::class);
    }

    public function handle()
    {
        $this->documentoService()->enviarLembretes();
    }
}
