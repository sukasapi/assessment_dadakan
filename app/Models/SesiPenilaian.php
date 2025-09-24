<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    /**
     * Get the assessments for this session (exclude soft deleted)
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(SesiAssessment::class, 'sesi_penilaian_id')
            ->whereNull('deleted_at')
            ->orderBy('urutan');
    }

    /**
     * Get the participants for this session
     */
    public function participants(): HasMany
    {
        return $this->hasMany(AssessmentParticipant::class, 'sesi_penilaian_id');
    } 

    /**
     * Check if session is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if session is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'pending' => 'Menunggu',
            'active' => 'Aktif',
            'paused' => 'Dijeda',
            'completed' => 'Selesai',
            default => 'Tidak Diketahui'
        };
    }
}
