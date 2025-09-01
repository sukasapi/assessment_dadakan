<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_rumah',
        'nomor_telepon',
        'email',
        'instansi',
        'jabatan_saat_ini',
        'grade',
        'pin',
        'aktif'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'aktif' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($peserta) {
            if (empty($peserta->pin)) {
                $peserta->pin = strtoupper(substr(md5(uniqid()), 0, 6));
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jawabanStudiKasus(): HasMany
    {
        return $this->hasMany(JawabanStudiKasus::class);
    }

    public function jawabanInTray(): HasMany
    {
        return $this->hasMany(JawabanInTray::class);
    }

    public function catatanRoleplay(): HasMany
    {
        return $this->hasMany(CatatanRoleplay::class);
    }

    public function catatanFgd(): HasMany
    {
        return $this->hasMany(CatatanFgd::class);
    }

    public function kemajuanPenilaian(): HasMany
    {
        return $this->hasMany(KemajuanPenilaian::class);
    }

    // Accessors
    public function getJenisKelaminTextAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
