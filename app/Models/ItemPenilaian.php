<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPenilaian extends Model
{
    use HasFactory;

    protected $table = 'item_penilaian';

    protected $fillable = [
        'penilaian_id',
        'judul',
        'konten',
        'petunjuk',
        'jenis',
        'urutan',
        'opsi',
        'aktif'
    ];

    protected $casts = [
        'opsi' => 'array',
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    // Relationships
    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class);
    }
}
