<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LatihanInTray extends Model
{
    use HasFactory;

    protected $table = 'latihan_in_tray';

    protected $fillable = [
        'penilaian_id',
        'konten_memo',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    // Relationships
    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function jawabanInTray(): HasMany
    {
        return $this->hasMany(JawabanInTray::class);
    }
}
