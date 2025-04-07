<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Corteclick: Reserva online fácil y rápida en peluquerías y barberías locales. Encuentra, compara precios y agenda tu cita sin llamadas. ¡Digitalizamos el sector!">
    <meta name="keywords"
        content="Corteclick, peluquerías, barberías, reserva online, citas, agenda, belleza, cabello, servicios de peluquería, búsqueda de peluquerías, disponibilidad peluquerías, valoración peluquerías, digitalización peluquerías, reserva de citas online">
    <meta name="author" content="Corteclick (o el nombre de tu empresa/desarrollador)">
    <meta name="robots" content="index, follow">
    <meta name="language" content="es">

    <meta property="og:title" content="Corteclick - Reserva online fácil y rápida en peluquerías y barberías">
    <meta property="og:description"
        content="Encuentra, compara precios y agenda tu cita en peluquerías y barberías locales sin complicaciones. ¡Digitalizamos el sector de la belleza!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}"> {{-- Genera la URL actual de la página dinámicamente --}}
    <meta property="og:site_name" content="Corteclick">
    {{--
    <meta property="og:image" content="{{ asset('images/logo-compartir.png') }}"> --}} {{-- Reemplazar con la URL del logo para compartir en redes sociales --}}

    <title>Corteclick</title>

    {{-- CSS general --}}
    @vite('resources/css/normalize.css')
    @vite('resources/css/app.css')
</head>

<body>
    <div id="app">
        <app>
            {{-- Aquí se montará el componente raíz App.vue --}}
        </app>
    </div>

    {{-- JavaScript --}}
    @vite('resources/js/app.js')
</body>

</html>
