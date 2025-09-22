<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InTrayAnswer extends Model
{
    use HasFactory;

    protected $table = 'jawaban_in_tray';
    
    protected $fillable = [
        'peserta_id',
        'penilaian_id',
        'sesi_penilaian_id',
        'latihan_in_tray_id',
        'urutan_prioritas',
        'disposisi',
        'status',
        'waktu_simpan',
        'model_assessment',
        'pertanyaan',
        'jawaban_pertanyaan'
    ];

    protected $casts = [
        'waktu_simpan' => 'datetime',
        'urutan_prioritas' => 'integer',
    ];

    // Relationships
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function latihanInTray(): BelongsTo
    {
        return $this->belongsTo(LatihanInTray::class);
    }

    public function prioritasMemo(): HasOne
    {
        return $this->hasOne(PrioritasMemo::class);
    }

    // Constants for assessment models
    const MODEL_URUTAN = 'urutan';
    const MODEL_PRIORITAS = 'prioritas';

    // Get assessment model options
    public static function getModelOptions()
    {
        return [
            self::MODEL_URUTAN => 'Model Urutan (Drag-Drop)',
            self::MODEL_PRIORITAS => 'Model Prioritas (4 Kategori)',
        ];
    }

    // Check if using priority model
    public function isPriorityModel()
    {
        return $this->model_assessment === self::MODEL_PRIORITAS;
    }

    // Check if using order model
    public function isOrderModel()
    {
        return $this->model_assessment === self::MODEL_URUTAN;
    }

    // Get answer display format
    public function getAnswerDisplayAttribute()
    {
        if ($this->isPriorityModel()) {
            $prioritas = $this->prioritasMemo;
            if ($prioritas) {
                return "memo-{$this->latihan_in_tray_id} ({$prioritas->priority_label})";
            }
            return "memo-{$this->latihan_in_tray_id} (belum dipilih)";
        } else {
            return "memo-{$this->latihan_in_tray_id}";
        }
    }

    // Get question answer if available
    public function getQuestionAnswerAttribute()
    {
        return $this->jawaban_pertanyaan ?? 'Tidak ada jawaban';
    }

    // Check if has question
    public function hasQuestion(): bool
    {
        return !empty($this->pertanyaan);
    }

    // Check if has answer
    public function hasAnswer(): bool
    {
        return !empty($this->jawaban_pertanyaan);
    }
}
