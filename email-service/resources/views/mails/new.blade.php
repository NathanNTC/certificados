<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro realizado com sucesso</title>
</head>
<body>
    <h1>Bem-vindo!</h1>
    <p>Seu cadastro foi realizado com sucesso. Aqui estão suas credenciais:</p>
    <p><strong>E-mail:</strong> {{ $email }}</p>
    <p><strong>Senha:</strong> {{ $password }}</p>
    <p>Por favor, mantenha essas informações em segurança e faça login em nosso site para começar a usar seus serviços.</p>
    <p>Se você tiver alguma dúvida, entre em contato conosco.</p>
    <p>Atenciosamente,</p>
    <p>A equipe</p>
</body>
</html>
