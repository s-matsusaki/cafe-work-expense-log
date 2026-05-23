<?php

namespace App\Models;

use App\Models\Concerns\FormatsDateWithWeekday;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkSession extends Model
{
    use HasFactory;
    use FormatsDateWithWeekday;
    
    protected $fillable = [
        'user_id',
        'cafe_id',
        'work_date',
        'start_time',
        'end_time',
        'title',
        'work_minutes',
        'category',
        'memo',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    public function getWorkDateLabelAttribute(): ?string
    {
        return $this->formatDateWithWeekday($this->work_date);
    }

    public function getTimeRangeLabelAttribute(): string
    {
        if (blank($this->start_time) && blank($this->end_time)) {
            return '未入力';
        }

        if (blank($this->start_time)) {
            return '〜'.$this->formatTime($this->end_time);
        }

        if (blank($this->end_time)) {
            return $this->formatTime($this->start_time).'〜';
        }

        return $this->formatTime($this->start_time).'〜'.$this->formatTime($this->end_time);
    }

    private function formatTime(string $time): string
    {
        return substr($time, 0, 5);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
