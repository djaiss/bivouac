<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TaskList extends Model
{
    use HasFactory;

    protected $table = 'task_lists';

    protected $fillable = [
        'organization_id',
        'project_id',
        'name',
        'tasklistable_id',
        'tasklistable_type',
        'collapsed',
    ];

    protected $casts = [
        'collapsed' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasklistable(): MorphTo
    {
        return $this->morphTo();
    }
}
