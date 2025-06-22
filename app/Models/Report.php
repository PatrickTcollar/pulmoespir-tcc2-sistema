<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'report_content',
        'generation_date',
        'patient_id',
    ];

    protected $casts = [
        'generation_date' => 'datetime', // CORREÇÃO AQUI: De 'date' para 'datetime'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
}
