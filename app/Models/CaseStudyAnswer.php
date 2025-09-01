<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudyAnswer extends Model
{
    protected $fillable = [
        'participant_id',
        'assessment_id',
        'answer',
        'status',
        'saved_at'
    ];

    protected $casts = [
        'saved_at' => 'datetime',
    ];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'final' => 'Final',
            default => 'Unknown'
        };
    }
}
