<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_session_id',
        'cafe_id',
        'expense_date',
        'title',
        'amount',
        'expense_type',
        'payment_method',
        'accounting_recorded',
        'accounting_recorded_at',
        'accounting_memo',
        'memo',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'accounting_recorded' => 'boolean',
        'accounting_recorded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workSession(): BelongsTo
    {
        return $this->belongsTo(WorkSession::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }
}
