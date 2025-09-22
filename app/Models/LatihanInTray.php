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
        'sesi_penilaian_id',
        'konten_memo',
        'urutan',
        'aktif',
        'pertanyaan'
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

    public function sesiPenilaian(): BelongsTo
    {
        return $this->belongsTo(SesiPenilaian::class);
    }

    public function jawabanInTray(): HasMany
    {
        return $this->hasMany(JawabanInTray::class);
    }

    // Helper methods
    public function hasQuestion(): bool
    {
        return !empty($this->pertanyaan);
    }

    public function getQuestionAttribute()
    {
        return $this->pertanyaan ?? 'Tidak ada pertanyaan';
    }

    // Scopes
    public function scopeWithQuestions($query)
    {
        return $query->whereNotNull('pertanyaan')->where('pertanyaan', '!=', '');
    }

    public function scopeWithoutQuestions($query)
    {
        return $query->where(function($q) {
            $q->whereNull('pertanyaan')->orWhere('pertanyaan', '');
        });
    }
}
