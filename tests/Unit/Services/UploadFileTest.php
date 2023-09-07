<?php

namespace Tests\Unit\Services\File;

use App\Exceptions\EnvVariablesNotSetException;
use App\Models\File;
use App\Models\User;
use App\Services\UploadFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UploadFileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_file_object_as_administrator(): void
    {
        config(['app.uploadcare_public_key' => 'test']);
        config(['app.uploadcare_private_key' => 'test']);

        $michael = User::factory()->create();
        $this->executeService($michael);
    }

    /** @test */
    public function it_throws_an_exception_when_env_keys_are_not_set(): void
    {
        config(['app.uploadcare_public_key' => null]);
        config(['app.uploadcare_public_key' => null]);

        $michael = User::factory()->create();
        $this->expectException(EnvVariablesNotSetException::class);
        $this->executeService($michael);

        config(['app.uploadcare_public_key' => 'test']);
        $this->expectException(EnvVariablesNotSetException::class);
        $this->executeService($michael);

        config(['app.uploadcare_private_key' => 'test']);
        $this->executeService($michael);
    }

    private function executeService(User $michael): void
    {
        $request = [
            'organization_id' => $michael->organization_id,
            'user_id' => $michael->id,
            'uuid' => '017162da-e83b-46fc-89fc-3a7740db0a81',
            'name' => 'Twitter post.png',
            'original_url' => 'https://ucarecdn.com/5c8b9cea-62e5-4c8b-bc4c-47c0ddae62eee/',
            'cdn_url' => 'cdn_url',
            'mime_type' => 'image/jpg',
            'size' => 390340,
            'type' => 'avatar',
        ];

        $file = (new UploadFile)->execute($request);

        $this->assertInstanceOf(
            File::class,
            $file
        );

        $this->assertDatabaseHas('files', [
            'id' => $file->id,
            'uploader_id' => $michael->id,
            'uploader_name' => $michael->name,
            'uuid' => '017162da-e83b-46fc-89fc-3a7740db0a81',
            'name' => 'Twitter post.png',
            'original_url' => 'https://ucarecdn.com/5c8b9cea-62e5-4c8b-bc4c-47c0ddae62eee/',
            'cdn_url' => 'cdn_url',
            'mime_type' => 'image/jpg',
            'size' => 390340,
            'type' => 'avatar',
        ]);
    }
}
