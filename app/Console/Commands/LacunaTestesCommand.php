<?php

namespace App\Console\Commands;

use App\Models\Arquivo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Lacuna\RestPki\Authentication;
use Lacuna\RestPki\PadesMeasurementUnits;
use Lacuna\RestPki\PadesSignatureFinisher2;
use Lacuna\RestPki\PadesSignatureStarter;
use Lacuna\RestPki\RestPkiClient;
use Lacuna\RestPki\RestPkiException;
use Lacuna\RestPki\StandardSecurityContexts;
use Lacuna\RestPki\StandardSignaturePolicies;


class LacunaTestesCommand extends Command
{
    protected $signature = 'app:lacuna-testes';

    protected $description = 'Testes do Lacuna Software';

    public function handle()
    {
        try {


            $client = $this->getRestPkiClient();
//
            //$certificate = Storage::disk('local')->get('up-leg-certificate.pfx');
            $certStore = file_get_contents("storage/app/private/up-leg-certificate.pfx");
            $res = openssl_cms_read($certStore, $certObj);
            dd($res);

//
            $arquivo = Arquivo::find('9dea0cfe-9cab-4496-a335-9d231e00f5cb');
            $pdf = Storage::get($arquivo->getArquivoWithBucket());
//
            $auth = new Authentication($client);
//
//
            $token = $auth->startWithWebPki($this->getSecurityContextId());


            $signatureStarter = new PadesSignatureStarter($this->getRestPkiClient());
            $signatureStarter->setPdfToSignFromContentRaw($pdf);
            $signatureStarter->setSignerCertificateRaw($certObj['cert']);
            dd($signatureStarter);



//            $signatureStarter = new PadesSignatureStarter($client);
//            $signatureStarter->signaturePolicy = StandardSignaturePolicies::PADES_BASIC;
//            $signatureStarter->securityContext = StandardSecurityContexts::PKI_BRAZIL;
//            $signatureStarter->measurementUnits = PadesMeasurementUnits::CENTIMETERS;
//
//            $signatureStarter->setPdfToSignFromContentRaw($pdf);
//            $signatureStarter->setSignerCertificateRaw($certificate);
//            $params = $signatureStarter->start();
//
//            openssl_sign($params->toSignData, $signature, 'eiyRuWPja5Zgfb', $params->openSslSignatureAlgorithm);


            //dd($params);


        } catch (RestPkiException $ex) {

            dd($ex);



//        $certificado = Storage::disk('local')->get('up-leg-certificate.pfx');
//
//        openssl_pkcs12_read($certificado, $certObj, "eiyRuWPja5Zgfb");
//        dd($certObj);

        }
    }

    private function getRestPkiClient(): RestPkiClient
    {
        $pkiRestToken = env("LACUNA_REST_PKI_ACCESS_TOKEN");
        return new RestPkiClient("https://pki.rest/", $pkiRestToken);
    }

    private function getSecurityContextId(): string
    {

        /*
         * Lacuna Text PKI (for development purposes only!)
         *
         * This security context trusts ICP-Brasil certificates as well as certificates on Lacuna Software's
         * test PKI. Use it to accept the test certificates provided by Lacuna Software, uncomment the following
         * line.
         *
         * THIS SHOULD NEVER BE USED ON A PRODUCTION ENVIRONMENT!
         * For more information, see https://github.com/LacunaSoftware/RestPkiSamples/blob/master/TestCertificates.md
         */
        //return StandardSecurityContexts::LACUNA_TEST;

        // In production, accept only certificates from ICP-Brasil.
        return StandardSecurityContexts::PKI_BRAZIL;
    }
}
