<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory, HasUuids;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'type',
        'status',
        'color',
        'all_day',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'all_day' => 'boolean'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Format for FullCalendar
    public function toFullCalendarArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start->toISOString(),
            'end' => $this->end->toISOString(),
            'backgroundColor' => $this->color,
            'borderColor' => $this->color,
            'textColor' => $this->getTextColor(),
            'allDay' => $this->all_day,
            'extendedProps' => [
                'description' => $this->description,
                'type' => $this->type,
                'status' => $this->status,
                'creator' => $this->creator->name ?? 'Unknown'
            ]
        ];
    }

    private function getTextColor(): string
    {
        // Calculate luminance and return appropriate text color
        $hex = ltrim($this->color, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }
}
