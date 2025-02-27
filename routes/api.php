<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ArquivosController;
use App\Http\Controllers\AssinaturaController;
use App\Http\Controllers\AutorizacaoController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\ConviteController;
use App\Http\Controllers\DestinatarioController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\EnumsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MensagemController;
use App\Http\Controllers\MensagemTemplateController;
use App\Http\Controllers\PastaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RegistroAssinaturaInternaController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersPerfilController;
use App\Http\Controllers\UsersPessoaFisicaController;
use App\Http\Controllers\UsersPessoaJuridicaController;
use App\Http\Middleware\ValidarPerfil;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt.auth', ValidarPerfil::class])->group(callback: function () {
    Route::name('arquivos.')->prefix('arquivos')->group(function () {
        Route::get('{relacionamento_nome}/{relacionamento_id}', [ArquivosController::class, 'listar'])->name('index');
        Route::post('{relacionamento_nome}/{relacionamento_id}', [ArquivosController::class, 'upload'])->name('upload');
        Route::get('{id}', [ArquivosController::class, 'show'])->name('show');
        Route::delete('{arquivo_id}', [ArquivosController::class, 'destroy'])->name('destroy');
    });

    Route::name('enums.')->prefix('enums')->group(function () {
        Route::get('dashboard-documentos', [EnumsController::class, 'dashboardDocumentos']);
        Route::get('lembrete-documentos', [EnumsController::class, 'lembreteDocumentos']);
        Route::get('status-documento', [EnumsController::class, 'statusDocumento']);
    });

    Route::get('arquivos-download/{id}', [ArquivosController::class, 'download'])->name('download');

    Route::apiResource('users', UsersController::class);
    Route::put('users/pessoa-fisica/{id}', [UsersPessoaFisicaController::class, 'update'])->name('users-pessoa-fisica.update');
    Route::put('users/pessoa-juridica/{id}', [UsersPessoaJuridicaController::class, 'update'])->name('users-pessoa-juridica.update');
    Route::get('me', [LoginController::class, 'me'])->name('users.me');

    Route::apiResource('tipos', TipoController::class);
    Route::apiResource('pastas', PastaController::class);
    Route::apiResource('convites', ConviteController::class);
    Route::apiResource('agendas', AgendaController::class);

    Route::apiResource('destinatarios', DestinatarioController::class);
    Route::apiResource('mensagens', MensagemController::class);
    Route::apiResource('configuracoes', ConfiguracaoController::class);
    Route::apiResource('mensagens-template', MensagemTemplateController::class);

    Route::apiResource('documentos', DocumentoController::class);
    Route::post('documentos/enviar', [DocumentoController::class, 'enviar'])->name('documentos.enviar');
    Route::get('documentos-dashboard', [DocumentoController::class, 'dashboard'])->name('documentos.dashboard');
    Route::delete('documentos/{id}/trash', [DocumentoController::class, 'deleteTrash'])->name('documentos.trash');
    Route::delete('documentos-clean-trash', [DocumentoController::class, 'cleanTrash'])->name('documentos.clean-trash');
    Route::patch('documentos/{id}/cancelamento', [DocumentoController::class, 'cancelar'])->name('documentos.cancelar');

    Route::apiResource('autorizacoes', AutorizacaoController::class);

    Route::apiResource('assinaturas', AssinaturaController::class);
    Route::patch('assinaturas/{documentoId}/finalizar-por-documento', [AssinaturaController::class, 'finalizarAssinaturasPorDocumento'])->name('assinaturas.finalizar-por-documento');

    Route::apiResource('certificados', CertificadoController::class);
    Route::get('certificados-download/{id}', [CertificadoController::class, 'download'])->name('certificados.download');
    Route::patch('certificados/{id}/selecao', [CertificadoController::class, 'selecionar'])->name('certificados.selecao');
    Route::apiResource('registro-assinatura-interna', RegistroAssinaturaInternaController::class);

    Route::apiResource('perfis', PerfilController::class);
    Route::post('perfis/upload', [PerfilController::class, 'upload'])->name('perfis.upload');

    Route::put('users-perfil/{id}', [UsersPerfilController::class, 'update'])->name('users-perfil.update');
});

Route::middleware(['jwt.auth'])->group(callback: function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('users.logout');
});

Route::get('assinaturas-relatorio', [AssinaturaController::class, 'getRelatorioAssinaturas'])->name('assinaturas.relatorio-assinaturas');
Route::get('assinaturas-relatorio-pdf', [AssinaturaController::class, 'getRelatorioAssinaturasPdf'])->name('assinaturas.relatorio-assinaturas-pdf');
Route::get('assinaturas-finalizacao-email', [AssinaturaController::class, 'getFinalizacaoAssinaturas'])->name('assinaturas.assinaturas-finalizacao-email');
Route::get('registro-assinatura-interna-validador/{id}', [RegistroAssinaturaInternaController::class, 'showToValidador'])->name('registro-assinatura-interna.show-validador');
Route::post('assinaturas/{assinaturaId}/finalizacao', [AssinaturaController::class, 'finalizacao'])->name('assinaturas.finalizacao');

Route::post('autorizacoes', [AutorizacaoController::class, 'store'])->name('autorizacoes.store');
Route::post('autorizacoes/validar-codigo', [AutorizacaoController::class, 'validarCodigo'])->name('autorizacoes.validar-codigo');
Route::patch('autorizacoes/{assinaturaId}/gerar-codigo', [AutorizacaoController::class, 'gerarCodigoPorAssinatura'])->name('autorizacoes.gerar-codigo');
Route::post('assinaturas', [AssinaturaController::class, 'store'])->name('assinaturas.store');
Route::get('destinatarios-assinatura/{id}', [DestinatarioController::class, 'showToAssinatura'])->name('destinatarios-assinatura.show');
Route::post('users/pessoa-fisica', [UsersPessoaFisicaController::class, 'store'])->name('users-pessoa-fisica.store');
Route::post('users/pessoa-juridica', [UsersPessoaJuridicaController::class, 'store'])->name('users-pessoa-juridica.store');
Route::post('users/ativacao', [UsersController::class, 'ativar'])->name('users.ativar');
Route::post('users/reset-password', [UsersController::class, 'resetPassword'])->name('users.reset-password');
Route::post('users/reset-password-link', [UsersController::class, 'resetPasswordLink'])->name('users.reset-password-link');
Route::post('users/gerar-ativacao-email', [UsersController::class, 'gerarAtivacaoPorEmail'])->name('users.gerar-ativacao-email');
Route::post('login', [LoginController::class, 'login'])->name('users.login');
Route::post('refresh', [LoginController::class, 'refresh'])->name('users.refresh');

Route::get('email-ativacao', [UsersController::class, 'getEmailAtivacao'])->name('users.email-ativacao');
Route::get('email-confirmacao', [UsersController::class, 'getEmailConfirmacao'])->name('users.email-confirmacao');
Route::get('email-convite-aceito', [ConviteController::class, 'getEmailConviteAceito'])->name('convite.email-aceito');
Route::get('email-convite-nao-aceito', [ConviteController::class, 'getEmailConviteNaoAceito'])->name('convite.email-nao-aceito');
