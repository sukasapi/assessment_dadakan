<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentSession extends Model
{
    protected $fillable = [
        'name',
        'status',
        'start_time',
        'end_time',
        'duration_minutes',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'active' => 'Aktif',
            'paused' => 'Dijeda',
            'completed' => 'Selesai',
            default => 'Unknown'
        };
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->is_active;
    }

    public function getRemainingTime(): ?int
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        $remaining = now()->diffInSeconds($this->end_time, false);
        return $remaining > 0 ? $remaining : 0;
    }

    public function startSession(): void
    {
        $this->update([
            'status' => 'active',
            'start_time' => now(),
            'end_time' => now()->addMinutes($this->duration_minutes)
        ]);
    }

    public function stopSession(): void
    {
        $this->update([
            'status' => 'completed',
            'end_time' => now()
        ]);
    }
}
