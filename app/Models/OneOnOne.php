<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OneOnOne extends Model
{
    use HasFactory;

    protected $table = 'one_on_ones';

    protected $fillable = [
        'user_id',
        'other_user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function otherUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oneOnOneEntries(): HasMany
    {
        return $this->hasMany(OneOnOneEntry::class);
    }
}
