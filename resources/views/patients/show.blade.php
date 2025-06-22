@extends('layouts.app') {{-- Assume que você tem um layout base 'layouts.app' --}}

@section('content')
<div class="container mx-auto p-4">
    {{-- Verifica se o objeto $patient existe e não é nulo --}}
    @if ($patient)
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Detalhes do Paciente: {{ $patient->name }}</h2>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="mb-4">
                <strong class="block text-gray-600 text-sm font-bold mb-2">ID:</strong>
                <p class="text-gray-900 text-lg">{{ $patient->id }}</p>
            </div>
            <div class="mb-4">
                <strong class="block text-gray-600 text-sm font-bold mb-2">Nome:</strong>
                <p class="text-gray-900 text-lg">{{ $patient->name }}</p>
            </div>
            <div class="mb-4">
                <strong class="block text-gray-600 text-sm font-bold mb-2">Data de Nascimento:</strong>
                <p class="text-gray-900 text-lg">{{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }}</p>
            </div>
            <div class="mb-4">
                <strong class="block text-gray-600 text-sm font-bold mb-2">Sexo:</strong>
                <p class="text-gray-900 text-lg">{{ $patient->gender }}</p>
            </div>

            {{-- Você pode adicionar outros detalhes do paciente aqui, se houver campos adicionais --}}

        </div>

        <div class="flex space-x-4">
            {{-- Link para editar o paciente (assumindo que você terá um método edit e rota pacientes.edit) --}}
            <a href="{{ route('pacientes.edit', $patient->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out shadow-md">
                Editar Paciente
            </a>
            {{-- Link para voltar para a lista de pacientes --}}
            <a href="{{ route('pacientes.index') }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out shadow-md">
                Voltar para a lista
            </a>
        </div>
    @else
        {{-- Mensagem exibida se o paciente não for encontrado (variável $patient é nula) --}}
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Erro:</strong>
            <span class="block sm:inline">Paciente não encontrado.</span>
            <p class="mt-2"><a href="{{ route('pacientes.index') }}" class="text-red-700 hover:underline">Voltar para a lista de pacientes</a></p>
        </div>
    @endif
</div>
@endsection
