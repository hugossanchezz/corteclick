@extends('layouts.landing')

@section('title', 'Corteclick')

@section('meta')
    {{-- Otras etiquetas meta específicas para la landing
    (consultar views/layouts/landing.blade.php antes de añadir una nueva) --}}
@endsection

@section('content')
    <section id="hero" class="hero-section">
        <h1>El Titular Principal y Llamativo</h1>
        <p>Una breve descripción convincente de tu producto o servicio.</p>
        <button class="call-to-action">¡Empieza Ahora!</button>
    </section>

    <section id="features" class="features-section">
        <h2>Características Asombrosas</h2>
        {{-- Lista de características --}}
    </section>

    <section id="contact" class="contact-section">
        <h2>¡Contáctanos!</h2>
        {{-- Formulario de contacto --}}
    </section>
@endsection

@section('footer')
    {{-- Aquí va el contenido específico del footer de tu landing --}}
    <p>&copy; {{ date('Y') }} Tu Empresa</p>
@endsection

@section('scripts')
    {{-- Aquí puedes añadir scripts específicos para la landing page --}}
    <script>
        console.log('Scripts específicos de la landing page');
    </script>
@endsection
