<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrioritasMemo extends Model
{
    use HasFactory;

    protected $table = 'prioritas_memo';
    
    protected $fillable = [
        'jawaban_in_tray_id',
        'kategori_prioritas'
    ];

    protected $casts = [
        'kategori_prioritas' => 'string',
    ];

    // Relationships
    public function jawabanInTray(): BelongsTo
    {
        return $this->belongsTo(JawabanInTray::class);
    }

    // Constants for priority categories
    const MENDESAK_PENTING = 'mendesak_penting';
    const MENDESAK_TIDAK_PENTING = 'mendesak_tidak_penting';
    const TIDAK_MENDESAK_PENTING = 'tidak_mendesak_penting';
    const TIDAK_MENDESAK_TIDAK_PENTING = 'tidak_mendesak_tidak_penting';

    // Get priority label
    public function getPriorityLabelAttribute()
    {
        return match($this->kategori_prioritas) {
            self::MENDESAK_PENTING => 'Mendesak - Penting',
            self::MENDESAK_TIDAK_PENTING => 'Mendesak - Tidak Penting',
            self::TIDAK_MENDESAK_PENTING => 'Tidak Mendesak - Penting',
            self::TIDAK_MENDESAK_TIDAK_PENTING => 'Tidak Mendesak - Tidak Penting',
            default => 'Belum Dipilih'
        };
    }

    // Get all priority options
    public static function getPriorityOptions()
    {
        return [
            self::MENDESAK_PENTING => 'Mendesak - Penting',
            self::MENDESAK_TIDAK_PENTING => 'Mendesak - Tidak Penting',
            self::TIDAK_MENDESAK_PENTING => 'Tidak Mendesak - Penting',
            self::TIDAK_MENDESAK_TIDAK_PENTING => 'Tidak Mendesak - Tidak Penting',
        ];
    }
}
