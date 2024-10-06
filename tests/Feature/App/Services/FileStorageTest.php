<?php

namespace Tests\Feature\App\Services;

use App\Services\Storage\FileStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageTest extends TestCase
{
    public function test_store()
    {
        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video.mp4');
        $file = [
            'tmp_name' => $fakeFile->getPathName(),
            'name' => $fakeFile->getFileName(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $filePath = (new FileStorage())
            ->store('videos', $file);

        $this->assertTrue(true);

        Storage::assertExists($filePath);
    }
}
