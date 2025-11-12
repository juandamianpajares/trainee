<p>Hola {{ $trainee->name }},</p>
<p>Usá este enlace para acceder al panel (válido {{ env('MAGIC_LINK_EXPIRE_MINUTES',15) }} minutos):</p>
<p><a href="{{ $link }}">{{ $link }}</a></p>
<p>Saludos, BITNET</p>
