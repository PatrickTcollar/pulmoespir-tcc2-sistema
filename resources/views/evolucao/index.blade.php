@extends('layouts.app') {{-- Garante que esta view usa o layout principal --}}

@section('content')
    <div class="container mx-auto p-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Análise de Evolução do Paciente</h2>
        <p class="text-center text-lg text-gray-600 mb-10">
            Selecione um paciente para gerar um laudo de evolução baseado nos seus exames anteriores.
        </p>

        <div class="bg-white shadow-lg rounded-lg p-8 max-w-xl mx-auto">
            <form action="{{ route('evolucao.analyze') }}" method="POST"> {{-- AQUI: método POST --}}
                @csrf

                <div class="mb-6">
                    <label for="patient_id" class="block text-gray-700 text-sm font-bold mb-2">
                        Selecione o Paciente:
                    </label>
                    <select name="patient_id" id="patient_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        <option value="">-- Selecione um Paciente --</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}"
                                {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} (ID: {{ $patient->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <i class="fas fa-magic mr-2"></i> Gerar Laudo de Evolução
                    </button>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-indigo-600 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
