<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Aplikasi ini dikembangkan oleh Kusuma Dewangga
 * Hak Cipta © 2025
 * Email: kdewangga85@gmail.com
 */ 
class SesiAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sesi_assessment';
    
    protected $fillable = [
        'sesi_penilaian_id',
        'penilaian_id',
        'kategori_studi_kasus_id',
        'urutan',
        'aktif',
        'durasi_default',
        'instruksi_khusus',
        'model_in_tray',
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
     * Get the kategori studi kasus
     */
    public function kategoriStudiKasus(): BelongsTo
    {
        return $this->belongsTo(KategoriStudiKasus::class, 'kategori_studi_kasus_id');
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

    /**
     * Override the default soft delete behavior to handle '0000-00-00 00:00:00' values
     */
    protected static function bootSoftDeletes()
    {
        static::addGlobalScope(new class extends \Illuminate\Database\Eloquent\SoftDeletingScope {
            public function apply(\Illuminate\Database\Eloquent\Builder $builder, \Illuminate\Database\Eloquent\Model $model)
            {
                $builder->where(function ($query) use ($model) {
                    $query->whereNull($model->getQualifiedDeletedAtColumn())
                          ->orWhere($model->getQualifiedDeletedAtColumn(), '0000-00-00 00:00:00');
                });
            }
        });
    }
}
