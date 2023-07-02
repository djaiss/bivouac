<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\CreateAccount;
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

        for ($i = 0; $i < rand(3, 5); $i++) {
            (new CreateProject)->execute([
                'user_id' => $this->user->id,
                'name' => $this->faker->firstName,
                'description' => rand(1, 2) == 1 ? $this->faker->sentence() : null,
                'is_public' => rand(1, 2) == 1,
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
