<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Laudo;
use Illuminate\Support\Facades\Http;

class AIReportController extends Controller
{
    public function gerarLaudo(Request $request, $examId)
    {
        $exam = Exam::with('patient', 'laudo')->find($examId);

        if (!$exam) {
            return response()->json(['erro' => 'Exame não encontrado.'], 404);
        }

        if ($exam->laudo) {
            return response()->json(['laudo' => $exam->laudo->conteudo], 200);
        }

        // Monta o prompt com dados do exame
        $prompt = $this->montarPrompt($exam);

        // Chama a API do ChatGPT (gpt-4o)
        $resposta = $this->consultarChatGPT($prompt);

        if (!$resposta) {
            return response()->json(['erro' => 'Falha na geração do laudo.'], 500);
        }

        // Salva o laudo no banco
        $laudo = new Laudo();
        $laudo->conteudo = $resposta;
        $laudo->versao_modelo = 'gpt-4o';
        $laudo->exam_id = $exam->id;
        $laudo->save();

        return response()->json(['laudo' => $resposta], 201);
    }

    private function montarPrompt(Exam $exam)
    {
        $paciente = $exam->patient;
        return "Gere um laudo clínico baseado nos seguintes dados:

Paciente: {$paciente->nome}
Nascimento: {$paciente->nascimento}
Sexo: {$paciente->sexo}
Data do exame: {$exam->data}
Tipo de exame: {$exam->tipo}
Observações: {$exam->observacoes}

O laudo deve ser claro, técnico, com linguagem acessível ao fisioterapeuta.
Não diagnostique o paciente, apenas gere um laudo com base nos dados apresentados.";
    }

    private function consultarChatGPT($prompt)
    {
        $apiKey = config('services.openai.api_key');

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um fisioterapeuta especializado em espirometria que gera laudos com base em dados clínicos.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.5,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return null;
    }
}
