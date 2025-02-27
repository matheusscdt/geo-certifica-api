<?php

namespace App\Services;

use App\Models\Arquivo;
use Illuminate\Support\Facades\Storage;
use Lacuna\RestPki\PadesMeasurementUnits;
use Lacuna\RestPki\PadesSignatureFinisher2;
use Lacuna\RestPki\PadesSignatureStarter;
use Lacuna\RestPki\RestPkiClient;
use Lacuna\RestPki\SignatureAlgorithmParameters;
use Lacuna\RestPki\StandardSecurityContexts;
use Lacuna\RestPki\StandardSignaturePolicies;

class LacunaService2
{
    public ArquivoService $arquivoService;
    protected RestPkiClient $restPkiClient;

    protected array $certObj;

    public function __construct(ArquivoService $arquivoService)
    {
        $this->arquivoService = $arquivoService;
        $this->restPkiClient = $this->getRestPkiClient();
        $this->certObj = $this->getCertObj();
    }

    private function getRestPkiClient(): RestPkiClient
    {
        return new RestPkiClient("https://pki.rest/", env("LACUNA_REST_PKI_ACCESS_TOKEN"));
    }

    public function getCertObj(): array
    {
        $certStore = Storage::disk('local')->get('up-leg-certificate.pfx');
        openssl_pkcs12_read($certStore, $certObj, "eiyRuWPja5Zgfb");
        return $certObj;
    }

    public function getSignatureStarter(Arquivo $arquivo): PadesSignatureStarter
    {
        $signatureStarter = new PadesSignatureStarter($this->restPkiClient);
        $signatureStarter->setPdfToSignFromContentRaw($this->getPdf($arquivo));
        $signatureStarter->signaturePolicy = StandardSignaturePolicies::PADES_BASIC;
        $signatureStarter->securityContext = StandardSecurityContexts::PKI_BRAZIL;
        $signatureStarter->setSignerCertificateRaw($this->certObj['cert']);
        $signatureStarter->measurementUnits = PadesMeasurementUnits::CENTIMETERS;
        return $signatureStarter;
    }

    public function getSignatureFinisher(SignatureAlgorithmParameters $signatureParams)
    {
        openssl_sign($signatureParams->toSignData, $signature, $this->certObj['pkey'], $signatureParams->openSslSignatureAlgorithm);
        $signatureFinisher = new PadesSignatureFinisher2($this->restPkiClient);
        $signatureFinisher->token = $signatureParams->token;
        $signatureFinisher->setSignatureRaw($signature);
        $signatureResult = $signatureFinisher->finish();
        return $signatureResult->getContentRaw();
    }

    public function getPdf(Arquivo $arquivo)
    {
        return Storage::get($arquivo->getArquivoWithBucket());
    }

    public function signature()
    {
        $arquivo = $this->arquivoService->find('9dea0e50-7bef-44f0-a25b-d508dd4c9849');
        $signatureStarter = $this->getSignatureStarter($arquivo);
        $signatureParams = $signatureStarter->start();
        $signatureStarter->getCertificateInfo();
        $pdf = $this->getSignatureFinisher($signatureParams);
        dd($pdf);
    }

    public function signatureTest()
    {
        $signatureStarter = new PadesSignatureStarter($this->restPkiClient);
        $certObj = $this->getCertObj();

        $arquivo = $this->arquivoService->find('9e083214-0089-4d7c-8316-f4493684bbe1');
        $pdf = Storage::get($arquivo->getArquivoWithBucket());


        $signatureStarter->setPdfToSignFromContentRaw($pdf);
        $signatureStarter->signaturePolicy = StandardSignaturePolicies::PADES_BASIC;
        $signatureStarter->securityContext = StandardSecurityContexts::PKI_BRAZIL;
        $signatureStarter->setSignerCertificateRaw($certObj['cert']);
        $signatureStarter->measurementUnits = PadesMeasurementUnits::CENTIMETERS;
        $token = $signatureStarter->startWithWebPki();
        $signatureParams = $signatureStarter->start();

        openssl_sign($signatureParams->toSignData, $signature, $certObj['pkey'], $signatureParams->openSslSignatureAlgorithm);

        $signatureFinisher = new PadesSignatureFinisher2($this->restPkiClient);

        $signatureFinisher->token = $signatureParams->token;

        $signatureFinisher->setSignatureRaw($signature);

        $signatureResult = $signatureFinisher->finish();

        $signerCert = $signatureResult->certificate;
        $content = $signatureResult->getContentRaw();

        $ano = now()->year;
        $mes = now()->month;

        $path = env('MINIO_BUCKET')."/arquivo-assinado/{$ano}/{$mes}/assinado.pdf";

        $filesystem = Storage::disk(env('FILESYSTEM_DRIVER'));

        $resultado = $filesystem->put($path, $content);
        dd($resultado);
    }
}
