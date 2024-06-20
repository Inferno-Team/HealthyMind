<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TimelineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'timeline_id',
        'item_id',
        'item_type',
        'event_date_start',
        'event_date_end',
    ];
    public function timeline(): BelongsTo
    {
        return $this->belongsTo(CoachTimeline::class, 'timeline_id');
    }
    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class, 'timeline_item_id');
    }
    public function findDiff($delta): array
    {
        // info($delta);
        $years_diff = $delta['years'] * 3.154e+10;
        $months_diff = $delta['months'] * 2.628e+9;
        $days_diff = $delta['days'] * 8.64e+7;
        $milliseconds_diff = $delta['milliseconds'];
        $delta_ts = ($years_diff + $months_diff + $days_diff + $milliseconds_diff);

        $event_date_start = Carbon::parse($this->event_date_start);

        $event_date_end = Carbon::parse($this->event_date_end);
        $all = (string) $delta_ts;
        $event_date_start = $event_date_start->addRealMilliseconds($all)->format('Y-m-d H:i:s');
        $event_date_end = $event_date_end->addRealMilliseconds($all)->format('Y-m-d H:i:s');
        return [$event_date_start, $event_date_end];
    }

    public function format($normal_user_id = null)
    {
        $item_type_string = explode('\\', $this->item_type);
        return (object)[
            "id" => $this->id,
            "item_type" => end($item_type_string),
            "event_date_start" => $this->event_date_start,
            "event_date_end" => $this->event_date_end,
            "event_item" => $this->item->format(),
            'progress' => $normal_user_id == null ? 0 : $this->progress()->where('user_id', $normal_user_id)->first()?->percentage ?? 0,
        ];
    }
}
