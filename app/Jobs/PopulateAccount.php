<?php

namespace App\Jobs;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PopulateAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Organization $organization)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $jobs = [
            trans_key('Software Engineer'),
            trans_key('Quality Assurance (QA) Engineer'),
            trans_key('Project Manager'),
            trans_key('Product Manager'),
            trans_key('UI/UX Designer'),
            trans_key('Data Analyst/Scientist'),
            trans_key('DevOps Engineer'),
            trans_key('Technical Support Engineer'),
            trans_key('Scrum Master'),
            trans_key('Sales/Account Manager'),
            trans_key('Technical Writer'),
            trans_key('System Administrator'),
            trans_key('CEO'),
        ];

        $position = 1;
        foreach ($jobs as $job) {
            DB::table('roles')->insert([
                'organization_id' => $this->organization->id,
                'label' => null,
                'label_translation_key' => $job,
                'position' => $position++,
                'created_at' => now(),
            ]);
        }
    }
}
