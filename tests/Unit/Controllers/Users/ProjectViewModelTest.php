<?php

namespace Tests\Unit\Controllers\Users;

use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_index_view(): void
    {
        $organization = Organization::factory()->create();
        Project::factory()->create([
            'organization_id' => $organization->id,
        ]);
        $array = ProjectViewModel::index($organization);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('projects', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals(
            [
                'create' => env('APP_URL') . '/projects/create',
                'breadcrumb' => [
                    'home' => env('APP_URL') . '/profile',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_data_needed_for_the_create_view(): void
    {
        $array = ProjectViewModel::create();

        $this->assertCount(1, $array);
        $this->assertEquals(
            [
                'store' => env('APP_URL') . '/projects',
                'breadcrumb' => [
                    'projects' => env('APP_URL') . '/projects',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_data_for_the_show_view(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'name_for_avatar' => 'Michael Scott',
        ]);
        $project = Project::factory()->create([
            'created_by_user_id' => $user->id,
            'name' => 'Dunder',
            'description' => 'Dunder Mifflin',
            'is_public' => true,
        ]);
        $array = ProjectViewModel::show($project);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals(
            [
                'breadcrumb' => [
                    'projects' => env('APP_URL') . '/projects',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_data_for_the_edit_view(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'name_for_avatar' => 'Michael Scott',
        ]);
        $project = Project::factory()->create([
            'created_by_user_id' => $user->id,
            'name' => 'Dunder',
            'description' => 'Dunder Mifflin',
            'is_public' => true,
        ]);
        $array = ProjectViewModel::edit($project);

        $this->assertCount(2, $array);
        $this->assertEquals(
            [
                'project' => [
                    'id' => $project->id,
                    'author' => [
                        'name' => 'Michael Scott',
                        'avatar' => [
                            'type' => User::AVATAR_TYPE_SVG,
                            'content' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 231 231"><path d="M33.83,33.83a115.5,115.5,0,1,1,0,163.34,115.49,115.49,0,0,1,0-163.34Z" style="fill:#6FC30E;"/><path d="m115.5 51.75a63.75 63.75 0 0 0-10.5 126.63v14.09a115.5 115.5 0 0 0-53.729 19.027 115.5 115.5 0 0 0 128.46 0 115.5 115.5 0 0 0-53.729-19.029v-14.084a63.75 63.75 0 0 0 53.25-62.881 63.75 63.75 0 0 0-63.65-63.75 63.75 63.75 0 0 0-0.09961 0z" style="fill:#ffce73;"/><path d="m141.75 195a114.79 114.79 0 0 1 38 16.5 115.53 115.53 0 0 1-128.46 0 114.79 114.79 0 0 1 38-16.5c0 10.76 11.75 19.48 26.25 19.48s26.25-8.72 26.25-19.48z" style="fill:#d12823;"/><path d="m124.22 13.61c-19.783 0-36.945 8.0887-39.695 24.106-15.332 0.23539-31.831 2.7712-41.663 15.782-6.0238 7.9604-7.0402 19.901-6.8476 31.724 0.46007 28.503 10.742 64.228-4.3012 89.714 16.584 5.7777 43.086 10.742 73.59 11.662v-8.6558c-1.851-0.35308-3.6592-0.78105-5.4353-1.2732-30.953-8.4632-50.672-36.635-47.259-68.669 1.5514-10.603 4.6221-19.665 10.025-27.69 5.3818-7.9925 13.267-15.717 23.892-21.41 0.40658 0.72757 1.9901 3.5843 2.4074 4.3012 7.5003 12.775 17.986 23.849 33.157 26.866 12.433 2.4609 23.849 3.4666 36.346 1.1555 4.2584-0.78106 10.667-2.3967 14.851-2.4181 14.861 33.404-1.0806 75.035-40.668 87.457-2.2255 0.70616-4.5258 1.316-6.8904 1.8189 0 2.707-0.0428 5.6493-0.0642 8.5274 23.603-0.72757 48.682-4.0444 72.874-11.234-18.521-32.152 0.81315-89.083-10.036-121.46-9.0731-26.973-38.85-40.315-64.282-40.305z" style="fill:#000;"/><path d="m33.147 172.32c-2.6535 5.1143-6.088 9.9504-10.1 12.411 7.8427 10.453 17.387 19.516 28.257 26.781 16.038-10.731 35.629-17.055 54-18.606v-9.0089c-30.065-0.94155-56.108-5.8847-72.157-11.577zm164.06 0.55637c-23.731 7.0723-48.361 10.325-71.525 11.042-0.0321 3.1242-0.0535 6.2377-0.0107 9.0517 19.227 1.7226 37.908 7.8534 53.989 18.542 0.0107 0 0.0107 0 0.0214 0.0107 10.731-7.1686 20.179-16.081 27.958-26.374-4.2798-2.3967-7.832-6.9653-10.432-12.272z" style="fill:none;"/><path d="m50.02 46.5c-2.9297 1.9143-6.1313 3.8826-10.154 7.9805-14.091 14.359-16.145 27.701-6.1406 44.018 4.2049 6.8583 6.1414 13.706-0.24609 20.5-7.7143 8.1957-21.559 4.2912-21.537 16.061 0.0214 8.613 15.063 7.9178 22.531 13.984 3.7662 3.0707 5.0836 8.3992 2.0664 12.508-4.2156 5.7456-16.006 7.3715-22.629 8.9336 5.8811 10.843 13.45 20.638 22.355 29.033l0.0039 0.0234 0.0059-0.0137c2e-3 2e-3 0.0038 4e-3 0.0059 6e-3 0.0034-0.0112 0.0063-0.0219 0.0098-0.0332 14.775-12.218 20.268-20.965 49.461-28.434-17.404-10.258-30.68-27.122-24.143-35.34 4.4123-5.5444 5.6612-7.8633 6.4062-12.078 2.3582-13.339-10.208-22.335-9.2363-32.715 1.9432-8.2346 11.379-11.173 16.947-15.115 5.4577-3.9082 9.8014-8.7695 10.799-16.918-13.558-4.8896-17.609-5.8617-36.506-12.4zm140.87 19.357c-3.4404-0.91243-23.311 122.43 4.4121 133.14 8.9661-8.5809 16.552-18.584 22.404-29.658 0-0.31029-25.133-3.9922-25.979-14.018-0.10699-1.1769 0.11822-1.4855 0.86718-2.502 6.6764-9.2122 30.716-11.416 29.646-23.496-0.27818-3.1563-4.1617-5.2334-6.7402-6.4531-12.155-5.767-32.942-9.6494-15.031-24.543 9.2122-7.3505 10.43-8.4323 0.59766-14.691-9.4583-6.0238-9.394-11.993-9.7578-16.326-0.0767-0.93035-0.22089-1.4003-0.41992-1.4531z" style="fill:none;"/><path d="m133.83 39.909c-11.33 1.393-9.5492 16.204-2e-3 16.643-4.5102 10.717 9.0165 16.181 14.441 8.3125 6.562 8.6765 18.596 0.94751 14.457-8.3125 11.718-1.5381 9.2769-16.099 0-16.643 4.503-10.867-9.4883-16.101-14.457-8.3301-6.8832-9.0411-18.509-0.47321-14.439 8.3301z" style="fill:#FFCC00;"/><path d="m153.86 48.222c0-3.0528-2.5184-5.5648-5.5791-5.5648-3.0783 0-5.5793 2.512-5.5793 5.5648 0 3.0703 2.501 5.5648 5.5793 5.5648 3.0606 0 5.5791-2.4946 5.5791-5.5648z" style="fill:red;"/><path d="m145.38 95.628c-5.1601 2.2597-11.03 2.2597-16.19 0m-47.29 1.75c5.1755-2.2694 11.065-2.2694 16.24 0" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.9998px;stroke:#795548;"/><path d="m90.016 106.28c-4.4506-0.0105-6.6902 5.3657-3.5508 8.5195 3.1394 3.1539 8.5252 0.93887 8.5352-3.5117 0.0063-2.7522-2.2204-4.9898-4.9727-4.9961l-0.011719-0.01172zm47.281 0c-4.4506-0.0105-6.6902 5.3657-3.5508 8.5195 3.1394 3.1539 8.5252 0.93887 8.5352-3.5117 6e-3 -2.7522-2.2204-4.9898-4.9727-4.9961l-0.01171-0.01172z" style="fill:#000;"/><path d="m115.68 160.64c7.08 0 13.11-4.93 15.46-11.84a2.14 2.14 0 0 0-1.51-2.6101 2.3 2.3 0 0 0-0.73995-0.0593h-26.42a2.12 2.12 0 0 0-2.31 1.9099 1.85 1.85 0 0 0 0.0593 0.73995c2.3401 6.9301 8.3802 11.86 15.46 11.86z" style="fill:#7d0000;"/></svg>',
                        ],
                    ],
                    'name' => 'Dunder',
                    'description' => 'Dunder Mifflin',
                    'is_public' => true,
                ],
                'url' => [
                    'update' => env('APP_URL') . '/projects/' . $project->id,
                    'destroy' => env('APP_URL') . '/projects/' . $project->id,
                    'breadcrumb' => [
                        'home' => env('APP_URL') . '/profile',
                        'projects' => env('APP_URL') . '/projects',
                        'project' => env('APP_URL') . '/projects/' . $project->id,
                    ],
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_dto(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Michael',
            'last_name' => 'Scott',
        ]);
        $project = Project::factory()->create([
            'created_by_user_id' => $user->id,
            'name' => 'Dunder',
            'description' => 'Dunder Mifflin',
            'is_public' => true,
        ]);
        $project->users()->attach($user->id);
        $array = ProjectViewModel::dto($project);

        $this->assertCount(6, $array);
        $this->assertEquals($project->id, $array['id']);
        $this->assertEquals('Dunder', $array['name']);
        $this->assertEquals('Dunder Mifflin', $array['description']);
        $this->assertTrue($array['is_public']);
        $this->assertEquals(
            [
                'show' => env('APP_URL') . '/projects/' . $project->id,
                'edit' => env('APP_URL') . '/projects/' . $project->id . '/edit',
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_data_for_the_menu(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'name_for_avatar' => 'Michael Scott',
        ]);
        $project = Project::factory()->create([
            'created_by_user_id' => $user->id,
            'name' => 'Dunder',
            'description' => 'Dunder Mifflin',
            'is_public' => true,
        ]);
        $array = ProjectViewModel::menu($project);

        $this->assertCount(1, $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals(
            [
                'settings' => env('APP_URL') . '/projects/' . $project->id . '/edit',
            ],
            $array['url']
        );
    }
}
