<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelPenilaianStudiKasus extends Model
{
    use HasFactory;

    protected $table = 'level_penilaian_studi_kasus';

    protected $fillable = [
        'aspek_penilaian_studi_kasus_id',
        'level',
        'deskripsi_level',
        'text_report'
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    // Relationships
    public function aspekPenilaianStudiKasus(): BelongsTo
    {
        return $this->belongsTo(AspekPenilaianStudiKasus::class, 'aspek_penilaian_studi_kasus_id');
    }
}
