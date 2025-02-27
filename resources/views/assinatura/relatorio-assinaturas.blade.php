<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Relatório de Assinatura</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: Tahoma, sans-serif;
            color: #495057;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 53%;
            padding: 1rem 2rem 2rem 2rem;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .card-assinatura {
            border: 0.15rem rgba(128, 128, 128, 0.2) solid;
            border-radius: 1rem;
        }

        .assinado {
            border-radius: 0.4rem;
            background-color: #ecfbf7;
            padding: 0.5rem 1.2rem 0.5rem 1.2rem;
            margin-bottom: 0;
        }

        .assinado-empresa {
            display: flex;
            border-radius: 0.4rem;
            background-color: #ecfbf7;
            padding: 0.5rem;
            justify-content: center;
            gap: 0.7rem;
            width: 11rem;
            margin-bottom: 0;
        }

        .campo-assinatura {
            border: 0.2rem #13c296 solid;
            position: relative;
            padding: 0 1.2rem 0 1.2rem;
            border-radius: 0.15rem;
            min-width: 200px;
            height: auto;
            box-sizing: border-box;
            margin-bottom: 1rem;
            margin-right: 0.5cm;
        }

        .assinado-por {
            position: absolute;
            top: -1.8rem;
            left: 0.5rem;
            background-color: white;
            padding: 0 0.5rem 0 0.5rem;
            color: rgba(73, 80, 87, 0.8);
        }

        .assinante {
            font-optical-sizing: auto;
            font-weight: 500;
        }

        .autenticacao {
            display: flex;
            margin-left: 2rem;
            padding: 0 2rem 0 0;
            gap: 0.9rem;
        }

        .email {
            width: 20rem;
        }

        .pessoa-assinante {
            margin-left: 2rem;
            margin-right: 3rem
        }

        .pessoa-assinatura {
            padding-left: 1.5rem;
            border-left: 0.15rem rgba(128, 128, 128, 0.2) solid;
        }

        .footer {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 10pt;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                margin: 0.6cm;
                size: A4;
            }

            .container {
                width: 100%;
                box-shadow: none;
                page-break-inside: avoid;
            }

            .container h1 {
                font-size: 18pt;
                page-break-after: avoid;
            }

            .container h2,
            h3 {
                font-size: 14pt;
                page-break-after: avoid;
            }

            .container p {
                font-size: 10pt;
            }

            .card-assinatura {
                page-break-inside: avoid;
                margin-bottom: 1rem;
            }

            .assinante {
                margin-bottom: 0.7rem;
                margin-top: 0.7rem;
            }

            .assinado-por {
                top: -1.5rem;
            }

            .campo-assinatura {
                padding: 0.1rem 0.8rem 0.1rem 0.8rem;
            }

            .campo-assinatura h3 {
                font-size: 12pt;
            }

            .email {
                width: 16.8rem;
            }

            .footer {
                font-size: 8pt;
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
            }

            img {
                max-width: 100%;
                height: auto;
            }
        }

    </style>
</head>

<body>

