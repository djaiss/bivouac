<?php

namespace Tests\Unit\Models;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_organization(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($user->organization()->exists());
    }

    /** @test */
    public function it_has_many_projects(): void
    {
        $user = User::factory()->create();
        Project::factory()->create([
            'author_id' => $user->id,
        ]);

        $this->assertTrue($user->projectsAsCreator()->exists());
    }

    /** @test */
    public function it_belongs_to_many_projects(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $user->projects()->attach($project);

        $this->assertTrue($user->projects()->exists());
    }

    /** @test */
    public function it_belongs_to_many_tasks(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $user->tasks()->attach($task);

        $this->assertTrue($user->tasks()->exists());
    }

    /** @test */
    public function it_gets_the_name(): void
    {
        $user = User::factory()->create([
            'first_name' => null,
            'last_name' => null,
            'email' => 'dwight@dundermifflin.com',
        ]);

        $this->assertEquals(
            $user->name,
            'dwight@dundermifflin.com'
        );

        $user->first_name = 'Dwight';

        $this->assertEquals(
            $user->name,
            'Dwight'
        );

        $user->last_name = 'Schrute';

        $this->assertEquals(
            $user->name,
            'Dwight Schrute'
        );
    }

    /** @test */
    public function it_gets_the_age(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $user = User::factory()->create();

        $this->assertNull($user->age);

        $user->born_at = Carbon::create(1990, 3, 14);
        $user->age_preferences = User::AGE_ONLY_MONTH_DAY;
        $user->save();

        $this->assertEquals('Mar 14', $user->age);

        $user->age_preferences = User::AGE_FULL;
        $user->save();
        $this->assertEquals('Mar 14, 1990 (27)', $user->age);
    }

    /** @test */
    public function it_gets_the_avatar(): void
    {
        $user = User::factory()->create([
            'name_for_avatar' => 'michael',
        ]);

        $this->assertEquals(
            [
                'type' => 'svg',
                'content' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 231 231"><path d="M33.83,33.83a115.5,115.5,0,1,1,0,163.34,115.49,115.49,0,0,1,0-163.34Z" style="fill:#ff1ec1;"/><path d="m115.5 51.75a63.75 63.75 0 0 0-10.5 126.63v14.09a115.5 115.5 0 0 0-53.729 19.027 115.5 115.5 0 0 0 128.46 0 115.5 115.5 0 0 0-53.729-19.029v-14.084a63.75 63.75 0 0 0 53.25-62.881 63.75 63.75 0 0 0-63.65-63.75 63.75 63.75 0 0 0-0.09961 0z" style="fill:#fae3b9;"/><path d="m141.75 195a114.79 114.79 0 0 1 38 16.5 115.53 115.53 0 0 1-128.46 0 114.79 114.79 0 0 1 38-16.5l15.71 15.75h21z" style="fill:#212121;"/><path d="m89.291 195a114.79 114.79 0 0 0-38.002 16.5 115.53 115.53 0 0 0 38.002 16.482zm52.434 0v32.982a115.53 115.53 0 0 0 38-16.482 114.79 114.79 0 0 0-38-16.5z" style="fill:#fff;"/><path d="m157.15 199.75c0.2548 7.4501 1.54 14.855 4.9512 21.432a115.53 115.53 0 0 0 17.619-9.6797 114.79 114.79 0 0 0-22.57-11.752zm-83.295 2e-3a114.79 114.79 0 0 0-22.57 11.75 115.53 115.53 0 0 0 17.621 9.6797c3.411-6.5765 4.6944-13.98 4.9492-21.43z" style="fill:#212121;"/><path d="m99.197 204.97v2e-3l16.302 16.301 16.301-16.301v-2e-3z" style="fill:#fff;"/><path d="m157.79 67.5a61.31 61.31 0 0 1-42.79 17.43h-55.7c18.16-37.74 68.27-46.85 98.49-17.43z" style="fill:#531148;"/><path d="m122.93 7.0078c-10.503-0.15729-21.09 1.6448-29.545 5.4316-17.141 7.8999-32.169 23.297-43.973 38.779-5.1703 6.8631-8.7779 13.46-8.1855 18.395 0.93114 12.312 10.372 26.483 11.068 36.9 15.663-72.081 105.99-70.452 124.91-7.0525l4e-3 0.0156c5.616-10.926 8.0682-20.188 8.352-27.653 0.43654-15.607-7.8088-21.149-21.735-28.249 1.7934-3.7704 1.7273-7.5023 2.0625-10.154-0.79964-7.8568-3.6796-13.51-10.43-17.758-5.9434-3.7404-13.06-6.0867-18.463-7.2266-4.5319-0.87895-9.2901-1.3562-14.064-1.4277z" style="fill:#531148;"/><path d="m42.426 75.338c0.52158 18.689 10.557 74.338-18.115 101.25 12.38 10.603 28.352 19.061 46.025 24.594 11.032-4.6874 22.88-7.4147 34.817-8.5046l0.0633-14.477c-22.49-4.3813-40.766-18.898-48.862-39.967-8.096-21.07-4.7931-44.72 9.2478-62.393zm124.67 2.7207c7.8997 10.886 11.743 24.64 11.787 37.441-0.36632 30.178-22.389 57.576-53.12 62.708l0.0238 14.471c12.282 1.1216 24.518 3.9888 35.825 8.9128 15.488-5.1448 30.007-13.325 42.396-25.043-13.136-22.051-23.282-63.045-18.694-101.55z" style="fill:#531148;"/><path d="m143.61 46.383c-11.639 0.12482-20.998 1.8906-20.998 1.8906l-9 3.5059c0.63003-0.0191 1.2603-0.0289 1.8906-0.0293h0.0996c35.169 0.055 60.959 27.235 63.283 63.383 7.4e-4 31.157-22.742 57.213-53.106 63.079l-0.0216 14.498c11.567 1.0563 23.154 3.6067 33.887 8.0463 35.952-15.315 55.082-52.303 36.709-68.279-5.018-7.9035-10.44-15.409-9.5544-23.03 5.0545-50.452 0.39626-63.561-43.189-63.064zm-69.966 21.09c-15.286 3.244-17.096 3.73-31.734 6.6953 3.0304 13.081 3.0583 22.274 1.2085 30.012-3.8004 11.361-8.9712 19.787-12.286 28.764-6.8823 22.459-2.9157 31.982 12.093 46.165 8.6595 8.0693 19.861 16.209 30.939 20.647 2.669-1.0316 5.3729-1.9628 8.106-2.792 7.4979-2.275 15.388-3.6535 23.206-4.3673l0.0433-14.393c-23.933-4.5937-44.283-21.98-50.77-45.817-6.3319-23.265 0.51104-48.752 19.195-64.914z" style="fill:none;"/><path d="m145.39 104.7-11.52 11.2h17.26m-65.52-11.2 11.52 11.2h-17.26" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.4998px;stroke:#000;"/><polygon points="121.61 160.74 109.39 160.74 115.5 171.31" style="fill:#f73b6c;"/><path d="m132.64 144.06a34.42 34.42 0 0 1-34.24 0" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.9998px;stroke:#000;"/></svg>',
            ],
            $user->avatar
        );
    }
}
