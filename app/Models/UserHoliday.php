<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHoliday extends Model
{
    protected $fillable = [
        'user_id',
        'holiday_date',
    ];

    protected $casts = [
        'holiday_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
