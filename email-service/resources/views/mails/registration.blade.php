<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Realizado com Sucesso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4CAF50;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .body {
            padding: 20px;
            text-align: center;
        }
        .body h1 {
            color: #4CAF50;
            margin-bottom: 10px;
        }
        .body p {
            font-size: 18px;
            line-height: 1.6;
            margin: 10px 0;
        }
        .footer {
            background-color: #f4f4f9;
            color: #888;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            Cadastro Realizado com Sucesso!
        </div>
        <div class="body">
            <h1>Bem-vindo(a)!</h1>
            <p>Seu cadastro foi concluído com sucesso. Estamos muito felizes em ter você conosco!</p>
            <p>Se precisar de ajuda ou mais informações, não hesite em entrar em contato.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} - Eventos Microserviços. Todos os direitos reservados.</p>
            <p><a href="https://seusite.com.br">Visite nosso site</a></p>
        </div>
    </div>
</body>
</html>
