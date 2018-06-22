<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use Config;
use Illuminate\Http\Request;

/**
 * Class ProposeFreeUsernameController.
 *
 * @package App\Http\Controllers
 */
class ProposeFreeUsernameController extends Controller
{
    /**
     * Index.
     *
     * @param $tenant
     * @param $name
     * @param $sn1
     * @return string
     */
    public function index($tenant, $name, $sn1)
    {
        return $this->proposeUsername($name,$sn1);
    }

    /**
     * Propose username.
     *
     * @param $name
     * @param $sn1
     * @return string
     */
    private function proposeUsername($name, $sn1)
    {
        $originalUsername = propose_user_name($name,$sn1);
        $username = $originalUsername;
        $notFree = true;
        $i = 1;
        while ($notFree) {
            if (!User::findByEmail($username . '@' . Config::get('app.email_domain') )) {
                $notFree = false;
            } else {
                $username = $originalUsername . strval($i);
                $i++;
            }
        }
        return $username;
    }
}
