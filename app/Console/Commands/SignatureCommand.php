<?php

namespace App\Console\Commands;

use App\Services\LacunaService;
use Illuminate\Console\Command;


class SignatureCommand extends Command
{
    protected $signature = 'app:signature';

    protected $description = 'Testes de assinatura';


    protected function lacunaService(): LacunaService
    {
        return app(LacunaService::class);
    }


    public function handle()
    {
        $this->lacunaService()->signature();
    }
}
