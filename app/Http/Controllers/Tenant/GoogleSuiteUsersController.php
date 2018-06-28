<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteGoogleSuiteUser;
use App\Http\Requests\ListGoogleSuiteUser;
use App\Http\Requests\ShowGoogleSuiteUser;
use App\Http\Requests\StoreGoogleSuiteUser;
use Google_Service_Directory_User;
use Google_Service_Directory_UserName;
use PulkitJalan\Google\Facades\Google;

/**
 * Class GoogleSuiteUsersController.
 *
 * @package App\Http\Controllers
 */
class GoogleSuiteUsersController extends Controller
{
    /**
     * GoogleSuiteTestConnectionController constructor.
     */
    public function __construct()
    {
        tune_google_client();
    }

    public function index(ListGoogleSuiteUser $request)
    {
        $directory = Google::make('directory');
        try {
            $r = $directory->users->listUsers(array('domain' => 'iesebre.com', 'maxResults' => 500));
        } catch (\Exception $e) {
            dump('Error');
            dd($e);
            return $e;
        }
        return collect($r->users);
//        return $r;
    }

    /**
     * Show.
     *
     * @param ShowGoogleSuiteUser $request
     * @param $tenant
     * @param $email
     * @return array|\Exception|string
     */
    public function show(ShowGoogleSuiteUser $request, $tenant, $email)
    {
        $directory = Google::make('directory');

        try {
            $r = $directory->users->get($email);
        } catch (\Exception $e) {
            return $e;
        }
//        dd($r);
        if ($r->emails[0]['address'] === $email && $r->emails[0]['primary']) {
            return json_encode($r);
        } else {
            return [
                'result' => 'Error',
                'message' => 'User ' . $email . ' not found!'
            ];
        }
    }

//https://github.com/google/google-api-php-client-services/blob/master/src/Google/Service/Directory/Resource/Users.php

    public function store(StoreGoogleSuiteUser $request)
    {

        $user = new Google_Service_Directory_User();
        $name = new Google_Service_Directory_UserName();
        $new_person = array();
// SET THE ATTRIBUTES
        $name->setGivenName('Tester1');
        $name->setFamilyName('Testerton1');
        $user->setName($name);
        $user->setHashFunction("crypt");
        $user->setPrimaryEmail("testy1.testerton@iesebre.com");
        $user->setPassword('$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'); //SECRET
// the JSON object shows us that externalIds is an array, so that's how we set it here
        $user->setExternalIds(array("value"=>28790,"type"=>"custom","customType"=>"EmployeeID"));

        $directory = Google::make('directory');

        try {
            $result = $directory->users->insert($user);
        } catch (\Exception $e) {
            dump('ERROR');
            dd($e);
            return $e;
        }

        return $result;
    }

    /**
     * Destroy.
     *
     * @param DeleteGoogleSuiteUser $request
     * @param $tenant
     * @param $email
     * @return \Exception
     */
    public function destroy(DeleteGoogleSuiteUser $request, $tenant, $email)
    {

        $directory = Google::make('directory');
        try {
            $result = $directory->users->delete($email);
            dump($result);
        } catch (\Exception $e) {
            dump('ERROR');
            dd($e);
            return $e;
        }

        return $result;
    }

    /**
     * Undelete.
     *
     * @param DeleteGoogleSuiteUser $request
     * @param $tenant
     * @param $email
     * @return \Exception
     */
    public function undelete(DeleteGoogleSuiteUser $request, $tenant, $email)
    {

        $directory = Google::make('directory');
        dd(get_class($directory->users));
        try {
            //Google_Service_Directory_UserUndelete
            //See users.list(showDeleted=true) to get a list of all accounts that have been deleted in the past 5 days and convert the email address into an id If the primary address isn't enough to go off of (again, 2+ accounts created with that same primary addresss in past 5 days) then you can also look at creationTime and lastLoginTime to determine which is the correct account to undelete.
            // Cal aconseguir el id de l'usuari de la llista dels usuaris esborrat últimament
            // HI ha 20 dies per recuperar els últims usuaris esborrats!
            $result = $directory->users->undelete($id);
            dump($result);
        } catch (\Exception $e) {
            dump('ERROR');
            dd($e);
            return $e;
        }

        return $result;
    }
}
