<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Convite para Cadastro</title>
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
                                <td style="display: flex; align-items: center; gap: 8px; margin-bottom: 25px;">
                                    <img src="{{ getBaseUrlFrontEnd().'/images/icon-certo.svg' }}" alt="Logo GeoCertifica" style="margin: 0; padding: 0;">
                                    <span style="font-family: Tahoma, sans-serif; font-weight: 400; color: #fff; font-size: 1.365rem; line-height: 1.365rem;">GeoCertifica</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h1 style="font-family: Tahoma, sans-serif; font-weight: 700; color: #fff; font-size: 1.875rem; line-height: 2.25rem; margin: 0; padding: 0;">
                                        Convite para Cadastro
                                    </h1>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 30px; text-align: center;">
                        <p style="text-align: start; font-family: Tahoma, sans-serif; color: #374151; font-size: 1rem; line-height: 1.5rem; font-weight: 400; margin: 0; padding: 0;">
                            Olá, <span id="username-full">{{ $convite->nome }}</span>, tudo bem?
                        </p>
                        <br />
                        <p style="text-align: start; font-family: Tahoma, sans-serif; color: #374151; font-size: 1rem; line-height: 1.5rem; font-weight: 400; margin: 0; padding: 0;">
                            Você foi convidado a se cadastrar no nosso sistema.
                        </p>
                        <br />
                        <p style="text-align: start; font-family: Tahoma, sans-serif; color: #374151; font-size: 1rem; line-height: 1.5rem; font-weight: 400; margin: 0; padding: 0;">
                            Clique no botão abaixo para criar sua conta e começar a usar nossos serviços.
                        </p>
                        <div style="margin-top: 20px; margin-bottom: 30px;">
                            <a href="{{ getBaseUrlFrontEnd().'/criar-conta/'.$convite->id }}" target="_blank" style="padding: 5px; border: none; background-color: #13c296; border-radius: 6px; max-width: 350px; width: 100%; height: 50px; margin: 10px auto 0 auto; cursor: pointer; font-family: Tahoma, sans-serif; color: #fff; font-weight: bolder; font-size: 1rem; line-height: 1.5rem; display: flex; justify-content: center; align-items: center; text-decoration: none;">
                                Criar Conta
                            </a>
                        </div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 50%; text-align: left; vertical-align: top; border-right: 1px solid #d1d5db; padding-right: 10px">
                                    <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="display: flex; justify-content: start; align-items: center; gap: 8px; margin-top: 5px; margin-bottom: 8px;">
                                                <img src="{{ getBaseUrlFrontEnd().'/images/icon-certo.svg' }}" width="35" height="35" alt="Logo GeoCertifica" style="margin: 0; padding: 0;">
                                                <span style="font-family: Tahoma, sans-serif; font-size: 1.875rem; line-height: 2.25rem; font-weight: 400; color: #374151; margin: 0; padding: 0;">GeoCertifica</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; padding-top: 15px; padding-bottom: 15px">
                                                <span style="font-family: Tahoma, sans-serif; font-size: 1rem; line-height: 1.5rem; font-weight: 400; color: #9ca3af; margin: 0; padding: 0;">Sua plataforma para assinaturas digitais seguras</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 50%; text-align: left; vertical-align: middle">
                                    <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td align="center" style="padding: 0;">
                                                <table role="presentation" style="border-collapse: collapse;">
                                                    <tr>
                                                        <td><span style="font-family: Tahoma, sans-serif; font-size: 1rem; line-height: 1.5rem; font-weight: bolder; color: #667085; margin-right: 6px; padding: 0;">Uma solução</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="display: flex; align-items: end; margin-top: 5px; padding: 0;">
                                                            <img src="{{ getBaseUrlFrontEnd().'/images/logo-imagetech.svg' }}" alt="Logo ImageTech" style="margin: 0; padding: 0;">
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
