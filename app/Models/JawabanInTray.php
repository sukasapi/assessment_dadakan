<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanInTray extends Model
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

    public function prioritasMemo()
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

    // Check if memo is completed (both disposisi and prioritas are filled)
    public function isCompleted()
    {
        $hasDisposisi = !empty(trim($this->disposisi));
        
        if ($this->isPriorityModel()) {
            // For priority model, check if prioritas is selected
            $hasPrioritas = $this->prioritasMemo && !empty($this->prioritasMemo->kategori_prioritas);
        } else {
            // For order model, check if urutan_prioritas is set (should be > 0)
            $hasPrioritas = $this->urutan_prioritas > 0;
        }
        
        return $hasDisposisi && $hasPrioritas;
    }

    // Get completion status for display
    public function getCompletionStatus()
    {
        if ($this->isCompleted()) {
            return 'completed';
        }
        
        $hasDisposisi = !empty(trim($this->disposisi));
        
        if ($this->isPriorityModel()) {
            $hasPrioritas = $this->prioritasMemo && !empty($this->prioritasMemo->kategori_prioritas);
        } else {
            $hasPrioritas = $this->urutan_prioritas > 0;
        }
        
        if ($hasDisposisi && !$hasPrioritas) {
            return 'partial_disposisi';
        } elseif (!$hasDisposisi && $hasPrioritas) {
            return 'partial_prioritas';
        } else {
            return 'not_started';
        }
    }
}
