<?php

namespace App\Models;

use App\Models\Concerns\FormatsDateWithWeekday;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;
    use FormatsDateWithWeekday;

    protected $fillable = [
        'user_id',
        'work_session_id',
        'cafe_id',
        'book_id',
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

    public function getExpenseDateLabelAttribute(): ?string
    {
        return $this->formatDateWithWeekday($this->expense_date);
    }

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

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
