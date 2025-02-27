<?php

namespace App\Providers;

use App\Repositories\AgendaRepository;
use App\Repositories\AgendaRepositoryEloquent;
use App\Repositories\ArquivoRepository;
use App\Repositories\ArquivoRepositoryEloquent;
use App\Repositories\AssinaturaRepository;
use App\Repositories\AssinaturaRepositoryEloquent;
use App\Repositories\AutorizacaoRepository;
use App\Repositories\AutorizacaoRepositoryEloquent;
use App\Repositories\CertificadoRepository;
use App\Repositories\CertificadoRepositoryEloquent;
use App\Repositories\ConfiguracaoRepository;
use App\Repositories\ConfiguracaoRepositoryEloquent;
use App\Repositories\ConviteRepository;
use App\Repositories\ConviteRepositoryEloquent;
use App\Repositories\DestinatarioRepository;
use App\Repositories\DestinatarioRepositoryEloquent;
use App\Repositories\DocumentoRepository;
use App\Repositories\DocumentoRepositoryEloquent;
use App\Repositories\MensagemRepository;
use App\Repositories\MensagemRepositoryEloquent;
use App\Repositories\MensagemTemplateRepository;
use App\Repositories\MensagemTemplateRepositoryEloquent;
use App\Repositories\PastaRepository;
use App\Repositories\PastaRepositoryEloquent;
use App\Repositories\PerfilRepository;
use App\Repositories\PerfilRepositoryEloquent;
use App\Repositories\PessoaFisicaRepository;
use App\Repositories\PessoaFisicaRepositoryEloquent;
use App\Repositories\PessoaJuridicaRepository;
use App\Repositories\PessoaJuridicaRepositoryEloquent;
use App\Repositories\PessoaRepository;
use App\Repositories\PessoaRepositoryEloquent;
use App\Repositories\RegistroAssinaturaInternaRepository;
use App\Repositories\RegistroAssinaturaInternaRepositoryEloquent;
use App\Repositories\RegistroAssinaturaRepository;
use App\Repositories\RegistroAssinaturaRepositoryEloquent;
use App\Repositories\TipoRepository;
use App\Repositories\TipoRepositoryEloquent;
use App\Repositories\UserPerfilRepository;
use App\Repositories\UserPerfilRepositoryEloquent;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        $this->app->bind(ArquivoRepository::class, ArquivoRepositoryEloquent::class);
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(PessoaRepository::class, PessoaRepositoryEloquent::class);
        $this->app->bind(PessoaFisicaRepository::class, PessoaFisicaRepositoryEloquent::class);
        $this->app->bind(PessoaJuridicaRepository::class, PessoaJuridicaRepositoryEloquent::class);
        $this->app->bind(PerfilRepository::class, PerfilRepositoryEloquent::class);
        $this->app->bind(PastaRepository::class, PastaRepositoryEloquent::class);
        $this->app->bind(AgendaRepository::class, AgendaRepositoryEloquent::class);
        $this->app->bind(UserPerfilRepository::class, UserPerfilRepositoryEloquent::class);
        $this->app->bind(ConviteRepository::class, ConviteRepositoryEloquent::class);
        $this->app->bind(TipoRepository::class, TipoRepositoryEloquent::class);
        $this->app->bind(MensagemTemplateRepository::class, MensagemTemplateRepositoryEloquent::class);
        $this->app->bind(MensagemRepository::class, MensagemRepositoryEloquent::class);
        $this->app->bind(DestinatarioRepository::class, DestinatarioRepositoryEloquent::class);
        $this->app->bind(DocumentoRepository::class, DocumentoRepositoryEloquent::class);
        $this->app->bind(ConfiguracaoRepository::class, ConfiguracaoRepositoryEloquent::class);
        $this->app->bind(AutorizacaoRepository::class, AutorizacaoRepositoryEloquent::class);
        $this->app->bind(AssinaturaRepository::class, AssinaturaRepositoryEloquent::class);
        $this->app->bind(CertificadoRepository::class, CertificadoRepositoryEloquent::class);
        $this->app->bind(RegistroAssinaturaRepository::class, RegistroAssinaturaRepositoryEloquent::class);
        $this->app->bind(RegistroAssinaturaInternaRepository::class, RegistroAssinaturaInternaRepositoryEloquent::class);
    }
}
