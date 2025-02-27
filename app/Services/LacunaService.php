<?php

namespace App\Services;

use Illuminate\Support\MessageBag;
use Lacuna\RestPki\PadesMeasurementUnits;
use Lacuna\RestPki\PadesSignatureFinisher2;
use Lacuna\RestPki\PadesSignatureStarter;
use Lacuna\RestPki\RestPkiClient;
use Lacuna\RestPki\SignatureAlgorithmParameters;
use Lacuna\RestPki\StandardSecurityContexts;
use Lacuna\RestPki\StandardSignaturePolicies;
use Prettus\Validator\Exceptions\ValidatorException;

class LacunaService
{
    protected RestPkiClient $restPkiClient;

    public function __construct()
    {
        $this->restPkiClient = $this->getRestPkiClient();
    }

    private function getRestPkiClient(): RestPkiClient
    {
        return new RestPkiClient("https://pki.rest/", env("LACUNA_REST_PKI_ACCESS_TOKEN"));
    }

    public function getCertInfo($contentStoreFile, string $password): array
    {
        $certObj = $this->getCertObj($contentStoreFile, $password);
        return openssl_x509_parse($certObj['cert']);
    }

    public function getCertObj($contentStoreFile, string $password): array
    {
        if (!openssl_pkcs12_read($contentStoreFile, $certObj, $password)) {
            throw new ValidatorException(new MessageBag([
                "contentStoreFile" => "O arquivo de certificado digital não pôde ser lido."
            ]));
        }
        return $certObj;
    }

    public function getSignatureStarter($pdfContent, array $certObj): PadesSignatureStarter
    {
        $signatureStarter = new PadesSignatureStarter($this->restPkiClient);
        $signatureStarter->setPdfToSignFromContentRaw($pdfContent);
        $signatureStarter->signaturePolicy = StandardSignaturePolicies::PADES_BASIC;
        $signatureStarter->securityContext = StandardSecurityContexts::PKI_BRAZIL;
        $signatureStarter->setSignerCertificateRaw($certObj['cert']);
        $signatureStarter->measurementUnits = PadesMeasurementUnits::CENTIMETERS;
        return $signatureStarter;
    }

    public function getSignatureFinisher(SignatureAlgorithmParameters $signatureParams, array $certObj)
    {
        openssl_sign($signatureParams->toSignData, $signature, $certObj['pkey'], $signatureParams->openSslSignatureAlgorithm);
        $signatureFinisher = new PadesSignatureFinisher2($this->restPkiClient);
        $signatureFinisher->token = $signatureParams->token;
        $signatureFinisher->setSignatureRaw($signature);
        $signatureResult = $signatureFinisher->finish();
        return $signatureResult->getContentRaw();
    }

    public function signature($contentStoreFile, $pdfContent, string $password)
    {
        $certObj = $this->getCertObj($contentStoreFile, $password);
        $signatureStarter = $this->getSignatureStarter($pdfContent, $certObj);
        $signatureParams = $signatureStarter->start();
        $signatureStarter->getCertificateInfo();
        return $this->getSignatureFinisher($signatureParams, $certObj);
    }
}
