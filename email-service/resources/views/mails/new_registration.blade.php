<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .content {
            font-size: 16px;
            line-height: 1.5;
            color: #333;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        .highlight {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bem-vindo(a)!</h1>
        </div>
        <div class="content">
            <p>Estamos felizes em tê-lo(a) conosco. Aqui estão suas credenciais de acesso:</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Senha:</strong></p>
            <div class="highlight">{{ $password }}</div>
            <p>Por favor, guarde essas informações com segurança e acesse seu painel usando seu e-mail e senha.</p>
        </div>
        <div class="footer">
            <p>Obrigado por se registrar conosco.</p>
        </div>
    </div>
</body>
</html>
