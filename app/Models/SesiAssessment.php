<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Aplikasi ini dikembangkan oleh Kusuma Dewangga
 * Hak Cipta © 2025
 * Email: kdewangga85@gmail.com
 */ 
class SesiAssessment extends Model
{
    use HasFactory;

    protected $table = 'sesi_assessment';
    
    protected $fillable = [
        'sesi_penilaian_id',
        'penilaian_id',
        'urutan',
        'aktif',
        'durasi_default',
        'instruksi_khusus',
        'memos'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'durasi_default' => 'integer',
        'memos' => 'array',
    ];

    /**
     * Get the session that owns this assessment
     */
    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiPenilaian::class, 'sesi_penilaian_id');
    }

    /**
     * Get the assessment details
     */
    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }

    /**
     * Scope for active assessments
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope for ordered assessments
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}
