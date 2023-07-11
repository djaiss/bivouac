<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'task_list_id',
        'title',
    ];

    public function taskList(): BelongsTo
    {
        return $this->belongsTo(TaskList::class, 'task_list_id');
    }
}
