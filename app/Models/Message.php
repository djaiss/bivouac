<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Message extends Model
{
    use HasFactory, Searchable;

    protected $table = 'messages';

    protected $fillable = [
        'project_id',
        'created_by_user_id',
        'created_by_user_name',
        'title',
        'body',
    ];

    #[SearchUsingPrefix(['id', 'project_id'])]
    #[SearchUsingFullText(['title', 'body'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'project_id' => (int) $this->project_id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @return Attribute<string,never>
     */
    protected function author(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (is_null($attributes['created_by_user_id'])) {
                    return $attributes['created_by_user_name'];
                }

                return $this->creator->name;
            }
        );
    }
}
