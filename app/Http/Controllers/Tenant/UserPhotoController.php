<?php

namespace App\Http\Controllers\Tenant;

use App\Events\UserPhotoRemoved;
use App\Events\UserPhotoUploaded;
use App\Http\Requests\DeleteUserPhoto;
use App\Http\Requests\StoreUserPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Storage;

/**
 * Class UserPhotoController.
 *
 * @package App\Http\Controllers
 */
class UserPhotoController extends Controller
{
    /**
     * Show user photo.
     *
     * @param Request $request
     * @param $tenant
     * @param User $user
     * @return
     */
    public function show(Request $request, $tenant, User $user)
    {
        if (! $user->photo || ! Storage::disk('local')->exists($user->photo)) {
//            return response()->file(Storage::disk('local')->path(
//                $this->basePath($tenant,User::DEFAULT_PHOTO_PATH)));
            return response()->file(public_path(User::DEFAULT_PHOTO_PATH));
        }
        return response()->file(Storage::disk('local')->path($user->photo));
    }

    /**
     * Download.
     *
     * @param Request $request
     * @param $tenant
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request, $tenant, User $user)
    {
        if (! $user->photo || ! Storage::disk('local')->exists($user->photo)) {
            return response()->download(Storage::disk('local')->path(
                $this->basePath($tenant,User::DEFAULT_PHOTO_PATH)));
        }
        return response()->download(Storage::disk('local')->path($user->photo));
    }

    /**
     * Store.
     *
     * @param StoreUserPhoto $request
     * @param $tenant
     * @param User $user
     * @return false|string
     */
    public function store(StoreUserPhoto $request, $tenant, User $user)
    {
        $name = $user->photo_name;
        $ext = pathinfo($request->photo->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext) {
            $name = $name . '.' . $ext;
        }

        $path = $request->file('photo')->storeAs(
            $tenant. '/'. User::PHOTOS_PATH,
            $name
        );

        $user->photo = $path;
        $user->photo_hash = md5($path);
        $user->save();
        event(new UserPhotoUploaded($path));

        return $path;
    }

    /**
     * Tenant base path
     *
     * @param $tenant
     * @param $path
     * @return string
     */
    protected function basePath($tenant , $path)
    {
        return $tenant. '/' . $path;
    }

    /**
     * Destroy.
     * @param StoreUserPhoto $request
     * @param $tenant
     * @param User $user
     * @return mixed
     */
    public function destroy(DeleteUserPhoto $request, $tenant, User $user)
    {
        $path = $user->photo;
        $user->photo = null;
        $user->photo_hash = null;
        $user->save();
        event(new UserPhotoRemoved($path));

        return $path;
    }
}
