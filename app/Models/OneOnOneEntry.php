<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OneOnOneEntry extends Model
{
    use HasFactory;

    protected $table = 'one_on_one_entries';

    protected $fillable = [
        'one_on_one_id',
        'user_id',
        'body',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oneOnOne(): BelongsTo
    {
        return $this->belongsTo(OneOnOne::class, 'one_on_one_id');
    }
}
