<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;    // Garante que o model Exam está sendo usado
use App\Models\Report;  // Garante que o model Report está sendo usado
use Illuminate\Support\Facades\Storage; // Para acessar o arquivo PDF
use Illuminate\Support\Facades\Http;   // Para fazer requisições HTTP para a API da IA
use Smalot\PdfParser\Parser; // Importa a biblioteca PDF Parser
use Exception; // Para capturar exceções gerais

class ExameLaudoController extends Controller
{
    /**
     * Exibe uma listagem de todos os laudos.
     * Este é o método que será chamado pela rota 'laudos.index'.
     */
    public function index()
    {
        // Busca todos os laudos do banco de dados para exibição
        $laudos = Report::all(); // Assumindo que você tem um modelo Report e a tabela correspondente

        // **** CORREÇÃO AQUI: APONTANDO PARA 'reports.index' ****
        return view('reports.index', compact('laudos'));
    }


    /**
     * Este método é para a rota GET /laudo/{id}.
     * Se você o usava para exibir um laudo já existente, pode buscar do BD.
     * Atualmente, ele retorna um 404 para indicar que a nova funcionalidade deve ser usada.
     *
     * @param int $id O ID do laudo ou exame.
     * @return \Illuminate\Http\Response
     */
    public function gerarLaudo($id)
    {
        // Exemplo: Se este método for para exibir um laudo já gerado.
        // $report = Report::find($id);
        // if (!$report) {
        //     abort(404, 'Laudo não encontrado.');
        // }
        // return view('reports.show', compact('report')); // Você precisaria criar 'reports.show.blade.php'

        // Para o contexto atual do projeto, redirecionamos para o novo método.
        abort(404, 'Método de geração de laudo antigo. Use a nova funcionalidade de geração de laudo automatizado.');
    }

