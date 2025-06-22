@extends('layouts.app') {{-- Garante que esta vista usa o layout principal --}}

@section('content')
    <div class="h-screen py-4 px-4 flex items-center justify-center">
        <div id="exam-chat-root" class="w-full max-w-4xl h-full">
            {{-- O aplicativo React ser\u00e1 renderizado aqui --}}
            <p class="text-center text-gray-500">A carregar interface de chat...</p>
        </div>
    </div>

    {{-- Passe o ID do exame e o nome original para o JavaScript/React --}}
    <script>
        window.examId = {{ $exam->id }};
        window.examOriginalFilename = "{{ $exam->original_filename }}";
        // \u00c9 crucial que a meta tag CSRF esteja dispon\u00edvel no HTML.
        // Geralmente est\u00e1 no layouts/app.blade.php
    </script>

    {{-- Inclua o script de jsPDF para gerar o PDF da conversa via CDN --}}
    {{-- MUITO IMPORTANTE: Este script DEVE carregar ANTES do seu app.jsx (React) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    {{-- Inclua o script compilado do seu aplicativo React (onde o 'App.jsx' \u00e9 iniciado) --}}
    @viteReactRefresh {{-- ESTA LINHA DEVE VIR PRIMEIRO --}}
    @vite('resources/js/app.jsx') {{-- SEGUIDA POR ESTA --}}
@endsection
