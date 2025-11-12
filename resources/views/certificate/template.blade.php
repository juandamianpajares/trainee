<!doctype html>
<html>
<head><meta charset="utf-8"><title>Certificado</title></head>
<body style="font-family: DejaVu Sans, sans-serif; text-align:center; padding:50px;">
  <h1>Certificado BITNET Trainee</h1>
  <p>Se certifica que <strong>{{ $trainee->name }}</strong></p>
  <p>Completó el programa BITNET Trainee - Laravel 15D</p>
  <p>Código: <strong>{{ $trainee->certificate_code }}</strong></p>
  <p>{{ now()->toDateString() }}</p>
</body>
</html>
