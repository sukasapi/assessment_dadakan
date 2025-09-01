<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanFgd extends Model
{
    use HasFactory;

    protected $table = 'catatan_fgd';

    protected $fillable = [
        'peserta_id',
        'penilaian_id',
        'catatan',
        'status',
        'waktu_simpan'
    ];

    protected $casts = [
        'waktu_simpan' => 'datetime',
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
}
