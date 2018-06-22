<?php

namespace App\Http\Controllers;

use App\Events\TenantCreated;
use App\Events\TenantDeleted;
use App\Http\Requests\CreateUserTenant;
use App\Tenant;
use Illuminate\Http\Request;

/**
 * Class UserTenantController.
 *
 * @package App\Http\Controllers
 */
class UserTenantController extends Controller
{
    /**
     * @param Request $request
     * @param $tenant
     */
    public function index(Request $request)
    {
        return $request->user()->tenants;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserTenant $request
     */
    public function store(CreateUserTenant $request)
    {
        $tenant = $request->user()->addTenant($tenant = Tenant::create([
            'name' => $request->name,
            'subdomain' => $request->subdomain,
            // TODO -> Ask users for email_domain
            'email_domain' => $request->subdomain . '.com',
            'hostname' => 'localhost',
            'database' => $request->subdomain,
            'username' => $request->subdomain,
            'password' => str_random(),
            'port' => 3306
        ]));
        event(new TenantCreated($tenant,$request->password));
        return $tenant;
    }


    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        event(new TenantDeleted($tenant));
        return $tenant;
    }
}

