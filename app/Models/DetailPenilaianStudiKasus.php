<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenilaianStudiKasus extends Model
{
    use HasFactory;

    protected $table = 'detail_penilaian_studi_kasus';

    protected $fillable = [
        'penilaian_studi_kasus_id',
        'aspek_penilaian_studi_kasus_id',
        'level_terpilih'
    ];

    protected $casts = [
        'level_terpilih' => 'integer',
    ];

    // Relationships
    public function penilaianStudiKasus(): BelongsTo
    {
        return $this->belongsTo(PenilaianStudiKasus::class, 'penilaian_studi_kasus_id');
    }

    public function aspekPenilaianStudiKasus(): BelongsTo
    {
        return $this->belongsTo(AspekPenilaianStudiKasus::class, 'aspek_penilaian_studi_kasus_id');
    }

    public function levelPenilaian(): BelongsTo
    {
        return $this->belongsTo(LevelPenilaianStudiKasus::class, 'aspek_penilaian_studi_kasus_id', 'aspek_penilaian_studi_kasus_id')
            ->where('level', $this->level_terpilih);
    }
}
