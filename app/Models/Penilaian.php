<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'sesi_penilaian_id',
        'nama',
        'jenis',
        'petunjuk',
        'konten',
        'file_pdf',
        'durasi_menit',
        'urutan',
        'aktif',
        'model_in_tray'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'durasi_menit' => 'integer',
        'urutan' => 'integer',
    ];

    // Relationships
    public function sesiPenilaian(): BelongsTo
    {
        return $this->belongsTo(SesiPenilaian::class);
    }

    public function itemPenilaian(): HasMany
    {
        return $this->hasMany(ItemPenilaian::class);
    }

    public function assessmentParticipants(): HasMany
    {
        return $this->hasMany(AssessmentParticipant::class);
    }

    public function assignedPeserta()
    {
        return $this->assessmentParticipants()
                   ->with(['peserta', 'sesiPenilaian'])
                   ->orderBy('created_at');
    }

    public function sesiAssessments(): HasMany
    {
        return $this->hasMany(SesiAssessment::class);
    }

    public function activeSessions()
    {
        return $this->sesiAssessments()
                   ->with('sesiPenilaian')
                   ->aktif()
                   ->ordered();
    }

    public function jawabanStudiKasus(): HasMany
    {
        return $this->hasMany(JawabanStudiKasus::class);
    }

    public function jawabanInTray(): HasMany
    {
        return $this->hasMany(JawabanInTray::class);
    }

    public function latihanInTray(): HasMany
    {
        return $this->hasMany(LatihanInTray::class)->whereNull('deleted_at');
    }

    public function catatanRoleplay(): HasMany
    {
        return $this->hasMany(CatatanRoleplay::class);
    }

    public function catatanFgd(): HasMany
    {
        return $this->hasMany(CatatanFgd::class);
    }

    public function kemajuanPenilaian(): HasMany
    {
        return $this->hasMany(KemajuanPenilaian::class);
    }

    // Methods
    public function isActive(): bool
    {
        return $this->sesiPenilaian->isActive() && $this->aktif;
    }

    public function getRemainingTime(): ?int
    {
        return $this->sesiPenilaian->getRemainingTime();
    }

    // Accessors
    public function getJenisTextAttribute(): string
    {
        return match($this->jenis) {
            'studi_kasus' => 'Studi Kasus',
            'in_tray' => 'In-Tray Exercise',
            'roleplay' => 'Role-Play',
            'fgd' => 'LGD',
            default => 'Unknown'
        };
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    // Methods
    public function getNextAssessment()
    {
        return static::where('sesi_penilaian_id', $this->sesi_penilaian_id)
            ->where('urutan', '>', $this->urutan)
            ->ordered()
            ->first();
    }

    public function getPreviousAssessment()
    {
        return static::where('sesi_penilaian_id', $this->sesi_penilaian_id)
            ->where('urutan', '<', $this->urutan)
            ->orderBy('urutan', 'desc')
            ->first();
    }

    public function isFirstAssessment(): bool
    {
        return $this->urutan == 1;
    }

    public function isLastAssessment(): bool
    {
        $maxUrutan = static::where('sesi_penilaian_id', $this->sesi_penilaian_id)->max('urutan');
        return $this->urutan == $maxUrutan;
    }

    // Constants for in-tray models
    const MODEL_URUTAN = 'urutan';
    const MODEL_PRIORITAS = 'prioritas';

    // Get in-tray model options
    public static function getInTrayModelOptions()
    {
        return [
            self::MODEL_URUTAN => 'Model Urutan (Drag-Drop)',
            self::MODEL_PRIORITAS => 'Model Prioritas (4 Kategori)',
        ];
    }

    // Check if using priority model for in-tray
    public function isInTrayPriorityModel()
    {
        return $this->jenis === 'in_tray' && $this->model_in_tray === self::MODEL_PRIORITAS;
    }

    // Check if using order model for in-tray
    public function isInTrayOrderModel()
    {
        return $this->jenis === 'in_tray' && $this->model_in_tray === self::MODEL_URUTAN;
    }
}
