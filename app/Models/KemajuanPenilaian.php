<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KemajuanPenilaian extends Model
{
    use HasFactory;

    protected $table = 'kemajuan_penilaian';

    protected $fillable = [
        'peserta_id',
        'penilaian_id',
        'sesi_penilaian_id',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'aktivitas_terakhir',
        'jawaban'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'aktivitas_terakhir' => 'datetime',
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

    public function sesiPenilaian(): BelongsTo
    {
        return $this->belongsTo(SesiPenilaian::class);
    }

    public function sesiAssessment(): BelongsTo
    {
        return $this->belongsTo(SesiAssessment::class, 'penilaian_id', 'penilaian_id')
            ->where('sesi_penilaian_id', $this->sesi_penilaian_id);
    }

    // Accessors
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'belum_mulai' => 'Belum Mulai',
            'sedang_berlangsung' => 'Sedang Berlangsung',
            'draft' => 'Draft',
            'selesai' => 'Selesai',
            default => 'Unknown'
        };
    }
}
