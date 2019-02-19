<?php

namespace Tests\Feature\Tenants\Api\Moodle;

use App\Moodle\Clients\Adapters\RestClient;
use App\Moodle\Connection;
use App\Moodle\Services\Course;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class MoodleTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class BasicObsoletMoodleTest extends BaseTenantTest{

    use RefreshDatabase;

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate',[
            '--path' => 'database/migrations/tenant'
        ]);

        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function moodle_test1()
    {
        $token = env('MOODLE_TOKEN');
        $domainname = 'https://www.iesebre.com/moodle';
        $serverurl = 'https://www.iesebre.com/moodle';
//        $serverurl = $domainname . '/webservice/rest/server.php';
        dump($serverurl);
        $connection = new Connection($serverurl, $token);
        $client = new RestClient($connection);
        $courseService = new Course($client);
        $courses = $courseService->getAll();
//        dd($courses);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function moodle_test_list_all_users()
    {
        // NOTWORKING
//        https://moodle.org/mod/forum/discuss.php?d=353543
        $token = env('MOODLE_TOKEN');
        $domainname = 'https://www.iesebre.com/moodle';
        $functionname = 'core_user_get_users';
        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new \GuzzleHttp\Client();
        $params = [
            'criteria' => [
                ['key' => 'email', 'value' => '%']
//                ['key' => 'deleted', 'value' => false]
            ]
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
//        dump('XML RESPONSE BODY:');
//        dump((string)($res->getBody()));
        $result = json_decode($res->getBody());
        //        dump(count($result));
        dump(count($result->users));
        var_dump($result->users);

//        dump($result);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function moodle_test_list_all_enrolled_users()
    {
        // NOTWORKING
//        https://moodle.org/mod/forum/discuss.php?d=353543
        $token = env('MOODLE_TOKEN');
        $domainname = 'https://www.iesebre.com/moodle';
        $functionname = 'core_enrol_get_enrolled_users';
        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new \GuzzleHttp\Client();
        $params = [
            'courseid' => 0
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        dump('XML RESPONSE BODY:');
        dump((string)($res->getBody()));
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function moodle_test_list_enrolled_users()
    {
        $token = env('MOODLE_TOKEN');
        $domainname = 'https://www.iesebre.com/moodle';
        $functionname = 'core_enrol_get_enrolled_users';
        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new \GuzzleHttp\Client();
        $params = [
            'courseid' => 940
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        dump($res->getStatusCode());
//        dump('RESPONSE BODY:');
//        dump((string)($res->getBody()));
        dump($res->getHeaderLine('content-type'));
//        dump('RESPONSE:');
//        dump($res);
//        dump('RESPONSE BODY:');
//        dump($res->getBody());
//        dump('JSON RESPONSE BODY:');
//        dump(json_decode($res->getBody()));
        dump('XML RESPONSE BODY:');
        dump((string)($res->getBody()));
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function moodle_test_list_users()
    {
        $token = env('MOODLE_TOKEN');
        $domainname = 'https://www.iesebre.com/moodle';
        $functionname = 'core_user_view_user_list';
        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new \GuzzleHttp\Client();
        $params = [
            'courseid' => 940
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        dump($res->getStatusCode());
//        dump('RESPONSE BODY:');
//        dump((string)($res->getBody()));
        dump($res->getHeaderLine('content-type'));
//        dump('RESPONSE:');
//        dump($res);
//        dump('RESPONSE BODY:');
//        dump($res->getBody());
//        dump('JSON RESPONSE BODY:');
//        dump(json_decode($res->getBody()));
        dump('XML RESPONSE BODY:');
        dump((string)($res->getBody()));
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function moodle_test_list_users_obsolet()
    {
        $token = env('MOODLE_TOKEN');
        $domainname = 'https://www.iesebre.com/moodle';
        $functionname = 'core_user_view_user_list';
        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new \GuzzleHttp\Client();
        $params = [
            'courseid' => 940
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        dump($res->getStatusCode());
//        dump('RESPONSE BODY:');
//        dump((string)($res->getBody()));
        dump($res->getHeaderLine('content-type'));
//        dump('RESPONSE:');
//        dump($res);
//        dump('RESPONSE BODY:');
//        dump($res->getBody());
//        dump('JSON RESPONSE BODY:');
//        dump(json_decode($res->getBody()));
        dump('XML RESPONSE BODY:');
        dump((string)($res->getBody()));
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function moodle_test_create_users()
    {
//        dump(env('MOODLE_TOKEN'));
        $token = env('MOODLE_TOKEN');
        $domainname = 'https://www.iesebre.com/moodle';
        $functionname = 'core_user_create_users';
        // REST RETURNED VALUES FORMAT
        $restformat = 'json'; //Also possible in Moodle 2.2 and later: 'json'
        //Setting it to 'json' will fail all calls on earlier Moodle version
        //////// moodle_user_create_users ////////
        /// PARAMETERS - NEED TO BE CHANGED IF YOU CALL A DIFFERENT FUNCTION
        $user1 = new \stdClass();
        $user1->username = 'testusername1';
        $user1->password = 'testpassword1';
        $user1->firstname = 'testfirstname1';
        $user1->lastname = 'testlastname1';
            $user1->email = 'testemail1@moodle.com';
        $user1->auth = 'manual';
        $user1->idnumber = 'testidnumber1';
        $user1->lang = 'en';
//        $user1->theme = 'standard';
//        $user1->timezone = '-12.5';
//        $user1->mailformat = 0;
        $user1->description = 'Hello World!';
        $user1->city = 'testcity1';
        $user1->country = 'au';
//        $preferencename1 = 'preference1';
//        $preferencename2 = 'preference2';
//        $user1->preferences = array(
//            array('type' => $preferencename1, 'value' => 'preferencevalue1'),
//            array('type' => $preferencename2, 'value' => 'preferencevalue2'));
        $user2 = new \stdClass();
        $user2->username = 'testusername2';
        $user2->password = 'testpassword2';
        $user2->firstname = 'testfirstname2';
        $user2->lastname = 'testlastname2';
        $user2->email = 'testemail2@moodle.com';
        $user2->timezone = 'Pacific/Port_Moresby';
        $users = array($user1, $user2);
        $params = ['users' => $users];
//        dump($params);
        /// REST CALL
//        header('Content-Type: text/plain');
        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
//        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
//        require_once('./curl.php');
//        $curl = new curl;
//        //if rest format == 'xml', then we do not add the param for backward compatibility with Moodle < 2.2
//        $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
//        $resp = $curl->post($serverurl . $restformat, $params);
//        print_r($resp);
//        dump($serverurl);
        $client = new \GuzzleHttp\Client();
//        dump($params);
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
//        dump('RESPONSE:');
//        dump($res);
//        dd('dsasad');
        dump($res->getStatusCode());
        // 200
        dump($res->getHeaderLine('content-type'));
        // 'application/json; charset=utf8'
//        dump('RESPONSE:');
//        dump($res);
//        dump('RESPONSE BODY:');
//        dump($res->getBody());
        dump('JSON RESPONSE BODY:');
        dump(json_decode($res->getBody()));
        dump('XML RESPONSE BODY:');
        dump((string)($res->getBody()));
        // '{"id": 1420053, "name": "guzzle", ...}'
    }


}
