<?php

namespace App\Http\Controllers\Tenant\Api\Git;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Git\GitIndex;
use Cache;

/**
 * Class GitController
 * @package App\Http\Controllers\Tenant\Api\Git
 */
class GitController  extends Controller
{
    /**
     * Index.
     * @param GitIndex $request
     * @return mixed
     */
    public function index(GitIndex $request)
    {
        Cache::forget('git_info');
        return git();
    }
}
