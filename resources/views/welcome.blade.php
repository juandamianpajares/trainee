<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BITNET Trainee PRO</title>
  <link href="/css/app.css" rel="stylesheet">
</head>
<body class="bg-slate-50">
<div class="max-w-4xl mx-auto p-8">
  <h1 class="text-4xl font-bold mb-4">BITNET Trainee PRO — Laravel 15D</h1>
  <p class="mb-6">Formación intensiva y certificación. Register below.</p>
  @if(session('success'))<div class="p-3 bg-green-100 mb-4">{{ session('success') }}</div>@endif
  <form action="{{ route('trainees.store') }}" method="POST" class="space-y-3 bg-white p-6 rounded shadow">
    @csrf
    <input name="name" placeholder="Nombre" class="w-full p-2 border rounded">
    <input name="email" placeholder="Correo" class="w-full p-2 border rounded">
    <input name="github" placeholder="GitHub" class="w-full p-2 border rounded">
    <textarea name="motivation" placeholder="Motivación" class="w-full p-2 border rounded"></textarea>
    <button class="px-4 py-2 bg-orange-500 text-white rounded">Enviar</button>
  </form>
  <hr class="my-6">
  <h2 class="text-2xl">Acceso Trainee</h2>
  <form action="{{ route('login.post') }}" method="POST" class="mt-3">
    @csrf
    <input name="email" placeholder="Tu correo registrado" class="p-2 border rounded">
    <button class="px-3 py-1 bg-sky-600 text-white rounded">Entrar (MVP)</button>
  </form>
</div>
</body>
</html>
