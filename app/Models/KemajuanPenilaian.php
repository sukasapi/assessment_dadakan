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
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'aktivitas_terakhir'
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

    // Accessors
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'belum_mulai' => 'Belum Mulai',
            'sedang_berlangsung' => 'Sedang Berlangsung',
            'selesai' => 'Selesai',
            default => 'Unknown'
        };
    }
}
