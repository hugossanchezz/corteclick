<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página No Encontrada</title>

    <style>
        /* Dancing Script, Montserrat Alternate & Montserrat fonts */

        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

    @vite('resources/css/normalize.css')
    @vite('resources/css/app.css')
</head>

<body>
    <div class="not-found__container flex-center">
        <div class="container__modal glass-effect">
            <h1>404 - Página No Encontrada</h1>
            <p>¡Oops! Parece que la página que estás buscando no existe.</p>
            <p>Puedes volver a la <a href="{{ url('/') }}">página principal</a>.</p>
        </div>
    </div>
</body>

</html>
