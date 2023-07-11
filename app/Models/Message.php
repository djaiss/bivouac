<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Message extends Model
{
    use HasFactory, Searchable;

    protected $table = 'messages';

    protected $fillable = [
        'project_id',
        'author_id',
        'author_name',
        'title',
        'body',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    #[SearchUsingPrefix(['id', 'project_id'])]
    #[SearchUsingFullText(['title', 'body'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'project_id' => (int) $this->project_id,
            'title' => $this->title,
            'body' => $this->body,
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    public function taskLists(): MorphMany
    {
        return $this->morphMany(TaskList::class, 'tasklistable');
    }

    /**
     * @return Attribute<string,never>
     */
    protected function authorName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (is_null($attributes['author_id'])) {
                    return $attributes['author_name'];
                }

                return $this->creator->name;
            }
        );
    }
}
