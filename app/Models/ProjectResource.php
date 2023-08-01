<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectResource extends Model
{
    use HasFactory;

    protected $table = 'project_resources';

    protected $fillable = [
        'project_id',
        'name',
        'link',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
