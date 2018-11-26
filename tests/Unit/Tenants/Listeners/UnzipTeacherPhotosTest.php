<?php

namespace Tests\Unit\Tenants\Listeners;

use App\Events\TeacherPhotosZipUploaded;
use File;
use Storage;
use Tests\TestCase;

/**
 * Class UnzipTeacherPhotosTest.
 *
 * @package Tests\Unit
 */
class UnzipTeacherPhotosTest extends TestCase
{
    /** @test */
    public function teacher_photos_are_unzipped_after_event()
    {
        Storage::fake('local');
        $files = File::allFiles(base_path('tests/__Fixtures__/photos/teachers_zip'));
        Storage::disk('local')->putFileAs('tenant_test/teacher_photos_zip', $files[0], 'teachers.zip');

        event(new TeacherPhotosZipUploaded('teacher_photos_zip/teachers.zip','tenant_test'));

        Storage::disk('local')->assertExists('tenant_test/teacher_photos/040 - TUR, Sergi.jpg');
        Storage::disk('local')->assertExists('tenant_test/teacher_photos/041 - Pardo, Jeans.jpg');
        Storage::disk('local')->assertExists('tenant_test/teacher_photos/042 - Parda, Jeans Parda.jpg');
    }
}
