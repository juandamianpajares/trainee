<!doctype html>
<html>
<head><meta charset="utf-8"><title>Panel</title><link href="/css/app.css" rel="stylesheet"></head>
<body class="p-6 bg-gray-50"><div class="container mx-auto"><h1 class="text-2xl font-bold">Bienvenido, {{ $trainee->name }}</h1><p class="mt-2">Progreso: {{ $trainee->progress }}%</p>
<form id="connectForm" method="POST" action="/connect/github">@csrf<input type="hidden" name="trainee_id" value="{{ $trainee->id }}"><input name="repo" placeholder="usuario/repositorio" class="border p-2 rounded"><button class="px-3 py-1 bg-indigo-600 text-white rounded">Conectar GitHub (MVP)</button></form>
@if($trainee->progress>=100)<p class="mt-4"><a href="/certificate/download/{{ $trainee->id }}" class="px-4 py-2 bg-green-600 text-white rounded">Descargar Certificado PDF</a> <a href="/verify/{{ $trainee->certificate_code }}" class="ml-2 text-blue-600 underline">Verificar Online</a></p>@endif</div></body></html>