    /**
     * Gera um laudo automatizado para um exame usando a API do Gemini (IA).
     * Mapeado para a rota POST /exames/{exam}/gerar-laudo (nome da rota: exams.generate_report).
     *
     * @param  \Illuminate\Http\Request  $request O objeto da requisição.
     * @param  \App\Models\Exam  $exam O modelo do Exame injetado automaticamente pelo Laravel.
     * @return \Illuminate\Http\RedirectResponse Redireciona com uma mensagem de sucesso ou erro.
     */
    public function gerarLaudoAutomotizado(Request $request, Exam $exam)
    {
        // Inicializa $report como null para garantir que ela exista caso ocorra um erro
        // antes de sua atribuição e o bloco catch seja executado.
        $report = null;

        try {
            // 1. Acessar e Extrair Texto do Arquivo PDF do Exame
            // Obtém o caminho absoluto para o arquivo PDF armazenado.
            $pdfPath = Storage::disk('public')->path($exam->file_path);

            // Verifica se o arquivo PDF realmente existe no sistema de arquivos.
            if (!file_exists($pdfPath)) {
                throw new Exception('Arquivo PDF do exame não encontrado no diretório de armazenamento.');
            }

            // Inicializa o parser de PDF.
            $parser = new Parser();
            // Faz o parse do arquivo PDF para extrair seu conteúdo.
            $pdf = $parser->parseFile($pdfPath);
            // Extrai todo o texto contido no PDF.
            $text = $pdf->getText();

            // Limpeza básica do texto: remove múltiplos espaços em branco e espaços no início/fim.
            // Isso ajuda a otimizar o texto para o envio à IA.
            $cleanedText = preg_replace('/\s+/', ' ', trim($text));

            // Verifica se algum texto relevante foi extraído do PDF.
            if (empty($cleanedText)) {
                throw new Exception('Não foi possível extrair texto relevante do arquivo PDF. O arquivo pode estar vazio ou ser apenas imagem.');
            }

            // 2. Construir o Prompt para a Inteligência Artificial (Gemini)
            // Este prompt direciona a IA para gerar um laudo profissional com base nos dados.
            $prompt = "Você é um assistente especializado em fisioterapia respiratória. Analise os resultados de espirometria abaixo e gere um laudo médico conciso e profissional, com as principais métricas e uma interpretação básica. Se os dados estiverem incompletos ou ilegíveis, mencione isso no laudo. Aqui estão os resultados do exame:\n\n" . $cleanedText;

            // 3. Fazer a Requisição para a API do Gemini
            $apiKey = env('GEMINI_API_KEY'); // Obtém a chave da API do arquivo .env
            // URL do endpoint da API do Gemini para geração de conteúdo.
            // Utilizamos 'gemini-1.5-flash' como modelo padrão, mas pode ser alterado para 'gemini-pro', etc.
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

            // Prepara o payload (corpo da requisição) no formato esperado pela API do Gemini.
            $payload = [
                'contents' => [
                    [
                        'role' => 'user', // Indica que o conteúdo é do usuário
                        'parts' => [
                            ['text' => $prompt] // O texto do prompt
                        ]
                    ]
                ],
                // generationConfig pode ser adicionado aqui para controlar parâmetros como temperatura, top_p, etc.
                // Exemplo:
                // 'generationConfig' => [
                //     'temperature' => 0.7,
                //     'maxOutputTokens' => 1500,
                // ]
            ];

            // Realiza a requisição HTTP POST para a API do Gemini.
            // Define um timeout de 60 segundos para a requisição.
            // ->withoutVerifying() é usado para ignorar problemas de certificado SSL LOCALMENTE.
            // ATENÇÃO: ISTO É APENAS PARA TESTES LOCAIS E NÃO DEVE SER USADO EM PRODUÇÃO!
            $aiResponse = Http::timeout(60)->withoutVerifying()->post($apiUrl, $payload)->json();

            // 4. Processar a Resposta da IA
            // Tenta extrair o conteúdo do laudo da estrutura de resposta do Gemini.
            // Se a estrutura for inesperada ou o conteúdo estiver vazio, define uma mensagem padrão.
            $reportContent = $aiResponse['candidates'][0]['content']['parts'][0]['text']
                             ?? 'Não foi possível gerar o laudo. Resposta da IA vazia ou estrutura inesperada.';

            // Verifica se o conteúdo do laudo gerado é válido.
            if (empty($reportContent) || str_contains($reportContent, 'Não foi possível gerar o laudo.')) {
                 throw new Exception('A API da IA não retornou um laudo válido. Verifique o prompt ou a resposta da API.');
            }

            // 5. Salvar o Laudo no Banco de Dados
            // Cria um novo registro na tabela 'reports' com o laudo gerado.
            $report = Report::create([ // Armazena a instância do laudo criado
                'exam_id' => $exam->id,             // Associa o laudo ao exame original
                'report_content' => $reportContent, // O texto do laudo gerado pela IA
                'generation_date' => now(),         // Registra a data e hora da geração
            ]);

            // Redireciona de volta para a página de visualização do laudo recém-gerado.
            // Utiliza o ID do $report criado.
            return redirect()->route('reports.show', $report->id)->with('success', 'Laudo gerado com sucesso para o exame ' . $exam->original_filename . '!');

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Captura erros específicos de requisições HTTP (ex: 4xx, 5xx da API).
            // Isso pode incluir problemas com a API Key, limites de uso, etc.
            $errorMessage = 'Erro na comunicação com a API da IA: ' . ($e->response ? $e->response->body() : $e->getMessage());
            \Log::error($errorMessage); // Loga o erro completo para depuração
            // Retorna para a página anterior com uma mensagem de erro genérica, sem usar $report.
            return back()->with('error', 'Erro na API da IA. Verifique sua chave, conexão ou limites de uso. Detalhes no log.');
        } catch (Exception $e) {
            // Captura outras exceções gerais (ex: arquivo não encontrado, problemas de PDF Parser).
            \Log::error('Erro ao gerar laudo para exame ' . $exam->id . ': ' . $e->getMessage());
            // Retorna para a página anterior com uma mensagem de erro genérica, sem usar $report.
            return back()->with('error', 'Ocorreu um erro ao gerar o laudo: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o conteúdo de um laudo específico.
     * Mapeado para GET /laudos/{report} (nome da rota: reports.show).
     *
     * @param  \App\Models\Report  $report O modelo Report injetado automaticamente pelo Laravel.
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function showReport(Report $report)
    {
        // O Laravel com Route Model Binding já trata o caso de laudo não encontrado (404).
        // Apenas passa o laudo para a view.
        return view('reports.show', compact('report'));
    }
}
