<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentItem extends Model
{
    protected $fillable = [
        'assessment_id',
        'title',
        'content',
        'instructions',
        'type',
        'order',
        'options',
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'case_study' => 'Studi Kasus',
            'in_tray' => 'In-Tray Exercise',
            'roleplay' => 'Role-Play',
            'fgd' => 'FGD',
            default => 'Unknown'
        };
    }
}
