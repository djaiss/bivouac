<?php

namespace Tests\Unit\Controllers\Settings\Personalize;

use App\Http\Controllers\Settings\Personalize\PersonalizeUserViewModel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PersonalizeUserViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_view(): void
    {
        $user = User::factory()->create();
        $array = PersonalizeUserViewModel::data($user);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('users', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals(
            [
                'invite' => env('APP_URL') . '/settings/personalize/users/invite',
                'invite_store' => env('APP_URL') . '/settings/personalize/users/invite',
                'breadcrumb' => [
                    'home' => env('APP_URL') . '/profile',
                    'settings' => env('APP_URL') . '/settings/personalize',
                    'users' => env('APP_URL') . '/settings/personalize/users',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_dto_for_user(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Ross',
            'last_name' => 'Geller',
            'name_for_avatar' => 'Ross',
            'email' => 'ross.geller@gmail.com',
            'email_verified_at' => '2021-01-01 00:00:00',
        ]);
        $array = PersonalizeUserViewModel::dtoUser($user, $user);

        $this->assertCount(8, $array);
        $this->assertEquals(
            [
                'id' => $user->id,
                'name' => 'Ross Geller',
                'avatar' => [
                    'type' => User::AVATAR_TYPE_SVG,
                    'content' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 231 231"><path d="M33.83,33.83a115.5,115.5,0,1,1,0,163.34,115.49,115.49,0,0,1,0-163.34Z" style="fill:#0DC15C;"/><path d="m115.5 51.75a63.75 63.75 0 0 0-10.5 126.63v14.09a115.5 115.5 0 0 0-53.729 19.027 115.5 115.5 0 0 0 128.46 0 115.5 115.5 0 0 0-53.729-19.029v-14.084a63.75 63.75 0 0 0 53.25-62.881 63.75 63.75 0 0 0-63.65-63.75 63.75 63.75 0 0 0-0.09961 0z" style="fill:#ab5f2c;"/><path d="m141.75 195a114.79 114.79 0 0 1 38 16.5 115.53 115.53 0 0 1-128.46 0 114.79 114.79 0 0 1 38-16.5 115.77 115.77 0 0 1 15.71-2.53v-14.09a63.8 63.8 0 0 0 21 0v14.09a116.6 116.6 0 0 1 15.75 2.53z" style="fill:#000;"/><path d="m60.984 205.66 6.2675 2.2051 3.4074-6.819 2.8018-1.1353-3.9911 7.9907 27.222-3.0857 3.2541-11.739 2.1451-0.2692-3.2833 11.819 20.393-1.6011-14.191-15.945v-2.4379l17.606-5.7274 3.3855-0.473v1.47l-19.167 6.2295 14.731 16.542 19.839-7.7432 3.3636 0.8223-21.371 8.34 20.532 13.842 2.6777-21.687 1.9481 0.5604-2.7726 22.378 0.0584 0.0364 8.5075 4.9923-2.4807 0.85145-6.4718-3.7916-1.2987 6.0622-2.1524 0.53125 1.3425-6.2804-17.037 8.8348-5.0271 0.35661 21.59-11.193-20.962-14.133-7.5006 25.457-2.0721-0.0364 7.6392-25.915-21.05 1.652 9.0109 24.052-1.4155-0.0946-0.49615-0.0437-0.073-7e-3 -0.2043-0.0145-8.3688-22.342-10.127 19.242-1.9846-0.52399 10.514-19.962-26.04 2.9547 13.425 16.418-3.4438-1.0625-12.083-14.781-8.1645 5.9675-1.9043-1.077 8.128-5.9385-6.9898-2.4598 2.3348-1.2881zm92.509-7.2556 14.228 20.093-1.8095 0.89514-15.614-22.043z" style="fill:#fff;"/><path d="m109.99 15.57c-13.46 3.6301-19.789 11.95-24.069 24.08-6.9996-7-8.7307-10.82-7.5606-21.43a41 41 0 0 0-9.2698 24.988c0.0366 7.6776 5.6462 13.939 12.697 15.297-13.315 5.8106-15.258 22.033-14.045 33.524 5.7687-11.861 14.254-20.981 27.258-22.951-0.43017 6.6-2.5099 10.22-7.29 17.66 18.29-2.8601 25.119-7.8199 37.15-18.24 0.46001 0 1.0001 0.089 1.4606 0.12058-0.33023 3.5601-1.0906 6.5598-5.0004 12.46 9.5298-1.32 14.721-5.8006 17.539-11.671 8.8862 0.95314 15.836 6.785 21.26 14.818 1.928-15.211-4.4766-26.6-19.807-34.036 1.4167-2.6974 8.0143-11.925 17.661-15.721-1.424-0.28569-2.8883-0.49486-4.4033-0.61125-5.71-0.41992-13.62-0.99982-24.89 4.1703 2.8501-8.5101 10.21-11 18.05-13.12-15.131-1.2501-28.61-2.5898-40.53 8.1801-1.8997-6.21-0.18055-12.54 3.7889-17.52z" style="fill:#fff539;"/><path d="m172.63 69.954c1.2292 14.064 0.93841 29.96 0.34635 45.169 1.7887 6.796 3.0379 13.235 3.8842 18.388l0.13973-0.011c1.0001 6.56 2.3597 13.18 3.2698 19.73 2.0002-6.5699 2.5303-18.25 3.2405-25.43 1.2597-13 1.8296-29.311-0.43017-41.931-0.85041-4.72-2.0007-7.6896-2.0007-8.4796 4.6205 3.5601 8.6606 9.2204 13.001 14.15-0.6751-3.4318-1.347-6.6004-2.0567-9.5273-4.047-5.7183-13.726-12.154-19.393-12.06z" style="fill:none;"/><path d="m157.97 34.471c-10.339 2.7579-17.715 13.543-19.132 16.24 15.33 7.4361 20.783 17.96 21.278 33.517 5.9534 8.8179 10.066 20.289 12.857 30.895 0.87636-13.178 1.8186-27.726 0.26566-44.28 2.5698 0.44857 9.1372 1.3934 18.781 11.17-2.1158-8.7321-4.5671-15.31-8.4539-20.283-4.5598-5.8401-10.999-10.431-23.809-13 9.6502-3.34 16.27-0.76993 25.5 2.1301-8.1388-7.4315-16.474-14.219-27.287-16.389z" style="fill:#fff539;"/><path d="m61.473 73.354c-7.256-0.77501-13.024 2.3746-16.262 5.3879 0.73789-0.45409 1.3868-0.74208 1.8489-0.74208 0 0-1.5198 10.359-1.6197 11.519-1.56 19.73 0.99957 43.401 6.37 62.471 1.3099 4.6899 1.1895 3.0893 1.8898-0.9107 1.7526-10.061 3.3891-24.703 6.9739-38.864-5.068-17.627-4.2508-32.403 0.79937-38.861z" style="fill:none;"/><path d="m69.09 43.21c-0.0253 1.0803-8e-3 2.1612 0.0523 3.2402-3.8402 0-12.46 0.71984-16 2.1598-4.4504 1.8001-8.48 5.4801-11.67 11.83 7.2999-3.94 11.899-3.8502 16.66-1.8102-10.39 3.45-19.52 11.37-20.32 26.9 1.1456-1.5053 4.6079-4.9789 7.1393-6.6285 0.09-0.0587 0.17427-0.10556 0.26167-0.15946 3.7141-2.3211 9.0494-5.1247 15.181-4.9553-5.0501 6.4577-6.6824 20.434 0.28207 38.428 1.7866-7.0567 4.0574-13.994 7.0681-20.184-1e-3 -11.664 2.0764-27.774 15.391-33.585-7.0508-2.1538-12.709-7.991-14.043-15.236z" style="fill:#fff539;"/><path d="m83.739 83.92h63.533a19.101 19.1 0 0 1 19.051 19 19.111 19.11 0 0 1-19.051 19h-63.533a19.091 19.09 0 0 1-19.001-19 19.091 19.09 0 0 1 19.001-19z" style="fill:#0c00de;"/><path d="m140.23 93.54a9.3804 9.38 0 1 0 9.3804 9.38 9.3804 9.38 0 0 0-9.3804-9.38zm-49.402 0a9.3804 9.38 0 1 0 9.3804 9.38 9.3904 9.39 0 0 0-9.3804-9.38z" style="fill:#fff;"/><rect x="79.795" y="98.627" width="71.471" height="8.5859" ry="4.2929" style="fill:none;"/><path d="m100.19 152.09c2.8726 4.0616 9.8095 4.7232 15.119-0.45432 5.0656 4.5134 11.167 5.6898 15.495 0.31458" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.8949;stroke:#111;"/><path d="m109.67 135.53c-0.9758 0.0743-2.05 0.45327-3.1485 0.99414-4.3235 2.1399-7.3862 4.2557-10.639 7.1406-0.6251 0.5715 0.1168 0.77785 1.4238 0.87304 5.6967 0.0536 14.384 0.41404 15.098-0.875 1.9251-2.0788 1.7969-5.3303-0.1816-7.3008-0.701-0.67533-1.5769-0.90632-2.5527-0.83203zm11.656 0c-0.9758-0.0743-1.8517 0.1567-2.5527 0.83203-1.9785 1.9705-2.1067 5.222-0.1817 7.3008 0.7142 1.289 9.401 0.9286 15.098 0.875 1.307-0.0952 2.0489-0.30154 1.4238-0.87304-3.2524-2.8849-6.3151-5.0007-10.639-7.1406-1.0985-0.54087-2.1727-0.91985-3.1485-0.99414z" style="fill:none;"/></svg>',
                ],
                'email' => 'ross.geller@gmail.com',
                'verified' => true,
                'can_delete' => false,
                'permissions' => 'User',
                'url' => [
                    'destroy' => env('APP_URL') . '/settings/personalize/users/'.$user->id,
                ],
            ],
            $array
        );
    }
}
