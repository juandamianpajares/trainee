<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Certificado - {{ $trainee->name }}</title><style>body{font-family: DejaVu Sans, sans-serif;text-align:center;padding:50px}.certificate{border:5px solid #0f172a;padding:50px;border-radius:20px}h1{font-size:40px;color:#0f172a}p{font-size:18px}.qr{margin-top:30px}</style></head>
<body><div class="certificate"><h1>Certificado de Finalización</h1><p>Se otorga a</p><h2>{{ $trainee->name }}</h2><p>por haber completado satisfactoriamente el Bootcamp Laravel BitNet Trainee.</p><p><strong>Emitido el:</strong> {{ $certificate->issued_at->format('d/m/Y') }}</p><div class="qr"><img src="data:image/png;base64,{{ $qrImage }}" alt="QR Code"><p>Verificar en: {{ $verifyUrl }}</p></div></div></body></html>