<div class="container">
    <div>
        <div
            style="display: flex; position: relative; border-bottom: 0.15rem rgba(128, 128, 128, 0.2) solid; padding-bottom: 1rem;">
            <div>
                <h1 style="font-optical-sizing: auto; font-weight: 500">Relatório de Assinatura</h1>
                <div>
                    <p>Datas e horários em UTC-0300 ( America/Sao_Paulo)</p>
                    <p>Última atualização em 02 Dezembro 2024, 16:00:43</p>
                </div>
            </div>
            <img src="https://geocertifica.dev.grupoimagetech.com.br/images/logo-geocertifica-html.svg" alt="GeoCertifica"
                 style="width: 14rem; position: absolute; top: 1rem; right: 0" />
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 1rem; border-bottom: 0.15rem rgba(128, 128, 128, 0.2) solid; padding-bottom: 1rem; margin-bottom: 2rem;">
            <div>
                <p><strong>Documento:</strong> {{ $registroAssinaturaInterna->arquivoOriginal->nome }}</p>
                <p><strong>Número:</strong> {{ $registroAssinaturaInterna->arquivoOriginal->id }}</p>
                <p><strong>Data da criação:</strong> {{ $registroAssinaturaInterna->arquivoOriginal->created_at->translatedFormat('d F Y, H:i:s') }} </p>
                <p><strong>Hash do documento:</strong> {{ $registroAssinaturaInterna->arquivoOriginal->getHashSha256() }}</p>
            </div>
            {{ $registroAssinaturaInterna->getQrCode() }}
{{--            <img src="https://geocertifica.dev.grupoimagetech.com.br/images/codigo-barras.svg" alt="codigo-barras" />--}}
        </div>
    </div>
    <h1 style="font-optical-sizing: auto; font-weight: 500; margin-bottom: 2rem;">Assinaturas</h1>
    <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1rem;">

        @foreach($registroAssinaturaInterna->documento->destinatarios as $destinatario)
            @if($destinatario->assinatura_realizada)
                <div class="card-assinatura">
                    <div style="display: flex; border-bottom: 0.15rem rgba(128, 128, 128, 0.2) solid;">
                        <div class="pessoa-assinante">
                            <div style="display: flex; gap: 1rem">
                                <p class="assinado">
                                    Assinado
                                </p>
                                <p class="assinado-empresa">
                                    <img src="https://geocertifica.dev.grupoimagetech.com.br/images/secure_svgrepo.svg" alt="" /> via GeoCertifica
                                </p>
                            </div>
                            <div>
                                <h2 style="font-optical-sizing: auto; font-weight: 500; margin-bottom: 0;">{{ $destinatario->agenda->nome }}</h2>
                                <p style="margin: 0.3rem 0 0.4rem 0;">CPF: {{ $destinatario->autorizacaoValida()?->assinatura->cpf }}</p>
                                <p style="margin: 0.3rem 0 0.4rem 0;">Data e hora da assinatura: {{ $destinatario->autorizacaoValida()?->assinatura->data_assinatura->translatedFormat('d F Y, H:i:s') }} (horário de Brasília)</p>
                                <p style="margin: 0 0.5rem 0.5rem 0;">Token: {{ $registroAssinaturaInterna->id }}</p>
                            </div>
                        </div>
                        <div class="pessoa-assinatura">
                            <h3 style="font-optical-sizing: auto; font-weight: 500; margin-bottom: 1.5rem;">Assinatura</h3>
                            <div class="campo-assinatura">
                                <p class="assinado-por">Assinado por:</p>
                                <h3 class="assinante">{{ $destinatario->agenda->nome }}</h3>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex;">
                        <div style="padding-left: 30px; width: 600px">
                            <div>
                                <h2 style="font-optical-sizing: auto; font-weight: 500; margin-bottom: 0;">Autenticação</h2>
                                <p>E-mail: {{ $destinatario->agenda->email }}</p>
                            </div>
                        </div>
                        <div>
                            <div>
                                <p>IP: {{ $destinatario->autorizacaoValida()?->assinatura->ip_address }}</p>
                                <p>Dispositivo: {{ $destinatario->autorizacaoValida()?->assinatura->dispositivo }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            @else
                <div class="card-assinatura">
                    <div style="display: flex;">
                        <div class="pessoa-assinante">
                            <div style="display: flex; gap: 1rem">
                                <p class="assinado">Não Assinou</p>
                            </div>
                            <div>
                                <h2 style="font-optical-sizing: auto; font-weight: 500; margin-bottom: 0;">{{ $destinatario->agenda->nome }}</h2>
                            </div>
                        </div>
                        <div class="pessoa-assinatura">
                            <h3 style="font-optical-sizing: auto; font-weight: 500; margin-bottom: 1.5rem;">Assinatura</h3>
                            <div class="campo-assinatura">
                                <p class="assinado-por">Assinado por:</p>
                                <h3 class="assinante">Assinatura não realizada</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
</body>
