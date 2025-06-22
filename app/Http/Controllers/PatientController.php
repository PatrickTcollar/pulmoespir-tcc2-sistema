<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Exam;
use App\Models\Report;


class PatientController extends Controller
{
    /**
     * Exibe uma listagem de todos os pacientes.
     */
    public function index()
    {
        $pacientes = Patient::all();
        return view('patients.index', compact('pacientes'));
    }

    /**
     * Exibe o formulário para criar um novo paciente.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Armazena um paciente recém-criado no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender'     => 'required|in:M,F', // CORREÇÃO AQUI: Removido 'O'
        ]);

        Patient::create($validated);

        return redirect()->route('pacientes.index')->with('success', 'Paciente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(?Patient $paciente)
    {
        if (is_null($paciente)) {
            abort(404, 'Paciente não encontrado.');
        }
        return view('patients.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $paciente
     * @return \Illuminate\Http\Response
     */
    public function edit(?Patient $paciente)
    {
        if (is_null($paciente)) {
            abort(404, 'Paciente não encontrado para edição.');
        }
        return view('patients.edit', compact('paciente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $paciente)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender'     => 'required|in:M,F', // CORREÇÃO AQUI: Removido 'O'
        ]);

        $paciente->update($validated);

        return redirect()->route('pacientes.index')->with('success', 'Paciente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $paciente)
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')->with('success', 'Paciente excluído com sucesso!');
    }
}
