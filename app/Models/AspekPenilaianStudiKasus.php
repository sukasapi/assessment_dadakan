<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AspekPenilaianStudiKasus extends Model
{
    use HasFactory;

    protected $table = 'aspek_penilaian_studi_kasus';

    protected $fillable = [
        'kategori_studi_kasus_id',
        'nomor',
        'pertanyaan',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'nomor' => 'integer',
        'urutan' => 'integer',
    ];

    // Relationships
    public function kategoriStudiKasus(): BelongsTo
    {
        return $this->belongsTo(KategoriStudiKasus::class, 'kategori_studi_kasus_id');
    }

    public function levelPenilaian(): HasMany
    {
        return $this->hasMany(LevelPenilaianStudiKasus::class, 'aspek_penilaian_studi_kasus_id')->orderBy('level');
    }

    public function detailPenilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaianStudiKasus::class, 'aspek_penilaian_studi_kasus_id');
    }
}
