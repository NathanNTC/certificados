<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Certificado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .certificate {
            border: 5px solid #000;
            padding: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
        }
        .details {
            margin: 20px 0;
            font-size: 18px;
        }
        .id {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="title">Certificado de Participação</div>
        <div class="details">
            Certificamos que <strong>{{ $user_name }}</strong> participou do evento <strong>{{ $event_name }}</strong> no dia <strong>{{ $event_date }}</strong>.
        </div>
        <div class="id">Certificado ID: {{ $certificate_id }}</div>
        <img src="data:image/png;base64, {!! base64_encode(QrCode::generate(url("/certificates/validate/{$certificate_id}"))) !!}" alt="QR Code para validação" />
    </div>
</body>
</html>
