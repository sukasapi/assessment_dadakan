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
        'durasi_menit',
        'urutan',
        'aktif'
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

    public function jawabanStudiKasus(): HasMany
    {
        return $this->hasMany(JawabanStudiKasus::class);
    }

    public function jawabanInTray(): HasMany
    {
        return $this->hasMany(JawabanInTray::class);
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
            'fgd' => 'FGD',
            default => 'Unknown'
        };
    }
}
