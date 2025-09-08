<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentParticipant extends Model
{
    use HasFactory;

    protected $table = 'assessment_participant';
    
    protected $fillable = [
        'sesi_penilaian_id',
        'peserta_id',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'catatan_admin'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Get the session that owns this participant
     */
    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiPenilaian::class, 'sesi_penilaian_id');
    }

    /**
     * Get the participant details
     */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    /**
     * Check if participant has started
     */
    public function hasStarted(): bool
    {
        return !is_null($this->waktu_mulai);
    }

    /**
     * Check if participant has completed
     */
    public function hasCompleted(): bool
    {
        return !is_null($this->waktu_selesai);
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
            'selesai' => 'Selesai',
            default => 'Tidak Diketahui'
        };
    }
}
