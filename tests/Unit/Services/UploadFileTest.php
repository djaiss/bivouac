<?php

namespace Tests\Unit\Services;

use App\Services\UploadFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadFileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_uploads_a_file(): void
    {
        $fakeFile = UploadedFile::fake()->image('photo1.jpg');

        $path = (new UploadFile)->execute([
            'file' => $fakeFile,
        ]);

        $this->assertEquals(
            storage_path('app/uploads/photo1.jpg'),
            $path
        );
    }
}
