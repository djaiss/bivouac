<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use App\Services\AddCommentToMessage;
use App\Services\AddProjectMember;
use App\Services\CreateAccount;
use App\Services\CreateMessage;
use App\Services\CreateProject;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SetupDummyAccount extends Command
{
    use ConfirmableTrait;

    protected ?\Faker\Generator $faker;

    protected User $user;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bivouac:dummy
                            {--migrate : Use migrate command instead of migrate:fresh.}
                            {--force : Force the operation to run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare an account with fake data so users can play with it';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // remove queue
        config(['queue.default' => 'sync']);

        $this->start();
        $this->wipeAndMigrateDB();
        $this->createFirstUser();
        $this->createOtherUsers();
        $this->createProjects();

        $this->createSecondOrganization();
        $this->stop();
    }

    private function start(): void
    {
        if (! $this->confirmToProceed('Are you sure you want to proceed? This will delete ALL data in your environment.', true)) {
            exit;
        }

        $this->line('This process will take a few minutes to complete. Be patient and read a book in the meantime.');
        $this->faker = Faker::create();
    }

    private function wipeAndMigrateDB(): void
    {
        if ($this->option('migrate')) {
            $this->artisan('☐ Migration of the database', 'migrate', ['--force' => true]);
        } else {
            $this->artisan('☐ Migration of the database', 'migrate:fresh', ['--force' => true]);
        }
        $this->artisan('☐ Reset search engine', 'scout:setup', ['--force' => true, '--flush' => true]);
    }

    private function stop(): void
    {
        $this->line('');
        $this->line('-----------------------------');
        $this->line('|');
        $this->line('| Welcome to Bivouac');
        $this->line('|');
        $this->line('-----------------------------');
        $this->info('| You can now sign in with one of these two accounts:');
        $this->line('| An account with a lot of data:');
        $this->line('| username: admin@admin.com');
        $this->line('| password: admin123');
        $this->line('|------------------------–––-');
        $this->line('|A blank account:');
        $this->line('| username: blank@blank.com');
        $this->line('| password: blank123');
        $this->line('|------------------------–––-');
        $this->line('| URL:      ' . config('app.url'));
        $this->line('-----------------------------');

        $this->info('Setup is done. Have fun.');
    }

    private function createFirstUser(): void
    {
        $this->info('☐ Create first user of the account');

        $this->user = (new CreateAccount)->execute([
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'organization_name' => 'Bivouac',
        ]);
        $this->user->email_verified_at = Carbon::now();
        $this->user->save();
    }

    private function createOtherUsers(): void
    {
        $this->info('☐ Create users');

        for ($i = 0; $i < rand(3, 15); $i++) {
            User::create([
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'email' => $this->faker->email,
                'permissions' => User::ROLE_ACCOUNT_MANAGER,
                'name_for_avatar' => $this->faker->firstName,
                'password' => Hash::make('blank123'),
                'organization_id' => $this->user->organization_id,
                'invitation_code' => (string) Str::uuid(),
            ]);
        }
    }

    private function createProjects(): void
    {
        $this->info('☐ Create projects');

        $projectNames = [
            'In-Store Software Evolution Service',
            'Next-Generation Point-of-Sale System',
            'Intelligent Inventory Management Suite',
            'Advanced Customer Engagement Solutions',
            'Smart Store Operations Monitoring',
            'Evolving Retail Analytics Software',
            'Digital Transformation for Physical Stores',
            'Intelligent Product Recommendation Engine',
            'Revolutionary In-Store App Development',
            'Predictive Pricing and Promotions',
            'Dynamic Supply Chain Management Solutions',
            'Machine Learning Driven Business Insights',
            'Cutting-Edge Mobile Checkout Innovations',
            'Adaptable Store Maintenance Tools',
            'Automated Staff Scheduling and Optimization',
            'Virtual Reality In-Store Experience',
            'Enhanced Cybersecurity for Brick and Mortar',
            'Progressive Data Analytics for Retail',
            'Natural Language In-Store Assistants',
            'Continuous Software Improvement for Stores',
        ];

        for ($i = 0; $i < rand(3, 5); $i++) {
            $project = (new CreateProject)->execute([
                'user_id' => $this->user->id,
                'name' => $projectNames[array_rand($projectNames)],
                'description' => rand(1, 2) == 1 ? $this->faker->sentence() : null,
                'is_public' => rand(1, 2) == 1,
            ]);

            $this->addMembersToProject($project);
            $this->addMessages($project);
        }
    }

    private function addMembersToProject(Project $project): void
    {
        $this->info('☐ Add members to project ' . $project->name);

        User::get()
            ->map(fn (User $user) => (new AddProjectMember)->execute([
                'user_id' => $user->id,
                'project_id' => $project->id,
            ]));
    }

    private function addMessages(Project $project): void
    {
        $this->info('☐ Add messages to project ' . $project->name);

        $messageTitles = [
            "Hey, let's accelerate our project together!",
            'Calling all users: how can we speed things up?',
            'We need your input to break through barriers',
            "Let's revitalize our project. your ideas matter",
            'Ignite innovation and supercharge progress',
            'Boosting project velocity with your collaboration',
            "Powering ahead: let's drive swift advancement",
            'Agile minds unite: overcoming obstacles',
            'Pursuing speed: your contributions count',
            'Join us to unlock rapid progress',
            'Getting back on the fast track with your support',
            'Reigniting the momentum: breaking free',
            'Racing towards success: your involvement matters',
            'Elevating the pace: join the movement',
            'Hustle time: collaborative actions for results',
            'Breaking bottlenecks: speeding up our journey',
            'Reshaping our future: embracing agility',
            "Urging a swift approach: let's move together",
            'Leveraging synergy: powering project performance',
            'Transforming delays into deliveries: fueling progress',
            'Fuelling momentum: speed as our mantra',
            'Reenergizing our efforts: united for swift advancements',
            'Maximizing velocity: your participation counts',
            'Empowering action-oriented users for success',
            'Unleashing a speed revolution: join the movement',
            'Taking charge of speed: collaborating for milestones',
            'Breaking free from sluggishness: your involvement matters',
            'Rapid progress catalysts: amplifying our project',
            'Seizing time together: propelling success',
        ];

        for ($i = 0; $i < rand(3, 5); $i++) {
            $message = (new CreateMessage)->execute([
                'user_id' => $this->user->id,
                'project_id' => $project->id,
                'title' => $messageTitles[array_rand($messageTitles)],
                'body' => $this->faker->paragraph(15),
            ]);

            $this->addComments($message);
        }
    }

    private function addComments(Message $message): void
    {
        $this->info('☐ Add comments to message ' . $message->title);

        for ($i = 0; $i < rand(3, 5); $i++) {
            (new AddCommentToMessage)->execute([
                'user_id' => User::inRandomOrder()->first()->id,
                'message_id' => $message->id,
                'body' => $this->faker->paragraph(15),
            ]);
        }
    }

    private function createSecondOrganization(): void
    {
        $this->info('☐ Create first user of the account');

        $user = (new CreateAccount)->execute([
            'email' => 'blank@blank.com',
            'password' => 'blank123',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'organization_name' => 'Github',
        ]);
        $user->email_verified_at = Carbon::now();
        $user->save();
    }

    private function artisan(string $message, string $command, array $arguments = []): void
    {
        $this->info($message);
        $this->callSilent($command, $arguments);
    }
}
