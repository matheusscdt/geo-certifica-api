<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Código de Autenticação</title>
</head>
<body style="box-sizing: border-box;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;"
>
<div style="box-sizing: border-box;
          padding: 0;
          width: 100%;
          max-width: 650px;
          margin: 20px;
          background-color: #f8f8f8;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
          overflow: hidden;"
>
    <div style="box-sizing: border-box;
            margin: 0;
            background: linear-gradient(180deg, #39b19b 0%, #39b494 100%);
            color: white;
            padding: 40px 30px 30px;
            position: relative;"
    >
        <img src="{{ getBaseUrlFrontEnd().'/images/shapes.svg' }}" alt="Formas" style="box-sizing: border-box;
        margin: 0;
        padding: 0;
        position: absolute;
        top: 9px;
        right: 0;"
        />
        <div style="box-sizing: border-box;
              padding: 0;
              display: flex;
              align-items: center;
              gap: 8px;
              margin-bottom: 25px;
              align-self: start;"
        >
            <img src="{{ getBaseUrlFrontEnd().'/images/icon-certo.svg' }}" alt="Logo GeoCertifica" style="box-sizing: border-box;
        margin: 0;
        padding: 0;">
            <span style="box-sizing: border-box;
                margin: 0;
                padding: 0;font-family: Tahoma, sans-serif;
                font-optical-sizing: auto;
                font-style: normal; font-weight: 400; color: #fff;
                font-size: 1.365rem;
                line-height: 1.365rem;
                text-underline-position: from-font;
                text-decoration-skip-ink: none;"
            >
            GeoCertifica
          </span>
        </div>
        <h1 style="box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Tahoma, sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-weight: 700;
            color: #fff;
            font-size: 1.875rem;
            line-height: 2.25rem;"
        >
            Solicitação de Mudar Senha
        </h1>
    </div>
    <div style="box-sizing: border-box;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 65px 30px;
            text-align: center;"
    >
        <p style="box-sizing: border-box;
            margin: 0;
            padding: 0;text-align: start; align-self: start; font-family: Tahoma, sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
             color: #374151;
            font-size: 1rem;
            line-height: 1.5rem;
            font-weight: 400;"
        >
            Olá, {{ $user->pessoa->nome }}!<br /><br />

            Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.
        </p>
        <a href="{{ $link }}" target="_blank" style="box-sizing: border-box;
              padding: 0;
              border: none;
              background-color: #13c296;
              border-radius: 6px;
              max-width: 350px;
              width: 100%;
              height: 50px;
              margin-top: 30px;
              margin-bottom: 65px;
              cursor: pointer;
              font-family: Tahoma, sans-serif;
              font-optical-sizing: auto;
              font-style: normal;
              color: #fff;
              font-size: 1rem;
              line-height: 1.5rem;
              font-weight: 400;
              display: flex;
              justify-content: center;
              align-items: center;
              text-decoration: none;">
            Atualizar Senha
        </a>
        <p style="box-sizing: border-box;
            margin: 0;
            padding: 0;text-align: start;
            align-self: start;
            font-family: Tahoma, sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            color: #9ca3af;
            font-size: 1rem;
            line-height: 1.5rem;
            font-weight: 400;"
        >
            Este link de redefinição de senha expirará em 60 minutos. <br /><br />

            Se você não solicitou a redefinição de senha, nenhuma ação adicional será necessária. <br /><br />

            Se essa solicitação não foi feita por você, alguém está tentando mudar
            sua senha.
        </p>
        <div style="box-sizing: border-box;
              margin: 0;
              padding: 0;
              align-self: start;"
        >
            <div style="box-sizing: border-box;
                padding: 0;
                margin-top: 20px;
                display: flex;
                justify-content: start;
                align-items: center;
                gap: 8px;
                margin-bottom: 8px;"
            >
                <img src="{{ getBaseUrlFrontEnd().'/images/icon-certo.svg' }}" width="35" height="35" alt="Logo GeoCertifica" style="box-sizing: border-box;margin: 0;padding: 0;">
                <span style="box-sizing: border-box;
                  margin: 0;
                  padding: 0;
                  font-family: Tahoma, sans-serif;
                  font-optical-sizing: auto;
                  font-style: normal;
                  font-size: 1.875rem;
                  line-height: 2.25rem;
                  font-weight: 400;
                  color: #374151;"
                >
              GeoCertifica
            </span>
            </div>
            <span style="box-sizing: border-box;
                margin: 0;
                padding: 0;
                font-family: Tahoma, sans-serif;
                font-optical-sizing: auto;
                font-style: normal;
                font-size: 1rem;
                line-height: 1.5rem;
                font-weight: 400;
                color: #9ca3af;"
            >
            Sua plataforma para assinaturas digitais seguras
          </span>
        </div>
        <div style="box-sizing: border-box;
              padding: 0;
              display: flex;
              align-items: end;
              margin-top: 40px;"
        >
          <span style="box-sizing: border-box;
                padding: 0;
                font-family: Tahoma, sans-serif;
                font-optical-sizing: auto;
                font-style: normal;
                font-size: 1rem;
                line-height: 1.5rem;
                font-weight: 400;
                color: #667085;
                margin-right: 6px"
          >
            Uma solução
          </span>
            <img src="{{ getBaseUrlFrontEnd().'/images/logo-imagetech.svg' }}" alt="Logo ImageTech" style="box-sizing: border-box;margin: 0;padding: 0;">
        </div>
    </div>
</div>
</body>
</html>
