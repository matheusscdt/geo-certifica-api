<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Documento Assinado e Finalizado</title>
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #f8f8f8;">
<table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8f8f8;">
    <tr>
        <td align="center" style="padding: 20px;">
            <table role="presentation" style="width: 100%; max-width: 650px; border-collapse: collapse; background-color: #ffffff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                <tr>
                    <td style="background-color: #39b19b; color: white; padding: 40px 30px 30px; position: relative; background-image: url('{{ getBaseUrlFrontEnd().'/images/shapes.svg' }}'); background-repeat: no-repeat; background-position: top right;">
                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding-bottom: 25px;">
                                    <img src="{{ getBaseUrlFrontEnd().'/images/icon-certo.svg' }}" alt="Logo GeoCertifica" style="vertical-align: middle;">
                                    <span style="font-family: Tahoma, sans-serif; font-weight: 400; color: #fff; font-size: 1.365rem; line-height: 1.365rem; vertical-align: middle;">GeoCertifica</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h1 style="font-family: Tahoma, sans-serif; font-weight: 700; color: #fff; font-size: 1.875rem; line-height: 2.25rem; margin: 0; padding: 0;">
                                        Documento Assinado e Finalizado
                                    </h1>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 30px; text-align: center;">
                        <p style="text-align: start; font-family: Tahoma, sans-serif; color: #374151; font-size: 1rem; line-height: 1.5rem; font-weight: 400; margin: 0; padding: 0;">
                            Olá, <span id="username-full">{{ $destinatario->agenda->nome }}</span>, tudo bem?
                        </p>
                        <br />
                        <p style="text-align: start; font-family: Tahoma, sans-serif; color: #374151; font-size: 1rem; line-height: 1.5rem; font-weight: 400; margin: 0; padding: 0;">
                            Assinatura do documento foi concluída com sucesso. Seu documento assinado está em anexo, lembre-se de baixá-lo.
                        </p>
                        <br />
                        <p style="text-align: start; font-family: Tahoma, sans-serif; color: #374151; font-weight: bolder; font-size: 1rem; line-height: 1.5rem; margin: 0; padding: 0;">
                            Documentos:
                        </p>
                        <div style="margin-top: 15px; margin-bottom: 15px;">
                            @foreach($destinatario->documento->arquivos as $arquivo)
                                <span id="file-name" style="font-family: Tahoma, sans-serif; color: #374151; font-size: 1rem; line-height: 1.5rem; font-weight: 400;">
                                        {{ $arquivo->nome }}
                                    </span><br />
                            @endforeach
                        </div>
                        <div style="width: 100%; max-width: 330px; text-align: start; font-family: Tahoma, sans-serif; color: #374151; font-size: 1rem; line-height: 1.5rem; font-weight: 400;">
                            <p style="font-weight: bolder; margin: 0; padding: 0;">
                                Assinaturas:
                            </p>
                            <div style="margin-top: 10px; margin-bottom: 20px;">
                                @foreach($destinatario->documento->destinatarios as $destinatario)
                                    <p style="margin: 0; padding: 0;">
                                        <img src="{{ getBaseUrlFrontEnd().'/images/icon-' }}{{ $destinatario->assinatura_realizada ? 'done' : 'clock' }}.svg" alt="{{ $destinatario->assinatura_realizada ? 'Assinado' : 'Não Assinado' }}" style="vertical-align: middle;">
                                        <span id="signer-1" style="color: #100101; vertical-align: middle;">
                                                {{ $destinatario->agenda->nome }} - @if($destinatario->assinatura_realizada) <span style="font-size: 15px; font-weight: bolder; color: #33ae9a">Assinou</span> @else <span style="font-size: 15px; font-weight: bolder; color: #d1b60c">Não Assinou</span> @endif
                                            </span>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 50%; text-align: left; vertical-align: top; border-right: 1px solid #d1d5db; padding-right: 10px;">
                                    <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding-bottom: 8px;">
                                                <img src="{{ getBaseUrlFrontEnd().'/images/icon-certo.svg' }}" width="35" height="35" alt="Logo GeoCertifica" style="vertical-align: middle;">
                                                <span style="font-family: Tahoma, sans-serif; font-size: 1.875rem; line-height: 2.25rem; font-weight: 400; color: #374151; vertical-align: middle;">
                                                        GeoCertifica
                                                    </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                    <span style="font-family: Tahoma, sans-serif; font-size: 1rem; line-height: 1.5rem; font-weight: 400; color: #9ca3af;">
                                                        Sua plataforma para assinaturas digitais seguras
                                                    </span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 50%; text-align: left; vertical-align: middle;">
                                    <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td align="center" style="padding: 0;">
                                                <table role="presentation" style="border-collapse: collapse;">
                                                    <tr>
                                                        <td><span style="font-family: Tahoma, sans-serif; font-size: 1rem; line-height: 1.5rem; font-weight: bolder; color: #667085; margin-right: 6px; padding: 0;">Uma solução</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="display: flex; align-items: end; margin-top: 5px; padding: 0;">
                                                            <img src="{{ getBaseUrlFrontEnd().'/images/logo-imagetech.svg' }}" alt="Logo ImageTech" style="vertical-align: middle;">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
