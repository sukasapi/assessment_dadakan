<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'kategori_studi_kasus_id',
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

    public function kategoriStudiKasus(): BelongsTo
    {
        return $this->belongsTo(KategoriStudiKasus::class, 'kategori_studi_kasus_id');
    }

    public function detailPenilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaianStudiKasus::class, 'penilaian_studi_kasus_id');
    }

    // Accessors
    public function getTotalYaAttribute(): int
    {
        // Hanya untuk sistem lama
        if (!$this->isOldSystem()) {
            return 0;
        }
        
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

    // Helper methods untuk deteksi sistem
    public function isOldSystem(): bool
    {
        return is_null($this->kategori_studi_kasus_id);
    }

    public function isNewSystem(): bool
    {
        return !is_null($this->kategori_studi_kasus_id);
    }

    // Method untuk generate report text berdasarkan sistem
    public function getReportText(): array
    {
        if ($this->isOldSystem()) {
            // Sistem lama: return format sederhana
            return [
                'pertanyaan_1' => $this->pertanyaan_1 ?? '-',
                'pertanyaan_2' => $this->pertanyaan_2 ?? '-',
                'pertanyaan_3' => $this->pertanyaan_3 ?? '-',
                'catatan' => $this->catatan ?? '',
                'total_ya' => $this->total_ya
            ];
        } else {
            // Sistem baru: ambil text_report dari level yang dipilih
            $reports = [];
            foreach ($this->detailPenilaian as $detail) {
                $levelPenilaian = LevelPenilaianStudiKasus::where('aspek_penilaian_studi_kasus_id', $detail->aspek_penilaian_studi_kasus_id)
                    ->where('level', $detail->level_terpilih)
                    ->first();
                
                if ($levelPenilaian) {
                    $aspek = $detail->aspekPenilaianStudiKasus;
                    $reports[] = [
                        'aspek_nomor' => $aspek->nomor,
                        'aspek_pertanyaan' => $aspek->pertanyaan,
                        'level' => $detail->level_terpilih,
                        'text_report' => $levelPenilaian->text_report,
                        'deskripsi_level' => $levelPenilaian->deskripsi_level
                    ];
                }
            }
            
            return [
                'kategori' => $this->kategoriStudiKasus->kode ?? '',
                'reports' => $reports,
                'catatan' => $this->catatan ?? ''
            ];
        }
    }

    // Method untuk mendapatkan semua text report sebagai string (untuk tampilan)
    public function getReportTextAsString(): string
    {
        if ($this->isOldSystem()) {
            $text = "Hasil Penilaian (Sistem Lama):\n\n";
            $text .= "1. Apakah jawaban sudah menjawab pertanyaan soal? : " . ($this->pertanyaan_1 ?? '-') . "\n";
            $text .= "2. Apakah jawaban sudah mencerminkan kompetensi-kompetensi? : " . ($this->pertanyaan_2 ?? '-') . "\n";
            $text .= "3. Apakah jawaban sudah menggunakan alat analisis? : " . ($this->pertanyaan_3 ?? '-') . "\n";
            $text .= "\nTotal Ya: " . $this->total_ya . " dari 3 pertanyaan\n";
            if ($this->catatan) {
                $text .= "\nCatatan:\n" . $this->catatan;
            }
            return $text;
        } else {
            $reports = $this->getReportText();
            $text = "Hasil Penilaian (Sistem Baru - Kategori " . $reports['kategori'] . "):\n\n";
            
            foreach ($reports['reports'] as $report) {
                $text .= "Aspek " . $report['aspek_nomor'] . " (Level " . $report['level'] . "):\n";
                $text .= $report['text_report'] . "\n\n";
            }
            
            if ($reports['catatan']) {
                $text .= "Catatan:\n" . $reports['catatan'];
            }
            
            return $text;
        }
    }
}
