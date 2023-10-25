<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Project extends Model
{
    use HasFactory, Searchable;

    protected $table = 'projects';

    protected $fillable = [
        'organization_id',
        'author_id',
        'author_name',
        'name',
        'description',
        'short_description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    #[SearchUsingPrefix(['id', 'organization_id'])]
    #[SearchUsingFullText(['name', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'organization_id' => (int) $this->organization_id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class)->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function taskLists(): HasMany
    {
        return $this->hasMany(TaskList::class);
    }

    public function projectResources(): HasMany
    {
        return $this->hasMany(ProjectResource::class);
    }

    public function projectUpdates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    public function keyPeople(): HasMany
    {
        return $this->hasMany(KeyPeople::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * @return Attribute<string,never>
     */
    protected function author(): Attribute
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
