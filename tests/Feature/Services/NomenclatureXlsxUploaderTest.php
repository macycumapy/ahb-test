<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Nomenclature;
use App\Services\NomenclatureUploaderService\NomenclatureXlsxUploader;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NomenclatureXlsxUploaderTest extends TestCase
{
    private readonly NomenclatureXlsxUploader $service;
    private Filesystem $testDisk;
    public function setUp(): void
    {
        parent::setUp();
        $this->service = app(NomenclatureXlsxUploader::class);
        $this->testDisk = Storage::disk('tests');
    }

    public function testUpload()
    {
        $this->assertTrue(
            $this->service->upload($this->testDisk->get('test_data.xlsx'))
        );

        $this->assertEquals(40, Nomenclature::all()->count());
    }
}
