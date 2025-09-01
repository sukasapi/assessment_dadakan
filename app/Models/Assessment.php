<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $fillable = [
        'assessment_session_id',
        'name',
        'type',
        'instructions',
        'content',
        'duration_minutes',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
        'order' => 'integer',
    ];

    public function assessmentSession(): BelongsTo
    {
        return $this->belongsTo(AssessmentSession::class);
    }

    public function assessmentItems(): HasMany
    {
        return $this->hasMany(AssessmentItem::class);
    }

    public function caseStudyAnswers(): HasMany
    {
        return $this->hasMany(CaseStudyAnswer::class);
    }

    public function inTrayExercises(): HasMany
    {
        return $this->hasMany(InTrayExercise::class);
    }

    public function inTrayAnswers(): HasMany
    {
        return $this->hasMany(InTrayAnswer::class);
    }

    public function roleplayNotes(): HasMany
    {
        return $this->hasMany(RoleplayNote::class);
    }

    public function fgdNotes(): HasMany
    {
        return $this->hasMany(FgdNote::class);
    }

    public function assessmentProgress(): HasMany
    {
        return $this->hasMany(AssessmentProgress::class);
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

    public function isActive(): bool
    {
        return $this->assessmentSession->isActive() && $this->is_active;
    }

    public function getRemainingTime(): ?int
    {
        return $this->assessmentSession->getRemainingTime();
    }
}
