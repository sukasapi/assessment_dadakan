<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class SesiPenilaian extends Model
{
    use HasFactory;

    protected $table = 'sesi_penilaian';

    protected $fillable = [
        'nama',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'catatan',
        'aktif'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'aktif' => 'boolean',
        'durasi_menit' => 'integer',
    ];

    // Relationships
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->aktif;
    }

    public function getRemainingTime(): ?int
    {
        if (!$this->waktu_mulai || $this->status !== 'active') {
            return null;
        }

        $endTime = $this->waktu_selesai ?? $this->waktu_mulai->addMinutes($this->durasi_menit);
        $remaining = $endTime->diffInSeconds(now(), false);
        
        return $remaining > 0 ? $remaining : 0;
    }

    public function startSession(): void
    {
        $this->update([
            'status' => 'active',
            'waktu_mulai' => now(),
            'waktu_selesai' => now()->addMinutes($this->durasi_menit)
        ]);
    }

    public function stopSession(): void
    {
        $this->update([
            'status' => 'completed',
            'waktu_selesai' => now()
        ]);
    }

    // Accessors
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'active' => 'Aktif',
            'paused' => 'Dijeda',
            'completed' => 'Selesai',
            default => 'Unknown'
        };
    }
}
