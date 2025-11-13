<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianStudiKasus extends Model
{
    use HasFactory;

    protected $table = 'penilaian_studi_kasus';

    protected $fillable = [
        'jawaban_studi_kasus_id',
        'peserta_id',
        'penilaian_id',
        'sesi_penilaian_id',
        'user_id',
        'pertanyaan_1',
        'pertanyaan_2',
        'pertanyaan_3',
        'catatan',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function jawabanStudiKasus(): BelongsTo
    {
        return $this->belongsTo(JawabanStudiKasus::class, 'jawaban_studi_kasus_id');
    }

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function sesiPenilaian(): BelongsTo
    {
        return $this->belongsTo(SesiPenilaian::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getTotalYaAttribute(): int
    {
        $total = 0;
        if ($this->pertanyaan_1 === 'ya') $total++;
        if ($this->pertanyaan_2 === 'ya') $total++;
        if ($this->pertanyaan_3 === 'ya') $total++;
        return $total;
    }

    public function getStatusTextAttribute(): string
    {
        return $this->status === 'final' ? 'Final' : 'Draft';
    }
}
