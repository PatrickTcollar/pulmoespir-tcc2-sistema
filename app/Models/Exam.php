<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'file_path',
        'original_filename',
        'upload_date',
    ];

    protected $casts = [
        'upload_date' => 'datetime', // CORREÇÃO AQUI: De 'date' para 'datetime'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function report()
    {
        return $this->hasOne(Report::class, 'exam_id', 'id');
    }
}
