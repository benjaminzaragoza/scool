<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteApprovedTeacher;
use App\Http\Requests\DownloadMedia;
use App\Http\Requests\StoreApprovedTeacher;
use App\Models\Address;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Location;
use App\Models\PendingTeacher;
use App\Models\Teacher;
use App\Models\User;
use App\Repositories\PersonRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Permission\Models\Role;

/**
 * Class MediaController.
 *
 * @package App\Http\Controllers\Tenant
 */
class MediaController extends Controller
{
    /**
     * Download.
     *
     * @param DownloadMedia $request
     * @param $tenant
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(DownloadMedia $request, $tenant, Media $media)
    {
        return response()->download($media->getPath());
    }
}
