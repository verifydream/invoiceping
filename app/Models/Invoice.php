<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'client_name',
        'client_email',
        'amount',
        'currency',
        'description',
        'status',
        'due_date',
        'sent_at',
        'last_reminder_at',
        'reminder_count',
        'reminder_enabled',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'last_reminder_at' => 'datetime',
        'reminder_count' => 'integer',
        'reminder_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsSent(): void
    {
        $this->update(['sent_at' => now(), 'status' => 'sent']);
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    public function daysSinceSent(): ?int
    {
        return $this->sent_at ? now()->diffInDays($this->sent_at) : null;
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status !== 'paid';
    }
}
