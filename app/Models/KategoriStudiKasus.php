<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriStudiKasus extends Model
{
    use HasFactory;

    protected $table = 'kategori_studi_kasus';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relationships
    public function aspekPenilaian(): HasMany
    {
        return $this->hasMany(AspekPenilaianStudiKasus::class, 'kategori_studi_kasus_id');
    }

    public function aspekPenilaianAktif(): HasMany
    {
        return $this->aspekPenilaian()->where('aktif', true)->orderBy('urutan');
    }
}
