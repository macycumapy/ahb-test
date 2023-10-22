<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Nomenclature;
use App\Services\NomenclatureUploaderService\NomenclatureUploader;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NomenclatureXlsxUploaderTest extends TestCase
{
    private readonly NomenclatureUploader $service;
    private Filesystem $testDisk;
    public function setUp(): void
    {
        parent::setUp();
        $this->service = app(NomenclatureUploader::class);
        $this->testDisk = Storage::disk('tests');
    }

    /**
     * @throws \Exception
     */
    public function testUpload()
    {
        Storage::fake('local');
        $this->assertTrue(
            $this->service->upload(new UploadedFile($this->testDisk->path('test_data.xlsx'), 'test_data.xlsx'))
        );

        $this->assertEquals(40, Nomenclature::all()->count());
    }
}
