<!doctype html>
<html><head><meta charset="utf-8"><title>Verificar Certificado</title></head><body style="font-family:Arial;text-align:center;padding:40px;"><h1>Certificado válido ✅</h1><p><strong>{{ $trainee->name }}</strong></p><p>Código: {{ $certificate->code }}</p><p>Emitido el: {{ $certificate->issued_at->format('d/m/Y') }}</p></body></html>
