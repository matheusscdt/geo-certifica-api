<?php

namespace App\Providers;

use App\Models\Assinatura;
use App\Models\Autorizacao;
use App\Models\Certificado;
use App\Models\Configuracao;
use App\Models\Convite;
use App\Models\Destinatario;
use App\Models\Documento;
use App\Models\Mensagem;
use App\Models\Perfil;
use App\Models\PessoaJuridica;
use App\Models\User;
use App\Observers\AssinaturaObserver;
use App\Observers\AutorizacaoObserver;
use App\Observers\CertificadoObserver;
use App\Observers\ConfiguracaoObserver;
use App\Observers\ConviteObserver;
use App\Observers\DestinatarioObserver;
use App\Observers\DocumentoObserver;
use App\Observers\MensagemObserver;
use App\Observers\PerfilObserver;
use App\Observers\PessoaJuridicaObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        User::observe(UserObserver::class);
        Convite::observe(ConviteObserver::class);
        Documento::observe(DocumentoObserver::class);
        Destinatario::observe(DestinatarioObserver::class);
        Mensagem::observe(MensagemObserver::class);
        Configuracao::observe(ConfiguracaoObserver::class);
        Autorizacao::observe(AutorizacaoObserver::class);
        Assinatura::observe(AssinaturaObserver::class);
        PessoaJuridica::observe(PessoaJuridicaObserver::class);
        Perfil::observe(PerfilObserver::class);
        Certificado::observe(CertificadoObserver::class);
    }
}
