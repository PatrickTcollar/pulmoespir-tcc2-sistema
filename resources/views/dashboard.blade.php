@extends('layouts.app') {{-- Garante que esta view usa seu layout principal --}}

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-extrabold text-center text-gray-900 mb-6 leading-tight animate-fade-in-down">
                Bem-vindo ao PulmoEspir!
            </h1>
            <p class="text-xl text-center text-gray-600 mb-12 animate-fade-in">
                Seu Assistente Fisioterapeuta Inteligente.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <!-- Cart\u00e3o/Bot\u00e3o: Gerenciar Pacientes -->
                <a href="{{ route('pacientes.index') }}"
                   class="dashboard-card group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-2 p-6 flex flex-col items-center text-center border border-gray-200 w-full">
                    <div class="icon-circle bg-blue-100 text-blue-600 mb-4 p-4 rounded-full group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-user-injured text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-blue-700 transition-colors duration-300">Gerenciar Pacientes</h3>
                    <p class="text-gray-600 text-sm group-hover:text-gray-700">Adicione, edite e visualize os registros dos seus pacientes.</p>
                </a>

                <!-- Cart\u00e3o/Bot\u00e3o: Upload de Exames -->
                <a href="{{ route('exams.upload.form') }}"
                   class="dashboard-card group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-2 p-6 flex flex-col items-center text-center border border-gray-200 w-full">
                    <div class="icon-circle bg-green-100 text-green-600 mb-4 p-4 rounded-full group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-cloud-upload-alt text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-green-700 transition-colors duration-300">Upload de Exames</h3>
                    <p class="text-gray-600 text-sm group-hover:text-gray-700">Envie novos exames de espirometria para an\u00e1lise.</p>
                </a>

                <!-- Cart\u00e3o/Bot\u00e3o: Lista de Exames -->
                <a href="{{ route('exames.index') }}"
                   class="dashboard-card group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-2 p-6 flex flex-col items-center text-center border border-gray-200 w-full">
                    <div class="icon-circle bg-yellow-100 text-yellow-600 mb-4 p-4 rounded-full group-hover:bg-yellow-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-list-alt text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-yellow-700 transition-colors duration-300">Lista de Exames</h3>
                    <p class="text-gray-600 text-sm group-hover:text-gray-700">Navegue por todos os exames j\u00e1 carregados no sistema.</p>
                </a>

                <!-- Cart\u00e3o/Bot\u00e3o: Acessar Laudos -->
                <a href="{{ route('laudos.index') }}"
                   class="dashboard-card group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-2 p-6 flex flex-col items-center text-center border border-gray-200 w-full">
                    <div class="icon-circle bg-purple-100 text-purple-600 mb-4 p-4 rounded-full group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-file-medical-alt text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-purple-700 transition-colors duration-300">Acessar Laudos</h3>
                    <p class="text-gray-600 text-sm group-hover:text-gray-700">Visualize e revise os laudos gerados pela IA.</p>
                </a>

                {{-- Cart\u00e3o/Bot\u00e3o: Evolu\u00e7\u00e3o do Paciente --}}
                <a href="{{ route('evolucao.index') }}"
                   class="dashboard-card group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-2 p-6 flex flex-col items-center text-center border border-gray-200 w-full">
                    <div class="icon-circle bg-teal-100 text-teal-600 mb-4 p-4 rounded-full group-hover:bg-teal-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-chart-line text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-teal-700 transition-colors duration-300">Evolu\u00e7\u00e3o do Paciente</h3>
                    <p class="text-gray-600 text-sm group-hover:text-gray-700">Analise o hist\u00f3rico de exames e a evolu\u00e7\u00e3o ao longo do tempo.</p>
                </a>

                {{-- NOVO CART\u00c3O/BOT\u00c3O: Iniciar Chat sobre Exame --}}
                <a href="{{ route('chat.upload.form') }}"
                   class="dashboard-card group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-2 p-6 flex flex-col items-center text-center border border-gray-200 w-full">
                    <div class="icon-circle bg-pink-100 text-pink-600 mb-4 p-4 rounded-full group-hover:bg-pink-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-comments text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-pink-700 transition-colors duration-300">Chat IA de Exames</h3>
                    <p class="text-gray-600 text-sm group-hover:text-gray-700">Fa\u00e7a upload e converse sobre o exame em tempo real.</p>
                </a>
            </div>
        </div>
    </div>

    <style>
        /* Anima\u00e7\u00e3o de Fade In Down para o t\u00edtulo */
        @keyframes fade-in-down {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.8s ease-out forwards;
        }

        /* Anima\u00e7\u00e3o de Fade In para o par\u00e1grafo */
        @keyframes fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .animate-fade-in {
            animation: fade-in 1s ease-out forwards;
            animation-delay: 0.3s; /* Atraso para aparecer depois do t\u00edtulo */
        }

        /* Anima\u00e7\u00e3o de Scale In para o card de boas-vindas */
        @keyframes scale-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-scale-in {
            animation: scale-in 0.7s ease-out forwards;
            animation-delay: 0.6s; /* Atraso para aparecer depois do par\u00e1grafo */
        }
    </style>
@endsection
