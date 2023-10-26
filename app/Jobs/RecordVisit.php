<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\ProjectVisit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordVisit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Project $project, public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ProjectVisit::create([
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
        ]);
    }
}
