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
        'waktu_simpan'
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
}
