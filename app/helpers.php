<?php

use App\GoogleGSuite\GoogleDirectory;
use App\Models\Address;
use App\Models\AdministrativeStatus;
use App\Models\Course;
use App\Models\Department;
use App\Models\Family;
use App\Models\Force;
use App\Models\GoogleUser;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Incident;
use App\Models\IncidentTag;
use App\Models\Lesson;
use App\Models\Location;
use App\Models\Menu;
use App\Models\Module;
use App\Models\PendingTeacher;
use App\Models\Person;
use App\Models\Position;
use App\Models\Province;
use App\Models\Setting;
use App\Models\Specialty;
use App\Models\Job;
use App\Models\JobType;
use App\Models\StudyTag;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Models\SubjectGroupTag;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserType;
use App\Models\WeekLesson;
use App\Models\Log;
use App\Models\MoodleUser;
use App\Notifications\SampleNotification;
use App\Repositories\PersonRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;
use App\Revisionable\Revision;
use App\Models\Study;
use App\Tenant;
use Carbon\Carbon;
use PulkitJalan\Google\Client;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Role as ScoolRole;

/**
 * Create model.
 *
 * @param $class
 * @param array $attributes
 * @param int $times
 * @return mixed
 */
function create($class, $attributes = [], $times = 1)
{
    $data = factory($class)->times($times)->create($attributes);
    if ($times > 1) {
        return $data;
    }
    return $data->first();
}

if (! function_exists('scool_menu')) {

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    function scool_menu()
    {
        return Menu::all();
    }
}


if (! function_exists('tenant')) {

    /**
     * tenant.
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    function tenant()
    {
        $tenant = [
            'name' => Config::get('app.name'),
            'subdomain' => Config::get('app.subdomain'),
            'email_domain' => Config::get('app.email_domain'),
            'pusher_app_key' => env('PUSHER_APP_KEY')
        ];

        if (app()->environment() === 'local') {
            $tenant['pusher_app_key'] = Config::get('broadcasting.connections.pusher_tenant.key');
        }

        if (app()->environment() === 'production') {
            $tenant['pusher_app_key'] = Config::get('broadcasting.connections.pusher_tenant_production.key');
        }

        return collect($tenant);
    }
}

if (! function_exists('create_tenant')) {

    /**
     * Create Tenant.
     *
     * @param $name
     * @param $subdomain
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    function create_tenant($name,$subdomain)
    {
        return Tenant::create([
            'name' => $name,
            'subdomain' => $subdomain,
            'email_domain' => '@' . $subdomain . '.com',
            'hostname' => 'localhost',
            'username' => $subdomain,
            'password' => 'secret',
            'database' => $subdomain,
            'port' => 3306
        ]);
    }
}

if (! function_exists('tenant_connect')) {
    /**
     * Establish a tenant database connection.
     *
     * @param $hostname
     * @param $username
     * @param $password
     * @param $database
     */
    function tenant_connect($hostname, $username, $password, $database)
    {
        // Erase the tenant connection, thus making Laravel get the default values all over again.
        DB::purge('tenant');

        // Make sure to use the database name we want to establish a connection.
        Config::set('database.connections.tenant.host', $hostname);
        Config::set('database.connections.tenant.database', $database);
        Config::set('database.connections.tenant.username', $username);
        Config::set('database.connections.tenant.password', $password);

        // Rearrange the connection data
        DB::reconnect('tenant');

        // Ping the database. This will throw an exception in case the database does not exists.
        Schema::connection('tenant')->getConnection()->reconnect();
    }
}

if (! function_exists('main_connect')) {
    function main_connect()
    {
        restore_connect(config('scool.main_database'));
    }
}

if (! function_exists('restore_connect')) {
    function restore_connect($connection)
    {
        // Erase the tenant connection, thus making Laravel get the default values all over again.
        DB::purge('tenant');

        Config::set('database.default', $connection);

        // Ping the database. This will throw an exception in case the database does not exists.
        Schema::connection($connection)->getConnection()->reconnect();
    }
}

if (! function_exists('create_admin_user_on_subdomain')) {
    /**
     * @param $subdomain
     */
    function create_admin_user_on_subdomain($subdomain)
    {
        $tenant = Tenant::findBySubdomain($subdomain);
        tenant_connect(
            $tenant->hostname,
            $tenant->username,
            $tenant->password,
            $tenant->database
        );
        create_tenant_admin_user();
    }
}

if (! function_exists('create_admin_user_on_tenant')) {
    /**
     * @param $user
     * @param $tenant
     */
    function create_admin_user_on_tenant($user, $tenant, $password = null)
    {
        tenant_connect(
            $tenant->hostname,
            $tenant->username,
            $tenant->password,
            $tenant->database
        );

        if(!$password) $password = str_random();

        $existingUser = App\Models\User::where('email',$user->email)->first();
        if (!$existingUser) {
            User::forceCreate([
                'name' => $user->name,
                'email' => $user->email,
                'password' => sha1($password),
                'admin' => true
            ]);
        }
        DB::purge('tenant');
    }
}


if (! function_exists('create_tenant_admin_user')) {
    function create_tenant_admin_user()
    {
        if (! App\User::where('email',config('scool.admin_user_email'))->first()) {
            User::forceCreate([
                'name' => config('scool.admin_user_name_on_tenant'),
                'email' => config('scool.admin_user_email_on_tenant'),
                'password' => is_sha1($password = config('scool.admin_username_password_on_tenant')) ? $password : sha1($password),
                'admin' => true
            ]);
        }
    }
}

if (! function_exists('is_sha1')) {
    function is_sha1($str) {
        return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
    }
}

if (! function_exists('create_admin_user')) {
    /**
     *
     */
    function create_admin_user()
    {
        if (! App\User::where('email',config('scool.admin_user_email'))->first()) {
            App\User::forceCreate([
                'name' => config('scool.admin_user_name'),
                'email' => config('scool.admin_user_email'),
                'password' => bcrypt(config('scool.admin_user_password')),
                'admin' => true
            ]);
        }
    }
}

if (! function_exists('create_tenant_user_without_permissions')) {
    /**
     *
     */
    function create_tenant_user_without_permissions()
    {
        if (! App\Models\User::where('email',config('scool.user_without_permissions_email'))->first()) {
            App\Models\User::forceCreate([
                'name' => config('scool.user_without_permissions_name'),
                'email' => config('scool.user_without_permissions_email'),
                'password' => config('scool.user_without_permissions_password') // HASHED SHA1 123456
            ]);
        }
    }
}

if (! function_exists('create_other_tenant_admin_users')) {
    /**
     *
     */
    function create_other_tenant_admin_users()
    {
        if (! App\Models\User::where('email',config('scool.admin_user_email_on_tenant1'))->first()) {
            App\Models\User::forceCreate([
                'email' => config('scool.admin_user_email_on_tenant1'),
                'name' => config('scool.admin_user_name_on_tenant1'),
                'password' => config('scool.admin_username_password_on_tenant1'),
                'admin' => true,
                'user_type_id' => UserType::findByName('Becari')->id
            ]);
        }
    }
}

if (! function_exists('create_iesebre_tenant')) {
    function create_iesebre_tenant()
    {
        return Tenant::create([
            'name' => config('iesebre.name',"Institut de l'Ebre") ,
            'subdomain' => config('iesebre.subdomain','iesebre') ,
            'email_domain' => config('iesebre.email_domain','iesebre.com') ,
            'hostname' =>  config('iesebre.database_host','localhost'),
            'database' => config('iesebre.database_name','iesebre'),
            'username' => config('iesebre.database_username','iesebre'),
            'password' => config('iesebre.database_password',str_random()),
            'port' => config('iesebre.database_port',3306),
            'gsuite_service_account_path' => config('iesebre.gsuite_account_path','/gsuite_service_accounts/scool-07eed0b50a6f.json'),
            'gsuite_admin_email' => config('iesebre.gsuite_admin_email','sergitur@iesebre.com'),
            'pusher_app_id' => config('iesebre.pusher_app_id',668468),
            'pusher_app_name' => config('iesebre.pusher_app_name',"Institut de l'Ebre"),
            'pusher_app_key' => config('iesebre.pusher_app_key','6f627646afb1261d5b50'),
            'pusher_app_secret' => config('iesebre.pusher_app_secret',''),
            'pusher_enable_client_messages' => config('iesebre.pusher_enable_client_messages',true),
            'pusher_enable_statistics' => config('iesebre.pusher_enable_statistics',true)
        ]);
    }
}

if (! function_exists('remove_default_tenant')) {
    function remove_default_tenant() {
        delete_mysql_database('iesebre');
    }
}

if (! function_exists('create_default_tenant')) {
    function create_default_tenant() {
        main_connect();
        $user = \App\User::find(1);
        $tenant = Tenant::where('subdomain','iesebre')->first();
        if (! $tenant) {
            $tenant = $user->addTenant($tenant = create_iesebre_tenant());
        }
        $tenant_user = $tenant->user;
        create_mysql_full_database(
            $tenant->database,
            $tenant->username ,
            $tenant->password,
            $tenant->hostname);
        create_admin_user_on_tenant($tenant_user, $tenant, config('scool.admin_username_password_on_tenant'));
        DB::purge('tenant');
        main_connect();
    }
}

if (! function_exists('save_current_tenant_config')) {
    /**
     * @return object
     */
    function save_current_tenant_config()
    {
        $host = Config::get('database.connections.tenant.host');
        $database = Config::get('database.connections.tenant.database');
        $username = Config::get('database.connections.tenant.username');
        $password = Config::get('database.connections.tenant.password');

        return (object) compact('host', 'database', 'username', 'password');
    }
}

if (! function_exists('restore_current_tenant_config')) {
    /**
     * @param $oldConfig
     */
    function restore_current_tenant_config($oldConfig)
    {
        Config::set('database.connections.tenant.host', $oldConfig->host);
        Config::set('database.connections.tenant.database', $oldConfig->database);
        Config::set('database.connections.tenant.username', $oldConfig->username);
        Config::set('database.connections.tenant.password', $oldConfig->password);
    }
}

if (! function_exists('test_user')) {
    /**
     * @param $user
     * @param $tenant
     * @return array
     */
    function test_user($user, $tenant, $password) {
        $current_config = save_current_tenant_config();
        $result = [];
        try {
            tenant_connect($tenant->hostname, $tenant->username, $tenant->password, $tenant->database);

            $tenantUser = User::where('email',$user->email)->firstOrFail();

            if (Hash::check($password, $tenantUser->password)) {
                $result = [ 'connection' => 'ok' ];
            } else {
                $result = [
                    'connection' => 'Error',
                    'exception' => 'Password incorrect for user ' . $user->email
                ];
            }


        } catch (PDOException $e) {
            $result = [
                'connection' => 'Error',
                'exception' => $e->getMessage()
            ];
        }

        restore_current_tenant_config($current_config);
        return $result;
    }
}

if (! function_exists('test_connection')) {
    /**
     * @param $hostname
     * @param $username
     * @param $password
     * @param $database
     * @return array
     */
    function test_connection($hostname, $username, $password, $database)
    {
        $current_config = save_current_tenant_config();
        $result = [];
        try {
            tenant_connect($hostname, $username, $password, $database);
            $result = [ 'connection' => 'ok' ];
        } catch (PDOException $e) {
            $result = [
                'connection' => 'Error',
                'exception' => $e->getMessage()
            ];
        }
        restore_current_tenant_config($current_config);

        return $result;
    }
}


if (! function_exists('tenant_migrate')) {
    /**
     * Run Tenant Migrations in the connected tenant database.
     */
    function tenant_migrate()
    {
        Config::set('auth.providers.users.model',User::class);

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true
        ]);
    }
}

if (! function_exists('tenant_seed')) {
    /**
     * Run Tenant Migrations in the connected tenant database.
     */
    function tenant_seed()
    {
        Config::set('auth.providers.users.model',User::class);

        Artisan::call('db:seed', [
            '--database' => 'tenant',
            '--class' => 'TenantDatabaseSeeder',
            '--force' => true
        ]);
    }
}

/**
 * @param $name
 * @param $user
 * @param null $password
 * @return null|string
 */
function create_mysql_full_database($name, $user , $password = null, $host = null)
{
    create_mysql_database($name);

    $password = create_mysql_user($user, $password, $host);
    mysql_grant_privileges($user, $name, $host);

    $hostname = 'localhost';
    if ($host) $hostname = $host;
    tenant_connect($hostname, $name, $password, $name);
    tenant_migrate();
    tenant_seed();

    return $password;
}

if (! function_exists('tenant_connect_migrate_seed')) {

    /**
     * @param $name
     */
    function tenant_connect_migrate_seed($name)
    {
        $tenant = Tenant::findBySubDomain($name);
        tenant_connect($tenant->hostname, $tenant->subdomain, $tenant->password, $tenant->subdomain);
        tenant_migrate();
        tenant_seed();
    }
}

if (! function_exists('delete_mysql_full_database')) {
    /**
     * @param $name
     * @param $user
     * @param null $password
     * @return null|string
     */
    function delete_mysql_full_database($name, $user, $host = null)
    {
        delete_mysql_database($name);
        delete_mysql_user($user, $host);
    }
}

if (! function_exists('create_mysql_database')) {
    /**
     * @param $name
     */
    function create_mysql_database($name)
    {
        DB::connection('mysql_admin')->getPdo()->exec("CREATE DATABASE IF NOT EXISTS `{$name}`");
    }
}

if (! function_exists('delete_mysql_database')) {
    /**
     * @param $name
     */
    function delete_mysql_database($name)
    {
        DB::connection('mysql_admin')->getPdo()->exec("DROP DATABASE IF EXISTS `{$name}`");
    }
}

if (! function_exists('remove_mysql_database')) {
    /**
     * @param $name
     */
    function remove_mysql_database($name)
    {
        delete_mysql_database($name);
    }
}

if (! function_exists('create_mysql_user')) {
    /**
     * @param $name
     * @param null $password
     * @param string $host
     * @return null|string
     */
    function create_mysql_user($name, $password = null, $host = 'localhost')
    {
        if (!$password) $password = str_random();
        DB::connection('mysql_admin')->getPdo()->exec(
            "CREATE USER IF NOT EXISTS '{$name}'@'{$host}'");
        DB::connection('mysql_admin')->getPdo()->exec(
            "ALTER USER '{$name}'@'{$host}' IDENTIFIED BY '{$password}'");
        return $password;
    }
}

if (! function_exists('delete_mysql_user')) {
    /**
     * @param $name
     * @param null $password
     * @param string $host
     * @return null|string
     */
    function delete_mysql_user($name, $host = 'localhost')
    {
        DB::connection('mysql_admin')->getPdo()->exec(
            "DROP USER IF EXISTS '{$name}'@'{$host}'");
    }
}

if (! function_exists('mysql_grant_all_privileges')) {
    /**
     * @param $user
     * @param string $host
     */
    function mysql_grant_all_privileges($user, $host = 'localhost') {
        DB::connection('mysql_admin')->getPdo()->exec(
            "GRANT ALL PRIVILEGES ON *.* TO '{$user}'@'{$host}' WITH GRANT OPTION");
        DB::connection('mysql_admin')->getPdo()->exec("FLUSH PRIVILEGES");
    }
}

if (! function_exists('mysql_grant_privileges')) {
    /**
     * @param $user
     * @param $database
     * @param string $host
     */
    function mysql_grant_privileges($user, $database, $host = 'localhost') {
        DB::connection('mysql_admin')->getPdo()->exec(
            "GRANT ALL PRIVILEGES ON {$database}.* TO '{$user}'@'{$host}' WITH GRANT OPTION");
        DB::connection('mysql_admin')->getPdo()->exec("FLUSH PRIVILEGES");
    }
}

if (!function_exists('get_tenant')) {
    /**
     * @param $name
     * @return mixed
     */
    function get_tenant($name) {
        $previousConnection = config('database.default');
        main_connect();
        $tenant= \App\Tenant::where('subdomain', $name)->firstOrFail();
        restore_connect($previousConnection);
        return $tenant;
    }
}


if (!function_exists('formatted_logged_user')) {
    function formatted_logged_user()
    {
        return json_encode(optional(Auth::user())->map());
    }
}

if (!function_exists('moodle_manager_permissions')) {
    function moodle_manager_permissions()
    {
        return [
            'moodle.index',
            'moodle.user.index',
            'moodle.user.show',
            'moodle.user.store',
            'moodle.user.destroy',
            'people.store',
            'moodle.user.password.update'
        ];
    }
}

if (!function_exists('teacher_permissions')) {
    function teacher_permissions()
    {
        return [
            'positions.index',
        ];
    }
}

if (!function_exists('student_permissions')) {
    function student_permissions()
    {
        return [
            'positions.index',
        ];
    }
}

if (!function_exists('positions_manager_permissions')) {
    function positions_manager_permissions()
    {
        return [
            'positions.index',
            'positions.store',
            'positions.show',
            'positions.update',
            'positions.destroy',
            'positions.tags.index',
            'positions.tags.store',
            'positions.tags.show',
            'positions.tags.update',
            'positions.tags.destroy',
            'positions.send.email'
        ];
    }
}

if (!function_exists('curriculum_manager_permissions')) {
    function curriculum_manager_permissions()
    {
        return [
            'curriculum.index',
            'studies.index',
            'studies.store',
            'studies.show',
            'studies.update',
            'studies.destroy',
            'studies.tags.index',
            'studies.tags.store',
            'studies.tags.show',
            'studies.tags.update',
            'studies.tags.destroy',
            'tagged.studies.store',
            'tagged.studies.destroy',
            'subjects.index',
            'subjects.store',
            'subjects.show',
            'subjects.update',
            'subjects.destroy',
            'subjectGroups.index',
            'subjectGroups.store',
            'subjectGroups.show',
            'subjectGroups.update',
            'subjectGroups.destroy',
            'subjectGroups.tags.index',
            'subjectGroups.tags.store',
            'subjectGroups.tags.show',
            'subjectGroups.tags.update',
            'subjectGroups.tags.destroy',
            'tagged.subjectGroups.store',
            'tagged.subjectGroups.destroy',
        ];
    }
}

if (!function_exists('incidents_manager_permissions')) {
    function incidents_manager_permissions()
    {
        return [

        ];
    }
}

if (!function_exists('users_manager_permissions')) {
    function users_manager_permissions()
    {
        return [
            'moodle.index',
            'moodle.user.index',
            'moodle.user.show',
            'moodle.user.store',
            'moodle.user.destroy',
            'people.update',
            'people.index',
            'people.show',
            'people.store',
            'moodle.user.password.update',
            'ldap.users.index',
            'ldap.users.update'
        ];
    }
}

if (!function_exists('ldap_manager_permissions')) {
    function ldap_manager_permissions()
    {
        return [
            'ldap.users.index',
            'ldap.users.update'
        ];
    }
}

if (!function_exists('notifications_manager_permissions')) {
    function notifications_manager_permissions()
    {
        return [
            'notifications.index',
            'notifications.destroy',
            'notifications.destroyMultiple',
            'notifications.simple.store',
        ];
    }
}

if (!function_exists('people_manager_permissions')) {
    function people_manager_permissions()
    {
        return [
            'people.index',
            'people.show',
            'people.store',
            'people.update'
        ];
    }
}

if (!function_exists('curriculum_permissions')) {
    function curriculum_permissions()
    {
        return [
//            'people.store',
//            'people.update'
        ];
    }
}

if (!function_exists('scool_permissions')) {
    function scool_permissions()
    {
        $permissions = [
            'incident.list',
            'incident.show',
            'incident.store',
            'incident.update',
            'incident.destroy',
            'incident.open',
            'incident.close',
            'reply.update'
        ];

        $permissions = array_merge($permissions,moodle_manager_permissions());
        $permissions = array_merge($permissions,people_manager_permissions());
        $permissions = array_merge($permissions,curriculum_manager_permissions());
        $permissions = array_merge($permissions,positions_manager_permissions());
        $permissions = array_merge($permissions,ldap_manager_permissions());
        return $permissions;
    }
}

if (!function_exists('scool_roles')) {
    function scool_roles()
    {
        return ScoolRole::ROLES;
    }
}

if (!function_exists('scool_roles_permissions')) {
    function scool_roles_permissions()
    {
        return [
            'MoodleManager' => moodle_manager_permissions(),
            'UsersManager' => users_manager_permissions(),
            'LdapManager' => ldap_manager_permissions(),
            'PeopleManager' => people_manager_permissions(),
            'Curriculum' => curriculum_permissions(),
            'CurriculumManager' => curriculum_manager_permissions(),
            'PositionsManager' => positions_manager_permissions(),
            'Teacher'  => teacher_permissions(),
        ];

    }
}

if (!function_exists('sample_permissions')) {
    function sample_permissions()
    {
        Permission::create(['name' => 'Permission1']);
        Permission::create(['name' => 'Permission2']);
        Permission::create(['name' => 'Permission3']);
    }
}

if (!function_exists('sample_roles')) {
    function sample_roles()
    {
        Role::create(['name' => 'Rol1']);
        Role::create(['name' => 'Rol2']);
        Role::create(['name' => 'Rol3']);
    }
}

if (!function_exists('initialize_tenant_roles_and_permissions')) {
    function initialize_tenant_roles_and_permissions()
    {
        $permissions = scool_permissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = scool_roles();
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']]);
        }

        $rolePermissions = scool_roles_permissions();
        foreach ($rolePermissions as $role => $rolePermission) {
            $role = Role::findByName($role);
            foreach ($rolePermission as $permission) {
                $role->givePermissionTo($permission);
            }
        }
    }
}

if (!function_exists('initialize_student_role')) {
    function initialize_student_role()
    {
        $role = Role::firstOrCreate(['name' => ScoolRole::STUDENT['name']]);
        $permissions = student_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}

if (!function_exists('initialize_users_manager_role')) {
    function initialize_teacher_role()
    {
        $role = Role::firstOrCreate(['name' => ScoolRole::TEACHER['name']]);
        $permissions = teacher_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}

if (!function_exists('initialize_users_manager_role')) {
    function initialize_users_manager_role()
    {
        $role = Role::firstOrCreate(['name' => ScoolRole::USERS_MANAGER['name']]);
        $permissions = users_manager_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}

if (!function_exists('initialize_ldap_manager_role')) {
    function initialize_ldap_manager_role()
    {
        $role = Role::firstOrCreate(['name' => ScoolRole::LDAP_MANAGER['name']]);
        $permissions = ldap_manager_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}

if (!function_exists('initialize_notifications_manager_role')) {
    function initialize_notifications_manager_role()
    {
        $role = Role::firstOrCreate(['name' => ScoolRole::NOTIFICATIONS_MANAGER['name']]);
        $permissions = notifications_manager_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}


if (!function_exists('initialize_people_manager_role')) {
    function initialize_people_manager_role()
    {
        $role = Role::firstOrCreate(['name' => 'PeopleManager']);
        $permissions = people_manager_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}

if (!function_exists('initialize_moodle_manager_role')) {
    function initialize_moodle_manager_role()
    {
        $role = Role::firstOrCreate(['name' => 'MoodleManager']);
        $permissions = moodle_manager_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}

if (!function_exists('initialize_curriculum_manager_role')) {
    function initialize_curriculum_manager_role()
    {
        $role = Role::firstOrCreate(['name' => 'CurriculumManager']);
        $permissions = curriculum_manager_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}

if (!function_exists('initialize_positions_manager_role')) {
    function initialize_positions_manager_role()
    {
        $role = Role::firstOrCreate(['name' => 'PositionsManager']);
        $permissions = positions_manager_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}

if (!function_exists('initialize_incidents_manager_role')) {
    function initialize_incidents_manager_role()
    {
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        $permissions = incidents_manager_permissions();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}


if (!function_exists('initialize_gates')) {
    function initialize_gates()
    {
        Gate::define('show-personal-data', function ($user) {
            return $user->hasRole(['UsersManager']);
        } );

        Gate::define('calculate-lessons', function ($user) {
            return $user->hasRole(['LessonsManager']);
        });

        Gate::define('show-teacher-profile', function ($user) {
            return $user->isTeacher();
        });

        Gate::define('impersonate-user', function ($user) {
            return $user->isSuperAdmin();
        });

        Gate::define('store-user-photo', function ($user) {
            return $user->hasRole(['UsersManager','TeachersManager']);
        });

        Gate::define('remove-user-photo', function ($user) {
            return $user->hasRole(['UsersManager','TeachersManager']);
        });

        Gate::define('approve-teacher', function ($user) {
            return $user->hasRole(['UsersManager','TeachersManager']);
        });

        // Google suite groups
        Gate::define('list-gsuite-groups', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('remove-gsuite-groups', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('show-gsuite-groups', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('store-gsuite-groups', function ($user) {
            return $user->hasRole('UsersManager');
        });

        // Google suite Users
        Gate::define('list-gsuite-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('show-gsuite-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('store-gsuite-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('edit-gsuite-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('delete-gsuite-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('watch-gsuite-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        // Ldap Users
        Gate::define('list-ldap-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('show-ldap-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('store-ldap-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('delete-ldap-users', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('resend-email-verification', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('send-welcome-email', function ($user) {
            return $user->hasRole('UsersManager');
        });

        // STAFF/JOBS
        Gate::define('list-available-users', function ($user) {
            return $user->hasRole('StaffManager') || $user->hasRole('TeachersManager');
        });

        Gate::define('list-jobs', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('show-jobs', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('store-job', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('update-job', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('delete-job', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('store-job-substitutions', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('update-job-substitutions', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('delete-job-substitution', function ($user) {
            return $user->hasRole('StaffManager');
        });

        Gate::define('delete-job-substitutions', function ($user) {
            return $user->hasRole('StaffManager');
        });

        //Teachers
        Gate::define('list_teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });

        Gate::define('show-teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });
        Gate::define('store-teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });
        Gate::define('delete-teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });

        Gate::define('show-pending-teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });

        Gate::define('delete-pending-teacher', function ($user) {
            return $user->hasRole('TeachersManager');
        });


        Gate::define('show-teachers-photos', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('store-teachers-photos', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('show-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('download-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('delete-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('delete-all-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('edit-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('store-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('list-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        Gate::define('destroy-teacher-photo', function ($user) {
            return $user->hasRole('PhotoTeachersManager');
        });

        //Pending teachers
        Gate::define('list_pending_teachers', function ($user) {
            return $user->hasRole('TeachersManager');
        });

        Gate::define('show-lessons', function ($user) {
            return $user->hasRole('LessonsManager');
        });

        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        //USERS
        Gate::define('users.list', function ($user) {
            return $user->hasRole('UsersManager') || $user->hasRole('IncidentsManager');
        });

        Gate::define('users.store', function ($user) {
            return $user->hasRole('UsersManager') || $user->hasRole('TeachersManager');
        });

        Gate::define('users.show', function ($loggedUser) {
            return $loggedUser->hasRole('UsersManager');
        });

        Gate::define('users.update', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('users.destroy', function ($user) {
            return $user->hasRole('UsersManager');
        });

        Gate::define('users.destroy.multiple', function ($user) {
            return $user->hasRole('UsersManager');
        });

        //INCIDENTS
        Gate::define('incident.list', function ($user) {
            return $user->hasRole('Incidents') || $user->hasRole('IncidentsManager');
        });

        Gate::define('incident.store', function ($user) {
            return $user->hasRole('Incidents') || $user->hasRole('IncidentsManager');
        });

        Gate::define('incident.show', function ($user) {
            return $user->hasRole('Incidents') || $user->hasRole('IncidentsManager');
        });

        Gate::define('incident.close', function ($user, $incident) {
            if($incident->user_id  == $user->id) return true;
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('incident.open', function ($user,$incident) {
            if($incident->user_id  == $user->id) return true;
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('incident.destroy', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('incident.update', function ($user,$incident) {
            if($incident->user_id  == $user->id) return true;
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('reply.update', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        // Settings
        Gate::define('setting.update', function ($user,$setting) {
            return $user->hasRole(Setting::getRole($setting->key));
        });

        Gate::define('tag.show', function ($user) {
            return $user->hasAnyRole(['IncidentsManager','Incidents']);
        });

        Gate::define('tag.list', function ($user) {
            return $user->hasAnyRole(['IncidentsManager','Incidents']);
        });

        Gate::define('tag.store', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('tag.update', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('tag.destroy', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('tagged.incident.store', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('tagged.incident.destroy', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('assignee.store', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('assignee.destroy', function ($user) {
            return $user->hasRole('IncidentsManager');
        });

        Gate::define('user.role.store', function ($user,$role) {
            return $user->hasRole('RolesManager') ||
                ($user->hasRole('IncidentsManager') && $role->name === 'Incidents') ||
                ($user->hasRole('IncidentsManager') && $role->name === 'IncidentsManager');
        });

        Gate::define('user.role.destroy', function ($user,$role) {
            return $user->hasRole('RolesManager')  ||
                ($user->hasRole('IncidentsManager') && $role->name === 'Incidents') ||
                ($user->hasRole('IncidentsManager') && $role->name === 'IncidentsManager');
        });

        // Changelog
        Gate::define('changelog.list', function ($user) {
            return $user->hasRole('ChangelogManager');
        });

        // CHANGELOG
        Gate::define('logs.module.list', function ($user, Module $module) {
            return $user->hasRole(studly_case($module->name . 'Manager'));
        });

        Gate::define('logs.index', function ($user) {
            return $user->hasRole('ChangelogManager');
        });

        Gate::define('logs.user.list', function ($loggedUser, $user) {
            $user = is_object($user) ? $user->id : $user;
            return (int) $loggedUser->id === (int) $user || $loggedUser->hasRole('ChangelogManager');
        });

        Gate::define('logs.loggable.list', function ($user, $loggable) {
            if ($user->id === (int) $loggable->user_id) return true;
            if($loggable->managerRole()){
                if ($user->hasRole($loggable->managerRole())) return true;
            }
            if($loggable->api_uri) {
                if ($loggable->api_uri === 'incidents') {
                    if($user->hasRole('Incidents')) return true;
                }
            }
            return false;
        });

        Gate::define('people.show', function($loggedUser,$person) {
            if ($person) {
                if ($person->user) {
                    if ((int) $person->user->id === (int) $loggedUser->id) return true;
                }
            }
            if ($loggedUser->hasRole(ScoolRole::PEOPLE_MANAGER['name'])) return true;
            if ($loggedUser->hasRole(ScoolRole::USERS_MANAGER['name'])) return true;
            return false;
        });


    }
}

if (!function_exists('initialize_modules')) {
    function initialize_modules()
    {
        Module::firstOrCreate([
            'name' => 'incidents',
        ]);

        Module::firstOrCreate([
            'name' => 'users',
        ]);

        Module::firstOrCreate([
            'name' => 'curriculum',
        ]);
    }
}

if (!function_exists('initialize_menus')) {
    function initialize_menus() {
        Menu::firstOrCreate([
            'icon' => 'home',
            'text' => 'Principal',
            'href' => '/home'
        ]);

        Menu::firstOrCreate([
            'icon' => 'build',
            'text' => 'Incidències',
            'href' => '/incidents',
            'role' => 'Incidents'
        ]);

        Menu::firstOrCreate([
            'icon' => 'notifications',
            'text' => 'Notificacions',
            'href' => '/notifications'
        ]);

        Menu::firstOrCreate([
            'icon' => 'cast',
            'text' => 'Curriculum',
            'href' => '/curriculum',
            'role' => 'Curriculum'
        ]);

        Menu::firstOrCreate([
            'heading' => 'Administració',
            'role' => 'Manager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Mòduls',
            'href' => '/modules',
            'role' => 'Manager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Potencial',
            'href' => '/lessons',
            'role' => 'LessonsManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Usuaris',
            'href' => '/users',
            'role' => 'UsersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Càrrecs',
            'href' => '/positions',
            'role' => 'PositionsManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Moodle',
            'href' => '/moodle/users',
            'role' => 'MoodleManager,UsersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Plantilla',
            'href' => '/jobs',
            'role' => 'StaffManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Professorat',
            'href' => '/teachers',
            'role' => 'TeachersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Fotos Professorat',
            'href' => '/teachers_photos',
            'role' => 'PhotoTeachersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Dades personals',
            'href' => '/personal_data',
            'role' => 'UsersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Configuració general',
            'href' => '/config',
            'role' => 'Admin'
        ]);

        Menu::firstOrCreate([
            'text' => 'Grups de Google',
            'href' => '/google_groups',
            'role' => 'UsersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Usuaris de Google',
            'href' => '/google_users',
            'role' => 'UsersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Usuaris de ldap',
            'href' => '/ldap_users',
            'role' => 'UsersManager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Registre de canvis',
            'href' => '/changelog',
            'role' => 'Changelogmanager'
        ]);

        Menu::firstOrCreate([
            'text' => 'Laravel Horizon',
            'href' => '/horizon',
            'role' => 'Superadmin',
            'target' => '_blank'
        ]);

        Menu::firstOrCreate([
            'text' => 'Laravel websockets',
            'href' => '/laravel-websockets',
            'role' => 'Superadmin',
            'target' => '_blank'
        ]);
    }
}

if (!function_exists('initialize_job_types')) {
    function initialize_job_types()
    {
        JobType::firstOrCreate([
            'name' => 'Professor/a'
        ]);

        JobType::firstOrCreate([
            'name' => 'Conserge'
        ]);

        JobType::firstOrCreate([
            'name' => 'Administratiu/va'
        ]);
    }
}

if (!function_exists('initialize_users')) {
    function initialize_users()
    {

    }
}

if (!function_exists('collect_files')) {
    /**
     * Collect files.
     *
     * @param $path
     * @param string $disk
     * @return static
     */
    function collect_files($path, $disk = 'local')
    {
        $files = collect(File::allFiles(Storage::disk($disk)->path($path)))->map(function ($file) {
            return [
                'filename' => $filename = $file->getFilename(),
                'slug' => str_slug($filename,'-')
            ];
        });
        return $files;
    }
}

if (!function_exists('initialize_administrative_assistants')) {
    function initialize_administrative_assistants()
    {
        User::createIfNotExists([
            'name' => 'Pilar Vericat',
            'email' => 'pilarvericat@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('AdministrativeAssistant'))
            ->assignFullName([
                'givenName' => 'Pilar',
                'sn1' => 'Vericat',
                'sn2' => '',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Administratiu/va')->id,
                    'code' => 'A1',
                    'order' => 1
                ])
            );

        User::createIfNotExists([
            'name' => 'Cinta Tomas',
            'email' => 'cintatomas@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
            'user_type_id' => UserType::findByName('Administratiu/va')->id
        ])->addRole(Role::findByName('AdministrativeAssistant'))
            ->assignFullName([
                'givenName' => 'Cinta',
                'sn1' => 'Tomas',
                'sn2' => '',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Administratiu/va')->id,
                    'code' => 'A2',
                    'order' => 1
                ])
            );

        User::createIfNotExists([
            'name' => 'Lluïsa Garcia',
            'email' => 'lluisagarcia@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
            'user_type_id' => UserType::findByName('Administratiu/va')->id
        ])->addRole(Role::findByName('AdministrativeAssistant'))
            ->assignFullName([
                'givenName' => 'Lluisa',
                'sn1' => 'Garcia',
                'sn2' => '',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Administratiu/va')->id,
                    'code' => 'A3',
                    'order' => 1
                ])
            );

        User::createIfNotExists([
            'name' => 'Sonia Alegria',
            'email' => 'soniaalegria@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
            'user_type_id' => UserType::findByName('Administratiu/va')->id
        ])->addRole(Role::findByName('AdministrativeAssistant'))
            ->assignFullName([
                'givenName' => 'Sonia',
                'sn1' => 'Alegria',
                'sn2' => '',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Administratiu/va')->id,
                    'code' => 'F4',
                    'order' => 1
                ])
            );
    }
}

if (!function_exists('initialize_janitors')) {
    function initialize_janitors()
    {
        User::createIfNotExists([
            'name' => 'Jaume Benaiges',
            'email' => 'jaumebenaiges@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret using SHA1 (blames Gsuite) instead of bcrypt
            'remember_token' => str_random(10),
            'user_type_id' => UserType::findByName('Conserge')->id
        ])->addRole(Role::findByName('Janitor'))
            ->assignFullName([
                'givenName' => 'Jaume',
                'sn1' => 'Benaiges',
                'sn2' => '',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Conserge')->id,
                    'code' => 'C1',
                    'order' => 1
                ])
            );

        User::createIfNotExists([
            'name' => 'Jordi Caudet',
            'email' => 'jordicaudet@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
            'user_type_id' => UserType::findByName('Conserge')->id
        ])->addRole(Role::findByName('Janitor'))
            ->assignFullName([
                'givenName' => 'Jordi',
                'sn1' => 'Caudet',
                'sn2' => '',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Conserge')->id,
                    'code' => 'C2',
                    'order' => 2
                ])
            );

        User::createIfNotExists([
            'name' => 'Leonor Agramunt',
            'email' => 'leonoragramunt@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
            'user_type_id' => UserType::findByName('Conserge')->id
        ])->addRole(Role::findByName('Janitor'))
            ->assignFullName([
                'givenName' => 'Leonor',
                'sn1' => 'Agramunt',
                'sn2' => '',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Conserge')->id,
                    'code' => 'C3',
                    'order' => 3
                ])
            );
    }
}

if (!function_exists('initialize_teachers_ppas')) {
    function initialize_teachers_ppas()
    {
        $dolors = User::createIfNotExists([
            'name' => 'Dolors Sanjuan Aubà',
            'email' => 'dolorssanjuanauba@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Dolors',
                'sn1' => 'Sanjuan',
                'sn2' => 'Aubà',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('CAS')->id,
                    'code' => '001',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '002',
                'department_id' => Department::findByCode('PPAS')->id
            ]))->assignPosition(Position::firstOrCreate([
                'name' => 'Tutora de CAM'
            ]));
        $dolors->assignRole('Incidents');

        $juliacurto = User::createIfNotExists([
            'name' => 'Julià Curto De la Vega',
            'email' => 'jcurto@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Julià',
                'sn1' => 'Curto',
                'sn2' => 'De la Vega',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('MA')->id,
                    'code' => '002',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '008',
                'department_id' => Department::findByCode('PPAS')->id
            ]));
        $juliacurto->assignRole('Incidents');

        // Principi de curs
        // Sílvia Armengol Bosch (nº 4). Anglès. DNI: 47829022Q. DNI: 47829022Q.Fa la baixa d'Isabel Jordà:
        // sarmeng5@xtec.com (crec que deuria ser .cat; però m'ha posat .com?)
        // TODO ?? No tenim Sílvia Armengol
        User::createIfNotExists([
            'name' => 'Núria Vallés Machirant',
            'email' => 'nuriavalles@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Núria',
                'sn1' => 'Vallés',
                'sn2' => 'Machirant',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('AN')->id,
                    'code' => '003',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '004',
                'department_id' => Department::findByCode('PPAS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Enric Querol Coll',
            'email' => 'equerol@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Enric',
                'sn1' => 'Querol',
                'sn2' => 'Coll',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('AN')->id,
                    'code' => '003',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '005',
                'department_id' => Department::findByCode('PPAS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Lara Melich Cañado',
            'email' => 'laramelich@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Lara',
                'sn1' => 'Melich',
                'sn2' => 'Cañado',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('AN')->id,
                    'code' => '004',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '006',
                'department_id' => Department::findByCode('PPAS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Carme Aznar Pedret',
            'email' => 'carmeaznar@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Carme',
                'sn1' => 'Aznar',
                'sn2' => 'Pedret',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('AN')->id,
                    'code' => '005',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '007',
                'department_id' => Department::findByCode('PPAS')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_fol')) {
    function initialize_teachers_fol()
    {
        User::createIfNotExists([
            'name' => 'Teresa Lasala Descarrega',
            'email' => 'tlasala@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Teresa',
                'sn1' => 'Lasala',
                'sn2' => 'Descarrega',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('505')->id,
                    'code' => '008',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '009',
                'department_id' => Department::findByCode('FOL')->id
            ]));

        User::createIfNotExists([
            'name' => 'Carmina Andreu Pons',
            'email' => 'candreu@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Carmina',
                'sn1' => 'Andreu',
                'sn2' => 'Pons',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('505')->id,
                    'code' => '009',
                    'order' => 2
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '010',
                'department_id' => Department::findByCode('FOL')->id
            ]));

        User::createIfNotExists([
            'name' => 'J.Andrés Brocal Safont',
            'email' => 'jbrocal@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'J.Andrés',
                'sn1' => 'Brocal',
                'sn2' => 'Safont',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('505')->id,
                    'code' => '010',
                    'order' => 3
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '011',
                'department_id' => Department::findByCode('FOL')->id
            ]));

        User::createIfNotExists([
            'name' => 'Pilar Fadurdo Estrada',
            'email' => 'pilarfadurdo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Pilar',
                'sn1' => 'Fadurdo',
                'sn2' => 'Estrada',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('505')->id,
                    'code' => '011',
                    'order' => 4
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '012',
                'department_id' => Department::findByCode('FOL')->id
            ]));

        User::createIfNotExists([
            'name' => 'Carlos Querol Bel',
            'email' => 'carlosquerol@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Carlos',
                'sn1' => 'Querol',
                'sn2' => 'Bel',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('505')->id,
                    'code' => '012',
                    'order' => 5
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '013',
                'department_id' => Department::findByCode('FOL')->id
            ]));

        User::createIfNotExists([
            'name' => 'Marisa Grau Campeón',
            'email' => 'cgrau@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Marisa',
                'sn1' => 'Grau',
                'sn2' => 'Campeón',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('505')->id,
                    'code' => '013',
                    'order' => 6
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '003',
                'department_id' => Department::findByCode('FOL')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_electrics')) {
    function initialize_teachers_electrics()
    {
        User::createIfNotExists([
            'name' => 'Nuria Bordes Vidal',
            'email' => 'nbordes@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Nuria',
                'sn1' => 'Bordes',
                'sn2' => 'Vidal',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('524')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '028',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '028',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Laura Llopis Lozano',
            'email' => 'laurallopis@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Laura',
                'sn1' => 'Llopis',
                'sn2' => 'Lozano',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('525')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '029',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '029',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Vicent Favà Figueres',
            'email' => 'vfava@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Vicent',
                'sn1' => 'Favà',
                'sn2' => 'Figueres',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('525')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '030',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '030',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Agustí Baubí Rovira',
            'email' => 'agustinbaubi@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Agustí',
                'sn1' => 'Baubí',
                'sn2' => 'Rovira',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('525')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '031',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '031',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Josep Joan Cid Castella',
            'email' => 'joancid1@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Josep Joan',
                'sn1' => 'Cid',
                'sn2' => 'Castellar',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('513')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '116',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '116',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Rafel Puig Rios',
            'email' => 'rafelpuig@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Rafel',
                'sn1' => 'Puig',
                'sn2' => 'Rios',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('602')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '032',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '032',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Laureà Ferré Menasanch',
            'email' => 'lferre@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Laureà',
                'sn1' => 'Ferré',
                'sn2' => 'Menasanch',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('602')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '033',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '033',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Manel Canalda Vidal',
            'email' => 'manelcanalda@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Manel',
                'sn1' => 'Canalda',
                'sn2' => 'Vidal',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('605')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '034',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '034',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Xavi Bel Fernández',
            'email' => 'xbel@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Xavi',
                'sn1' => 'Bel',
                'sn2' => 'Fernández',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('606')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '035',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '035',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        // TODO ->
        // A Ebreescool surt Francesc Audí Povedano. Es va jubilar?
        User::createIfNotExists([
            'name' => 'J.Luís Colomé Monllao',
            'email' => 'jcolome@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'J.Luís',
                'sn1' => 'Colomé',
                'sn2' => 'Monllao',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('606')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '036',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '036',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Angel Portillo Lucas',
            'email' => 'angelportillo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Angel',
                'sn1' => 'Portillo',
                'sn2' => 'Lucas',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('606')->id,
                    'family_id' => Family::findByCode('ELECTRIC')->id,
                    'code' => '037',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '037',
                'department_id' => Department::findByCode('ELÈCTRICS')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_sanitat')) {
    function initialize_teachers_sanitat()
    {
        User::createIfNotExists([
            'name' => 'Anna Valls Montagut',
            'email' => 'avalls@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Anna',
                'sn1' => 'Valls',
                'sn2' => 'Montagut',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('517')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '064',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '064',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Anna Benaiges Bertomeu',
            'email' => 'anabenaiges@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Anna',
                'sn1' => 'Benaiges',
                'sn2' => 'Bertomeu',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('517')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '065',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '065',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Salomé Figueres Brescolí',
            'email' => 'salomefigueres@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Salomé',
                'sn1' => 'Figueres',
                'sn2' => 'Brescolí',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('517')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '067',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '067',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Pepa Cugat Tomàs',
            'email' => 'pepacugat@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Pepa',
                'sn1' => 'Cugat',
                'sn2' => 'Tomàs',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('517')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '066',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '066',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Berta Safont Recatalà',
            'email' => 'bertasafont@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Berta',
                'sn1' => 'Safont',
                'sn2' => 'Recatalà',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('518')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '062',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '062',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'MªJesús Sales Berire',
            'email' => 'msales@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Mª Jesús',
                'sn1' => 'Sales',
                'sn2' => 'Berire',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('518')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '060',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '060',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'MªLuisa Asensi Moltalva',
            'email' => 'mariaasensi@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Mª Jesús',
                'sn1' => 'Asensi',
                'sn2' => 'Moltalva',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('518')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '061',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '061',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Santi López Garcia',
            'email' => 'santiagolopez@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Santi',
                'sn1' => 'López',
                'sn2' => 'Garcia',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('518')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '063',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '063',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Lluis Ventura Forner',
            'email' => 'lventura@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Lluis',
                'sn1' => 'Ventura',
                'sn2' => 'Forner',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('619')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '069',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '069',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'J.Antoni Pons Albalat',
            'email' => 'jpons@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'J.Antoni',
                'sn1' => 'Pons',
                'sn2' => 'Albalat',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('619')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '070',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '070',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Alicia Fàbrega Martínez',
            'email' => 'aliciafabrega@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Alicia',
                'sn1' => 'Fàbrega',
                'sn2' => 'Martínez',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('619')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '071',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '071',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Segis Benabent Gil',
            'email' => 'sbenabent@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Segis',
                'sn1' => 'Benabent',
                'sn2' => 'Gil',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('619')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '072',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '072',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Sandra Salvador Jovaní',
            'email' => 'sandrasalvador@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Sandra',
                'sn1' => 'Salvador',
                'sn2' => 'Jovaní',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('619')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '068',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '068',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'MªJosé Caballé Valverde',
            'email' => 'mcaballe@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'MªJosé',
                'sn1' => 'Caballé',
                'sn2' => 'Valverde',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '074',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '074',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Marisa Ramón Pérez',
            'email' => 'mramon@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Marisa',
                'sn1' => 'Ramón',
                'sn2' => 'Pérez',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '073',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '073',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Elisa Puig Moll',
            'email' => 'epuig@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Elisa',
                'sn1' => 'Puig',
                'sn2' => 'Moll',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '075',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '075',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Ruth Hidalgo Vilar',
            'email' => 'rhidalgo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Ruth',
                'sn1' => 'Hidalgo',
                'sn2' => 'Vilar',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '076',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '076',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Anna Sambartolomé Sancho',
            'email' => 'annasambartolome@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Anna',
                'sn1' => 'Sambartolomé',
                'sn2' => 'Sancho',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '077',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '077',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Cinta Mestre Escorihuela',
            'email' => 'cintamestre@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Cinta',
                'sn1' => 'Mestre',
                'sn2' => 'Escorihuela',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '078',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '078',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Fabiola Grau Talens',
            'email' => 'fgrau@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Fabiola',
                'sn1' => 'Grau',
                'sn2' => 'Talens',
            ])->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '079',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '079',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Trini Tomas Forcadell',
            'email' => 'trinidadtomas@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Trini',
                'sn1' => 'Tomas',
                'sn2' => 'Forcadell',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '080',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '080',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Adonay Pérez López',
            'email' => 'aperez@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Adonay',
                'sn1' => 'Pérez',
                'sn2' => 'López',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('620')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '081',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '081',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Tarsi Royo Cruselles',
            'email' => 'troyo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Tarsi',
                'sn1' => 'Royo',
                'sn2' => 'Cruselles',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('508')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '082',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '082',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'MªCarmen Lorenzo Monfó',
            'email' => 'carmelorenzo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'MªCarmen',
                'sn1' => 'Lorenzo',
                'sn2' => 'Monfó',
            ])->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('508')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '082',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '083',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));

        User::createIfNotExists([
            'name' => 'Iris Maturana Andreu',
            'email' => 'irismaturana@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Iris',
                'sn1' => 'Maturana',
                'sn2' => 'Andreu',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('508')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '084',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '084',
                'department_id' => Department::findByCode('SANITAT')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_serveis')) {
    function initialize_teachers_serveis()
    {
        User::createIfNotExists([
            'name' => 'Llatzer Carbó Bertomeu',
            'email' => 'llatzercarbo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Llatzer',
                'sn1' => 'Carbó',
                'sn2' => 'Bertomeu',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('508')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '085',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '085',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Mercè Gilo Ortiz',
            'email' => 'mercegilo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Mercè',
                'sn1' => 'Gilo',
                'sn2' => 'Ortiz',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('508')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '086',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '086',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Cristina Cardona Romero',
            'email' => 'ccardona99@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Cristina',
                'sn1' => 'Cardona',
                'sn2' => 'Romero',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('625')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '087',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '087',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));

        User::createIfNotExists([
            'name' => 'David Gàmez Balaguer',
            'email' => 'dgamez1@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'David',
                'sn1' => 'Gàmez',
                'sn2' => 'Balaguer',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('625')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '088',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '088',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));

        $angelsgarrido = User::createIfNotExists([
            'name' => 'Àngels Garrido Borja',
            'email' => 'mgarrido2@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Àngels',
                'sn1' => 'Garrido',
                'sn2' => 'Borja',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('625')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '089',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '089',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));
        $angelsgarrido->assignRole('Incidents');

        User::createIfNotExists([
            'name' => 'Alicia Gamundi Vilà',
            'email' => 'aliciagamundi@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Alicia',
                'sn1' => 'Gamundi',
                'sn2' => 'Vilà',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('625')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '090',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '090',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Ricard Gonzalez Castelló',
            'email' => 'rgonzalez1@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Ricard',
                'sn1' => 'Gonzalez',
                'sn2' => 'Castelló',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('625')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '091',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '091',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Elena Mauri Cuenca',
            'email' => 'elenamauri@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Elena',
                'sn1' => 'Mauri',
                'sn2' => 'Cuenca',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('625')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '092',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '092',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Irene Alegre Chavarria',
            'email' => 'irenealegre@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Irene',
                'sn1' => 'Alegre',
                'sn2' => 'Chavarria',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('625')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '093',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '093',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Maria Castells Gilabert',
            'email' => 'mariacastells1@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Maria',
                'sn1' => 'Castells',
                'sn2' => 'Gilabert',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('625')->id,
                    'family_id' => Family::findByCode('SERVEIS')->id,
                    'code' => '108',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '108',
                'department_id' => Department::findByCode('SERVEIS')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_administracio')) {
    function initialize_teachers_administracio()
    {
        User::createIfNotExists([
            'name' => 'Oscar Samo Franch',
            'email' => 'oscarsamo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Oscar',
                'sn1' => 'Samo',
                'sn2' => 'Franch',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('501')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '014',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '014',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Enric Garcia Carcelén',
            'email' => 'egarci@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Enric',
                'sn1' => 'Garcia',
                'sn2' => 'Carcelén',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('501')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '015',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '015',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Eduard Ralda Simó',
            'email' => 'eralda@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Eduard',
                'sn1' => 'Ralda',
                'sn2' => 'Simó',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('501')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '016',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '016',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Pili Nuez Garcia',
            'email' => 'mnuez@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Pili',
                'sn1' => 'Nuez',
                'sn2' => 'Garcia',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('501')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '017',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '017',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));

        User::createIfNotExists([
            'name' => 'MªRosa Ubalde Bellot',
            'email' => 'mariarosaubalde@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'MªRosa',
                'sn1' => 'Ubalde',
                'sn2' => 'Bellot',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('501')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '018',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '018',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Paqui Pinyol Moreso',
            'email' => 'fpinyol@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Paqui',
                'sn1' => 'Pinyol',
                'sn2' => 'Moreso',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('622')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '019',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '019',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Dolors Subirats Fabra',
            'email' => 'dsubirats@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Dolors',
                'sn1' => 'Subirats',
                'sn2' => 'Fabra',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('622')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '020',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '020',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Ferran Sabaté Borras',
            'email' => 'fsabate@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Ferran',
                'sn1' => 'Sabaté',
                'sn2' => 'Borras',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('622')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '021',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '021',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));

        $araceliesteller = User::createIfNotExists([
            'name' => 'Araceli Esteller Hierro',
            'email' => 'aesteller@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Araceli',
                'sn1' => 'Esteller',
                'sn2' => 'Hierro',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('622')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '022',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '022',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));
        $araceliesteller->assignRole('Incidents');

        User::createIfNotExists([
            'name' => 'Mavi Santamaria Andreu',
            'email' => 'mavisantamaria@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Mavi',
                'sn1' => 'Santamaria',
                'sn2' => 'Andreu',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('622')->id,
                    'family_id' => Family::findByCode('ADMIN')->id,
                    'code' => '023',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '023',
                'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_comerc')) {
    function initialize_teachers_comerc()
    {
        User::createIfNotExists([
            'name' => 'Agustí Moreso Garcia',
            'email' => 'amoreso@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Agustí',
                'sn1' => 'Moreso',
                'sn2' => 'Garcia',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('510')->id,
                    'family_id' => Family::findByCode('COMERÇ')->id,
                    'code' => '024',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '024',
                'department_id' => Department::findByCode('COMERÇ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Carme Vega Guerra',
            'email' => 'cvega@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Carme',
                'sn1' => 'Vega',
                'sn2' => 'Guerra',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('510')->id,
                    'family_id' => Family::findByCode('COMERÇ')->id,
                    'code' => '025',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '025',
                'department_id' => Department::findByCode('COMERÇ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Dolors Ferreres Gasulla',
            'email' => 'dolorsferreres@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Dolors',
                'sn1' => 'Ferreres',
                'sn2' => 'Gasulla',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('510')->id,
                    'family_id' => Family::findByCode('COMERÇ')->id,
                    'code' => '106',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '106',
                'department_id' => Department::findByCode('COMERÇ')->id
            ]));

        $juandedios = User::createIfNotExists([
            'name' => 'Juan Abad Bueno',
            'email' => 'juanabad@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Juan',
                'sn1' => 'Abad',
                'sn2' => 'Bueno',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('510')->id,
                    'family_id' => Family::findByCode('COMERÇ')->id,
                    'code' => '107',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '107',
                'department_id' => Department::findByCode('COMERÇ')->id
            ]));
        $juandedios->assignRole('Incidents');
        User::createIfNotExists([
            'name' => 'Just Pérez Santiago',
            'email' => 'justperez@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Just',
                'sn1' => 'Pérez',
                'sn2' => 'Santiago',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('621')->id,
                    'family_id' => Family::findByCode('COMERÇ')->id,
                    'code' => '026',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '026',
                'department_id' => Department::findByCode('COMERÇ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Armand Pons Roda',
            'email' => 'apons@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Armand',
                'sn1' => 'Pons',
                'sn2' => 'Roda',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('621')->id,
                    'family_id' => Family::findByCode('COMERÇ')->id,
                    'code' => '027',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '027',
                'department_id' => Department::findByCode('COMERÇ')->id
            ]));

        User::createIfNotExists([
            'name' => 'Raquel Planell Tolos',
            'email' => 'raquelplanell@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Armand',
                'sn1' => 'Pons',
                'sn2' => 'Roda',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('621')->id,
                    'family_id' => Family::findByCode('COMERÇ')->id,
                    'code' => '105',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '105',
                'department_id' => Department::findByCode('COMERÇ')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_arts')) {
    function initialize_teachers_arts()
    {
        User::createIfNotExists([
            'name' => 'Marta Grau Ferrer',
            'email' => 'martagrau@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Marta',
                'sn1' => 'Grau',
                'sn2' => 'Ferrer',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('522')->id,
                    'family_id' => Family::findByCode('ARTS')->id,
                    'code' => '094',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '094',
                'department_id' => Department::findByCode('ARTS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Gerard Domenech Vendrell',
            'email' => 'gerarddomenech@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Gerard',
                'sn1' => 'Domenech',
                'sn2' => 'Vendrell',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('623')->id,
                    'family_id' => Family::findByCode('ARTS')->id,
                    'code' => '095',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '095',
                'department_id' => Department::findByCode('ARTS')->id
            ]));

        User::createIfNotExists([
            'name' => 'J.Antonio Fernández Herraez',
            'email' => 'joseantoniofernandez1@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'J.Antonio',
                'sn1' => 'Fernández',
                'sn2' => 'Herraez',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('623')->id,
                    'family_id' => Family::findByCode('ARTS')->id,
                    'code' => '096',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '096',
                'department_id' => Department::findByCode('ARTS')->id
            ]));

        User::createIfNotExists([
            'name' => 'Monica Moreno Dionis',
            'email' => 'monicamoreno@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Monica',
                'sn1' => 'Moreno',
                'sn2' => 'Dionis',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('621')->id,
                    'family_id' => Family::findByCode('ARTS')->id,
                    'code' => '097',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '097',
                'department_id' => Department::findByCode('ARTS')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_informatica')) {
    function initialize_teachers_informatica()
    {
        User::createIfNotExists([
            'name' => 'Santi Sabaté Sanz',
            'email' => 'ssabate@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Santi',
                'sn1' => 'Sabaté',
                'sn2' => 'Sanz',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('507')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '038',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '038',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));

        $jordivaras = User::createIfNotExists([
            'name' => 'Jordi Varas Aliau',
            'email' => 'jvaras@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Jordi',
                'sn1' => 'Varas',
                'sn2' => 'Aliau',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('507')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '039',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '039',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));
        $jordivaras->assignRole('Incidents');

        User::createIfNotExists([
            'name' => 'Sergi Tur Badenas',
            'email' => 'stur@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Sergi',
                'sn1' => 'Tur',
                'sn2' => 'Badenas',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('507')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '040',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '040',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]))->assignRole('Incidents');

        $jaumeramos = User::createIfNotExists([
            'name' => 'Jaume Ramos Prades',
            'email' => 'jaumeramos@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Jaume',
                'sn1' => 'Ramos',
                'sn2' => 'Prades',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('507')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '041',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '041',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));
        $jaumeramos->assignRole('Incidents');

        User::createIfNotExists([
            'name' => 'Quique Lorente Fuertes',
            'email' => 'quiquelorente@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Quique',
                'sn1' => 'Lorente',
                'sn2' => 'Fuertes',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('507')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '046',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '046',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'A.Gonzal Verge Arnau',
            'email' => 'goncalverge@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'A.Gonzalb',
                'sn1' => 'Verge',
                'sn2' => 'Arnau',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('507')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '117',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '117',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Mireia Consarnau Pallarés',
            'email' => 'mireiaconsarnau@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Mireia',
                'sn1' => 'Consarnau',
                'sn2' => 'Pallarés',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('627')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '042',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '042',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Manel Macías Valanzuela',
            'email' => 'manelmacias@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Manel',
                'sn1' => 'Macías',
                'sn2' => 'Valanzuela',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('627')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '043',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '043',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Luís Pérez Càrcel',
            'email' => 'luisperez@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Luís',
                'sn1' => 'Pérez',
                'sn2' => 'Càrcel',
            ])->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('627')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '044',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '044',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Josep Diego Cervellera Forcadell',
            'email' => 'josediegocervellera@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Josep Diego',
                'sn1' => 'Cervellera',
                'sn2' => 'Forcadell',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('627')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '045',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '045',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));
    }
}

if (!function_exists('initialize_teachers_mecanica')) {
    function initialize_teachers_mecanica()
    {
        User::createIfNotExists([
            'name' => 'J.Luis Calderon Furió',
            'email' => 'jcaldero@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'J.Luis',
                'sn1' => 'Calderon',
                'sn2' => 'Furió',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('512')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '051',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '051',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Salvador Jareño Gas',
            'email' => 'sjareno@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Salvador',
                'sn1' => 'Jareño',
                'sn2' => 'Gas',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('512')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '052',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '052',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Jordi Brau Marza',
            'email' => 'jordibrau@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Jordi',
                'sn1' => 'Brau',
                'sn2' => 'Marza',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('512')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '053',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '053',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Joan Tiron Ferré',
            'email' => 'jtiron@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Joan',
                'sn1' => 'Tiron',
                'sn2' => 'Ferré',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('611')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '054',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '054',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Ricard Fernandez Burato',
            'email' => 'rfernand@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Ricard',
                'sn1' => 'Fernandez',
                'sn2' => 'Burato',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('611')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '055',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '055',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Ubaldo Arroyo Martínez',
            'email' => 'ubaldoarroyo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Ubaldo',
                'sn1' => 'Arroyo',
                'sn2' => 'Martínez',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('611')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '056',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '056',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Fernando Segura Venezia',
            'email' => 'fernandosegura@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Fernando',
                'sn1' => 'Segura',
                'sn2' => 'Venezia',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('611')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '057',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '057',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Francesc Besalduch Piñol',
            'email' => 'sbesalduch@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Francesc',
                'sn1' => 'Besalduch',
                'sn2' => 'Piñol',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('611')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '058',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '058',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Manel Segarra Capera',
            'email' => 'msegarra@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Manel',
                'sn1' => 'Segarra',
                'sn2' => 'Capera',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('611')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '059',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '059',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Rosendo Ferri Marzo',
            'email' => 'rosendoferri@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Rosendo',
                'sn1' => 'Ferri',
                'sn2' => 'Marzo',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('611')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '049',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '049',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Jordi Sanchez Bel',
            'email' => 'jordisanchez@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Jordi',
                'sn1' => 'Sanchez',
                'sn2' => 'Bel',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('611')->id,
                    'family_id' => Family::findByCode('FABRIC')->id,
                    'code' => '050',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '050',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Albert Rofí Estelles',
            'email' => 'arofin@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Albert',
                'sn1' => 'Rofí',
                'sn2' => 'Estelles',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('504')->id,
                    'family_id' => Family::findByCode('EDIFIC')->id,
                    'code' => '047',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '047',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Pedro Guerrero López',
            'email' => 'pedroguerrero@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Pedro',
                'sn1' => 'Guerrero',
                'sn2' => 'López',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('612')->id,
                    'family_id' => Family::findByCode('EDIFIC')->id,
                    'code' => '048',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '048',
                'department_id' => Department::findByCode('MECÀNICA')->id
            ]));
    }
}

if (!function_exists('initialize_substitutes')) {
    function initialize_substitutes()
    {
        // Plaça 004 Dos substituts
        // Núria Vallés Machirant (nº4) Anglès -> S04 Silvia Armengol Bosch (47829022Q)

        // Patrícia Prado Villegas (nº 110) esp. 518 (Dep. Sanitat)

        // Plaça 114 -> No sembla que substituexi a ningú! Nova plaça de centre?
        // Lluc Ulldemolins Nolla (nº 114) esp. 525 (Dep. Electricitat....)
        // Plaça 115 -> No sembla que substituexi a ningú! Nova plaça de centre?
        // Carlos Montesó Esmel (nº115) esp. 524 (Dep. Electricitat...)

        // Dades que falten i surten a Ebre-escool:
        // Núria Sayas Espuny (s24) 52601105J va substituir Agustí Moreso? profe 24
        // Núria Segura Capera (s36) 18967997H -> Francesc Audi Povedano -> Jubilació Colomé

        // Javier (Xavi) Sancho Fabregat substitut de profe 41 Jaume Ramos 47643281T
        User::createIfNotExists([
            'name' => 'Javier Sancho Fabregat',
            'email' => 'kalzakath1@gmail.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Javier',
                'sn1' => 'Sancho',
                'sn2' => 'Fabregat',
            ])
            ->assignJob(
                Job::findByCode('041'), // Subtitut de Jaume Ramos
                false,
                Carbon::parse('2018-03-30'),
                Carbon::parse('2018-06-30')
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => Teacher::firstAvailableCode(),
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));

        // S47 Josep Vilanova Roig Substitut de Peré Ferre 47626309W

        // S55 Albert Tiron -> Ricard Fernandez Burato 47476671W

        // S66 Laura Vidal Sancho -> Substituta de Pepa Cugat 4782673B

        // S72 Felipe Perez Viana 73563814Q

        // S79 Patrícia García Saez 21677820K

        // S83 Isabel Jovani Castillo 20470918K

        // S93 Vicente Martínez Aznar 19000244J


        // Mª José Dominguez Rodríguez (nº101) esp. 620 (Dep. Sanitat)
        // Núria Suñé Alañá (nº 109) esp. 620 (Dep. Sanitat)
    }
}

if (!function_exists('initialize_teachers')) {
    function initialize_teachers()
    {
        initialize_teachers_ppas();
        initialize_teachers_fol();
        initialize_teachers_administracio();
        initialize_teachers_comerc();
        initialize_teachers_electrics();
        initialize_teachers_informatica();
        initialize_teachers_mecanica();
        initialize_teachers_sanitat();
        initialize_teachers_serveis();
        initialize_teachers_arts();

        // TODO: Professors substituts falta alguna informació!

        // És la substituta que farà 0,33 jornada de Pepa Cugat TODO 0,33!!!!!!!!!!!!!!!!!
        User::createIfNotExists([
            'name' => 'Marta Delgado Escura',
            'email' => 'martadelgado@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Marta',
                'sn1' => 'Delgado',
                'sn2' => 'Escura',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '099'
            ]));

//        Carles Ferré Serret (nº 100). esp .620. Dep.Sanitat. DNI: 40929388Z. Té una jornada de 0,83 i estarà tot el curs.
//    carlesferre78@gmail.com

        User::createIfNotExists([
            'name' => 'Carles Ferré Serret',
            'email' => 'carlesferre@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Carles',
                'sn1' => 'Ferré',
                'sn2' => 'Serret',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '100'
            ]));

        //Mª Piedad Martin Borràs (nº 103) esp. 619. Dep. Sanitat DNI: 52609442R. Jornada de 0,33. Estarà tot el curs.
//    pmartin@coft.org
        User::createIfNotExists([
            'name' => 'MªPiedad Martin Borràs',
            'email' => 'mariapiedadmartin@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'MªPiedad',
                'sn1' => 'Martin',
                'sn2' => 'Borràs',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '103'
            ]));

        User::createIfNotExists([
            'name' => 'María José Domínguez Rodríguez',
            'email' => 'mariajosedominguez@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'María José',
                'sn1' => 'Domínguez',
                'sn2' => 'Rodríguez',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '101'
            ]));

        User::createIfNotExists([
            'name' => 'Eva Benet Escoda',
            'email' => 'evabenet@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Eva',
                'sn1' => 'Benet',
                'sn2' => 'Escoda',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '102'
            ]));

        User::createIfNotExists([
            'name' => 'Miguel Bardi Alegre',
            'email' => 'miguelbardi@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Miguel',
                'sn1' => 'Bardi',
                'sn2' => 'Alegre',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '111'
            ]));


        User::createIfNotExists([
            'name' => 'Mercè Ferré Aixalà',
            'email' => 'merceferre1@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Mercè',
                'sn1' => 'Ferré',
                'sn2' => 'Aixalà',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '112'
            ]));


        User::createIfNotExists([
            'name' => 'Carlos Montesó Esmel',
            'email' => 'carlosmonteso@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Carlos',
                'sn1' => 'Montesó',
                'sn2' => 'Esmel',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '115'
            ]));

        User::createIfNotExists([
            'name' => 'Patricia Prado Villegas',
            'email' => 'patriciaprado@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Patricia',
                'sn1' => 'Prado',
                'sn2' => 'Villegas',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '110'
            ]));

        User::createIfNotExists([
            'name' => 'Ivan Roche Jodar',
            'email' => 'ivanroche@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Ivan',
                'sn1' => 'Roche',
                'sn2' => 'Jodar',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '118',
                'department_id' => Department::findByCode('INFORMÀTICA')->id
            ]));

        User::createIfNotExists([
            'name' => 'Eduard Serra Pons',
            'email' => 'eduardserra@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
            'mobile' => 655873817
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Eduard',
                'sn1' => 'Serra',
                'sn2' => 'Pons',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '098'
            ]));

        User::createIfNotExists([
            'name' => 'Núria Suñé Alaña',
            'email' => 'eduardserra@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Núria',
                'sn1' => 'Suñé',
                'sn2' => 'Alaña',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '109'
            ]));

        User::createIfNotExists([
            'name' => 'Lluc Ulldemolins Nolla',
            'email' => 'lulldem2@xtec.cat',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Lluc',
                'sn1' => 'Ulldemolins',
                'sn2' => 'Nolla',
            ])->assignTeacher(Teacher::firstOrCreate([
                'code' => '114'
            ]));
    }
}

if (!function_exists('initialize_families')) {
    function initialize_families()
    {
        // http://queestudiar.gencat.cat/ca/estudis/pfi/cicles/

        Family::firstOrCreate([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);

        Family::firstOrCreate([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);

        Family::firstOrCreate([
            'name' => 'Serveis socioculturals i a la comunitat',
            'code' => 'SERVEIS'
        ]);

        Family::firstOrCreate([
            'name' => 'Arts gràfiques',
            'code' => 'ARTS'
        ]);


        Family::firstOrCreate([
            'name' => 'Administració i gestió',
            'code' => 'ADMIN'
        ]);

        Family::firstOrCreate([
            'name' => 'Comerç i marqueting',
            'code' => 'COMERÇ'
        ]);

        Family::firstOrCreate([
            'name' => 'Electricitat i electrònica/ Energia i aigua',
            'code' => 'ELECTRIC'
        ]);

        Family::firstOrCreate([
            'name' => 'Energia i aigua',
            'code' => 'ENERGIA'
        ]);

        Family::firstOrCreate([
            'name' => 'Fabricació mecànica',
            'code' => 'FABRIC'
        ]);

        Family::firstOrCreate([
            'name' => 'Instal·lació i manteniment',
            'code' => 'MANTENIMENT'
        ]);

        // Edificació
        // Ja no tenim estudis actius d'aquesta familia
        // Si que hi ha professors amb l'especialitat -> no podem marcar com softdeleted! Simplement no hi ha cap estudi
        // assignat a aquesta familia
        // També es maté per raons històriques
        Family::firstOrCreate([
            'name' => 'Edificació i obra civil',
            'code' => 'EDIFIC'
        ]);

        // IMPORTANT: PPAS, LLENGUES, FOL, ETC no són families, de fet són transversals a totes les families
        // No descomentar
//        Family::firstOrCreate([
//            'name' => 'Cursos d’accés',
//            'code' => 'CA'
//        ]);
//
//        Family::firstOrCreate([
//            'name' => 'Departament de llengües estrangeres',
//            'code' => 'ESTRANGER'
//        ]);
//
//        Family::firstOrCreate([
//            'name' => 'FOL',
//            'code' => 'FOL'
//        ]);

    }
}

if (!function_exists('initialize_forces')) {
    function initialize_forces()
    {
        Force::firstOrCreate([
            'name' => 'Mestres',
            'code' => 'MESTRES'
        ]);
        Force::firstOrCreate([
            'name' => "Professors d'ensenyament secundari",
            'code' => 'SECUNDARIA'
        ]);
        Force::firstOrCreate([
            'name' => 'Professors tècnics de formació professional',
            'code' => 'PT'
        ]);
        Force::firstOrCreate([
            'name' => "Professors d'escoles oficials d'idiomes",
            'code' => 'IDIOMES'
        ]);
    }
}


if (!function_exists('initialize_administrative_statuses')) {
    function initialize_administrative_statuses()
    {
        AdministrativeStatus::firstOrCreate([
            'name' => 'Funcionari/a amb plaça definitiva',
            'code' => 'FUNCIONARI DEF'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Funcionari/a propietari provisional',
            'code' => 'FUNCIONARI PROV'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Funcionari/a en pràctiques',
            'code' => 'FUNCIONARI PRAC'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Comissió de serveis',
            'code' => 'COMISSIÓ'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Interí/na',
            'code' => 'INTERÍ'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Substitut/a',
            'code' => 'SUBSTITUT'
        ]);

        AdministrativeStatus::firstOrCreate([
            'name' => 'Expert/a',
            'code' => 'EXPERT'
        ]);
    }
}

if (!function_exists('initialize_positions')) {
    function initialize_positions()
    {
        Position::firstOrCreate([
            'code' => 'TICTAC',
            'name' => 'Coordinador TIC/TAC',
            'shortname' => 'Coord. TIC'
        ]);

        Position::firstOrCreate([
            'code' => 'DIRE',
            'name' => 'Director',
            'shortname' => 'Director',
        ]);
    }
}

if (!function_exists('initialize_specialities')) {
    function initialize_specialities()
    {
        // Sanitat
        Specialty::firstOrCreate([
            'code' => '517',
            'name' => 'Processos diagnòstics clínics i productes ortoprotètics',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SANITAT')->id,
            'department_id' => Department::findByCode('SANITAT')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '518',
            'name' => 'Processos sanitaris',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SANITAT')->id,
            'department_id' => Department::findByCode('SANITAT')->id
        ]);

        // Cos -> Professors tècnics de formació professional
        Specialty::firstOrCreate([
            'code' => '619',
            'name' => 'Procediments de diagnòstic clínic i productes ortoprotètics',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('SANITAT')->id,
            'department_id' => Department::findByCode('SANITAT')->id
        ]);

        // Cos -> Professors tècnics de formació professional
        Specialty::firstOrCreate([
            'code' => '620',
            'name' => 'Procediments sanitaris i assistencials ',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('SANITAT')->id,
            'department_id' => Department::findByCode('SANITAT')->id
        ]);

        // Serveis socioculturals i a la comunitat
        Specialty::firstOrCreate([
            'code' => '508',
            'name' => 'Intervenció sociocomunitària',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SERVEIS')->id,
            'department_id' => Department::findByCode('SERVEIS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '625',
            'name' => 'Serveis a la comunitat',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('SERVEIS')->id,
            'department_id' => Department::findByCode('SERVEIS')->id
        ]);

        // Administració i finances
        Specialty::firstOrCreate([
            'code' => '501',
            'name' => 'Administració d’Empreses',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ADMIN')->id,
            'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '622',
            'name' => 'Processos de Gestió Administrativa',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ADMIN')->id,
            'department_id' => Department::findByCode('ADMINISTRACIÓ')->id
        ]);

        // Comerç i marqueting
        Specialty::firstOrCreate([
            'code' => '510',
            'name' => 'Organització i gestió comercial',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ADMIN')->id,
            'department_id' => Department::findByCode('COMERÇ')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '621',
            'name' => 'Processos comercials',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ADMIN')->id,
            'department_id' => Department::findByCode('COMERÇ')->id
        ]);

        // Informática
        Specialty::firstOrCreate([
            'code' => '507',
            'name' => 'Informàtica',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('INF')->id,
            'department_id' => Department::findByCode('INFORMÀTICA')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '627',
            'name' => 'Sistemes i aplicacions informàtiques',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('INF')->id,
            'department_id' => Department::findByCode('INFORMÀTICA')->id
        ]);

        // Electricitat i electrònica/ Energia i aigua
        Specialty::firstOrCreate([
            'code' => '524',
            'name' => 'Sistemes electrònics',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id,
            'department_id' => Department::findByCode('ELÈCTRICS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '525',
            'name' => 'Sistemes electrònics i automàtics',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id,
            'department_id' => Department::findByCode('ELÈCTRICS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '513',
            'name' => 'Organització i projectes de sistemes energètics',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id,
            'department_id' => Department::findByCode('ELÈCTRICS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '602',
            'name' => 'Equips electrònics',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id,
            'department_id' => Department::findByCode('ELÈCTRICS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '605',
            'name' => 'Instal·lació i manteniment d’equips tèrmics i de fluids',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id,
            'department_id' => Department::findByCode('ELÈCTRICS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '606',
            'name' => 'Instal·lacions electrotècniques',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ELECTRIC')->id,
            'department_id' => Department::findByCode('ELÈCTRICS')->id
        ]);

        // Fabricació mecànica/ Instal·lació i manteniment
        Specialty::firstOrCreate([
            'code' => '512',
            'name' => 'Organització i projectes de fabricació mecànica',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('FABRIC')->id,
            'department_id' => Department::findByCode('MECÀNICA')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '611',
            'name' => 'Mecanització i manteniment de màquines',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('FABRIC')->id,
            'department_id' => Department::findByCode('MECÀNICA')->id
        ]);

        // Arts gràfiques
        Specialty::firstOrCreate([
            'code' => '522',
            'name' => "Processos i productes d'arts gràfiques.",
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('ARTS')->id,
            'department_id' => Department::findByCode('ARTS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '623',
            'name' => 'Producció en arts gràfiques',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('ARTS')->id,
            'department_id' => Department::findByCode('ARTS')->id
        ]);


        // Edificació i obra civil
        Specialty::firstOrCreate([
            'code' => '504',
            'name' => 'Construccions civils i edificació',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('EDIFIC')->id
        ]);

        Specialty::firstOrCreate([
            'code' => '612',
            'name' => 'Oficina de projectes de construcció',
            'force_id' => Force::findByCode('PT')->id,
            'family_id' => Family::findByCode('EDIFIC')->id
        ]);

        // Cursos d’accés
        // Cos -> Secundària
        Specialty::firstOrCreate([
            'code' => 'MA',
            'name' => 'Matemàtiques',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'department_id' => Department::findByCode('PPAS')->id
        ]);

        Specialty::firstOrCreate([
            'code' => 'CAS',
            'name' => 'Castellà',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'department_id' => Department::findByCode('PPAS')->id
        ]);

        // Departament de llengües estrangeres
        // Cos -> Secundària
        Specialty::firstOrCreate([
            'code' => 'AN',
            'name' => 'Anglès',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'department_id' => Department::findByCode('PPAS')->id
        ]);

        // FOL
        Specialty::firstOrCreate([
            'code' => '505',
            'name' => 'Formació i orientació laboral',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'department_id' => Department::findByCode('FOL')->id
        ]);

    }
}



if (!function_exists('initialize_user_types')) {
    function initialize_user_types()
    {
        Role::firstOrCreate([
            'name' => 'Teacher'
        ]);
        Role::firstOrCreate([
            'name' => 'Student'
        ]);
        $teacher = UserType::firstOrCreate([
            'name' => 'Professor/a'
        ]);
        $teacher->roles()->save(Role::findByName('Teacher'));

        $student = UserType::firstOrCreate([
            'name' => 'Alumne/a'
        ]);
        $student->roles()->save(Role::findByName('Student'));

        UserType::firstOrCreate([
            'name' => 'Conserge'
        ]);

        UserType::firstOrCreate([
            'name' => 'Becari'
        ]);

        UserType::firstOrCreate([
            'name' => 'Administratiu/va'
        ]);

        UserType::firstOrCreate([
            'name' => 'Familiars'
        ]);
    }
}

if (!function_exists('configure_tenant')) {
    function configure_tenant($tenant)
    {

        Config::set('app.name', $tenant->name);
        //TODO Add shortname to tenants table
        Config::set('app.shortname', $tenant->name);
        Config::set('app.subdomain',$tenant->subdomain);
        Config::set('app.email_domain',$tenant->email_domain);
        Config::set('app.tenant_url','https://' . $tenant->subdomain . '.' . Config::get('app.domain'));
        config_google_api('/storage/app/' . $tenant->gsuite_service_account_path,$tenant->gsuite_admin_email);
        tune_google_client($tenant->gsuite_admin_email);
        Config::set('auth.providers.users.model', \App\Models\User::class);
        Config::set('auth.providers.users.driver', 'scool');
        Config::set('hashing.driver', 'sha1');

        //PUSHER
        if (app()->environment() === 'local') {
            Config::set('broadcasting.default','pusher_tenant');
            Config::set('broadcasting.connections.pusher_tenant.key', $tenant->pusher_app_key);
            Config::set('broadcasting.connections.pusher_tenant.secret', $tenant->pusher_app_secret);
            Config::set('broadcasting.connections.pusher_tenant.app_id', $tenant->pusher_app_id);
        }

        if (app()->environment() === 'production') {
            Config::set('broadcasting.default','pusher_tenant_production');
            Config::set('broadcasting.connections.pusher_tenant_production.key', $tenant->pusher_app_key);
            Config::set('broadcasting.connections.pusher_tenant_production.secret', $tenant->pusher_app_secret);
            Config::set('broadcasting.connections.pusher_tenant_production.app_id', $tenant->pusher_app_id);
        }


    }
}

if (!function_exists('apply_tenant')) {
    function apply_tenant($name=null)
    {
        $name = $name !== null ? $name : tenant_from_current_url();
        if ($tenant = get_tenant($name)) {
            $tenant->connect();
            $tenant->configure();
            Config::set('database.default', 'tenant');
        } else {
            dump('Tenant not found!');
        }
    }
}

if (!function_exists('add_fake_pending_teacher')) {
    function add_fake_pending_teacher()
    {
        initialize_administrative_statuses();
        seed_identifier_types();

        return PendingTeacher::create([
            'name' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'identifier' => '84008343S',
            'identifier_type' => 'NIF',
            'birthdate' => '1980-02-04',
            'street' => 'Alcanyiz',
            'number' => 40,
            'floor' => 3,
            'floor_number' => 1,
            'postal_code' => 43500,
            'locality_id' => 13560,
            'locality' => 'TORTOSA',
            'province_id' => 36,
            'province' => 'TARRAGONA',
            'email' => 'pepe@pardo.com',
            'other_emails' => 'pepepardojeans@gmail.com,ppardo@xtec.cat',
            'phone' => '977405689',
            'other_phones' => '977854265,9778542456',
            'mobile' => '679852467',
            'other_mobiles' => '651750489,689534729',
            'degree' => 'Enginyer en chapuzas varias',
            'other_degrees' => 'Master emerito por la Juan Carlos Primero',
            'languages' => 'Suajili',
            'profiles' => 'Master of the Universe',
            'other_training' => 'Fuster',
            'force_id' => 1,
            'force' => 'Mestres',
            'specialty_id' => 1,
            'specialty' => 'Processos sanitaris',
            'teacher_start_date' => '2015',
            'start_date' => '2017-03-06',
            'opositions_date' => '2009-06-10',
            'administrative_status_id' => AdministrativeStatus::findByName('Interí/na')->id,
            'administrative_status' => 'Interí/na',
            'destination_place' => 'La Seu Urgell',
            'teacher_id' => 8,
            'teacher' => 'Sergi Tur Badenas',
        ]);
    }
}

if (!function_exists('add_fake_pending_teachers')) {
    function add_fake_pending_teachers()
    {
        PendingTeacher::create([
            'name' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'identifier' => '84008343S',
            'identifier_type' => 'NIF',
            'birthdate' => '1980-02-04',
            'street' => 'Alcanyiz',
            'number' => 40,
            'floor' => 3,
            'floor_number' => 1,
            'postal_code' => 43500,
            'locality_id' => 13560,
            'locality' => 'TORTOSA',
            'province_id' => 36,
            'province' => 'TARRAGONA',
            'email' => 'pepe@pardo.com',
            'other_emails' => 'pepepardojeans@gmail.com,ppardo@xtec.cat',
            'phone' => '679852467',
            'other_phones' => '977854265,689578458',
            'mobile' => '679852467',
            'other_mobiles' => '651750489,689534729',
            'degree' => 'Enginyer en chapuzas varias',
            'other_degrees' => 'Master emerito por la Juan Carlos Primero',
            'languages' => 'Suajili',
            'profiles' => 'Master of the Universe',
            'other_training' => 'Fuster',
            'force_id' => 1,
            'force' => 'Mestres',
            'specialty_id' => 1,
            'specialty' => 'Processos sanitaris',
            'teacher_start_date' => '2015',
            'start_date' => '2017-03-06',
            'opositions_date' => '2009-06-10',
            'administrative_status_id' => 1,
            'administrative_status' => 'Interí/na',
            'destination_place' => 'La Seu Urgell',
            'teacher_id' => 8
        ]);

        PendingTeacher::create([
            'name' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeans',
            'identifier' => '69544773H',
            'identifier_type' => 'NIF',
            'birthdate' => '1975-04-14',
            'street' => 'Beseit',
            'number' => 14,
            'floor' => 2,
            'floor_number' => 3,
            'postal_code' => 43520,
            'locality_id' => 13560,
            'locality' => 'TORTOSA',
            'province_id' => 36,
            'province' => 'TARRAGONA',
            'email' => 'pepa@parda.com',
            'other_emails' => 'pepapardajeans@gmail.com,pparda@xtec.cat',
            'phone' => '674582467',
            'other_phones' => '977814265,689478458',
            'mobile' => '679852467',
            'other_mobiles' => '651750489,689534729',
            'degree' => 'Enginyera en chapuzas varias',
            'other_degrees' => 'Master a por la Juan Carlos Primero',
            'languages' => 'Suajila',
            'profiles' => 'Mistress of the Universe',
            'other_training' => 'Fustera',
            'force_id' => 1,
            'force' => 'Mestres',
            'specialty_id' => 1,
            'specialty' => 'Processos sanitaris',
            'teacher_start_date' => '2015',
            'start_date' => '2017-03-06',
            'opositions_date' => '2009-04-11',
            'administrative_status_id' => 5,
            'administrative_status' => 'Interí/na',
            'destination_place' => 'La Roca del Vallés',
            'teacher_id' => 9
        ]);
    }
}

if (! function_exists('seed_states')) {
    function seed_states()
    {

        DB::table('states')->delete();


        // Taken from //https://gist.github.com/daguilarm/0e93b73779f0306e5df2
        DB::table('states')->insert([
            ['id' => '1', 'country_code' => "ESP", 'name' => "Andalucía", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '2', 'country_code' => "ESP", 'name' => "Aragón", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '3', 'country_code' => "ESP", 'name' => "Asturias, Principado de", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '4', 'country_code' => "ESP", 'name' => "Baleares, Islas", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '5', 'country_code' => "ESP", 'name' => "Canarias", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '6', 'country_code' => "ESP", 'name' => "Cantabria", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '7', 'country_code' => "ESP", 'name' => "Castilla y León", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '8', 'country_code' => "ESP", 'name' => "Castilla - La Mancha", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '9', 'country_code' => "ESP", 'name' => "Cataluña", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '10', 'country_code' => "ESP", 'name' => "Comunidad Valenciana", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '11', 'country_code' => "ESP", 'name' => "Extremadura", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '12', 'country_code' => "ESP", 'name' => "Galicia", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '13', 'country_code' => "ESP", 'name' => "Madrid, Comunidad de", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '14', 'country_code' => "ESP", 'name' => "Murcia, Región de", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '15', 'country_code' => "ESP", 'name' => "Navarra, Comunidad Foral de", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '16', 'country_code' => "ESP", 'name' => "País Vasco", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '17', 'country_code' => "ESP", 'name' => "Rioja, La", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '18', 'country_code' => "ESP", 'name' => "Ceuta", 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '19', 'country_code' => "ESP", 'name' => "Melilla", 'created_at' => new DateTime, 'updated_at' => new DateTime]
        ]);
    }
}

if (! function_exists('seed_provinces')) {
    function seed_provinces()
    {
        seed_states();

        DB::table('provinces')->delete();


        // Taken from //https://gist.github.com/daguilarm/0e93b73779f0306e5df2
        DB::table('provinces')->insert([
            ['id' => '1','state_id' => 8, 'postal_code_prefix' => '02' , 'name' => 'Albacete', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '2','state_id' => 8, 'postal_code_prefix' => '13' , 'name' => 'Ciudad Real', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '3','state_id' => 8, 'postal_code_prefix' => '16' , 'name' => 'Cuenca', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '4','state_id' => 8, 'postal_code_prefix' => '19' , 'name' => 'Guadalajara', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '5','state_id' => 8, 'postal_code_prefix' => '45' , 'name' => 'Toledo', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '6','state_id' => 2, 'postal_code_prefix' => '22' , 'name' => 'Huesca', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '7','state_id' => 2, 'postal_code_prefix' => '44' , 'name' => 'Teruel', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '8','state_id' => 2, 'postal_code_prefix' => '50' , 'name' => 'Zaragoza', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '9','state_id' => 18, 'postal_code_prefix' => '51' , 'name' => 'Ceuta', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '10','state_id' => 13, 'postal_code_prefix' => '28' , 'name' => 'Madrid', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '11','state_id' => 14, 'postal_code_prefix' => '30' , 'name' => 'Murcia', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '12','state_id' => 19, 'postal_code_prefix' => '52' , 'name' => 'Melilla', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '13','state_id' => 15, 'postal_code_prefix' => '31' , 'name' => 'Navarra', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '14','state_id' => 1, 'postal_code_prefix' => '04' , 'name' => 'Almería', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '15','state_id' => 1, 'postal_code_prefix' => '11' , 'name' => 'Cádiz', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '16','state_id' => 1, 'postal_code_prefix' => '14' , 'name' => 'Córdoba', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '17','state_id' => 1, 'postal_code_prefix' => '18' , 'name' => 'Granada', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '18','state_id' => 1, 'postal_code_prefix' => '21' , 'name' => 'Huelva', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '19','state_id' => 1, 'postal_code_prefix' => '23' , 'name' => 'Jaén', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '20','state_id' => 1, 'postal_code_prefix' => '29' , 'name' => 'Málaga', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '21','state_id' => 1, 'postal_code_prefix' => '41' , 'name' => 'Sevilla', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '22','state_id' => 3, 'postal_code_prefix' => '33' , 'name' => 'Asturias', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '23','state_id' => 6, 'postal_code_prefix' => '39' , 'name' => 'Cantabria', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '24','state_id' => 7, 'postal_code_prefix' => '05' , 'name' => 'Ávila', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '25','state_id' => 7, 'postal_code_prefix' => '09' , 'name' => 'Burgos', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '26','state_id' => 7, 'postal_code_prefix' => '24' , 'name' => 'León', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '27','state_id' => 7, 'postal_code_prefix' => '34' , 'name' => 'Palencia', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '28','state_id' => 7, 'postal_code_prefix' => '37' , 'name' => 'Salamanca', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '29','state_id' => 7, 'postal_code_prefix' => '40' , 'name' => 'Segovia', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '30','state_id' => 7, 'postal_code_prefix' => '42' , 'name' => 'Soria', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '31','state_id' => 7, 'postal_code_prefix' => '47' , 'name' => 'Valladolid', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '32','state_id' => 7, 'postal_code_prefix' => '49' , 'name' => 'Zamora', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '33','state_id' => 9, 'postal_code_prefix' => '08' , 'name' => 'Barcelona', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '34','state_id' => 9, 'postal_code_prefix' => '17' , 'name' => 'Girona', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '35','state_id' => 9, 'postal_code_prefix' => '25' , 'name' => 'Lleida', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '36','state_id' => 9, 'postal_code_prefix' => '43' , 'name' => 'Tarragona', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '37','state_id' => 11, 'postal_code_prefix' => '06' , 'name' => 'Badajoz', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '38','state_id' => 11, 'postal_code_prefix' => '10' , 'name' => 'Cáceres', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '39','state_id' => 12, 'postal_code_prefix' => '15' , 'name' => 'Coruña, La', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '40','state_id' => 12, 'postal_code_prefix' => '27' , 'name' => 'Lugo', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '41','state_id' => 12, 'postal_code_prefix' => '32' , 'name' => 'Orense', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '42','state_id' => 12, 'postal_code_prefix' => '36' , 'name' => 'Pontevedra', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '43','state_id' => 17, 'postal_code_prefix' => '26' , 'name' => 'Rioja, La', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '44','state_id' => 4, 'postal_code_prefix' => '07' , 'name' => 'Baleares, Islas', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '45','state_id' => 16, 'postal_code_prefix' => '01' , 'name' => 'Álava', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '46','state_id' => 16, 'postal_code_prefix' => '20' , 'name' => 'Guipúzcoa', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '47','state_id' => 16, 'postal_code_prefix' => '48' , 'name' => 'Vizcaya', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '48','state_id' => 5, 'postal_code_prefix' => '35' , 'name' => 'Palmas, Las', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '49','state_id' => 5, 'postal_code_prefix' => '38' , 'name' => 'Tenerife, Santa Cruz De', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '50','state_id' => 10, 'postal_code_prefix' => '03' , 'name' => 'Alacant', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '51','state_id' => 10, 'postal_code_prefix' => '12' , 'name' => 'Castelló', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '52','state_id' => 10, 'postal_code_prefix' => '46' , 'name' => 'Valencia', 'created_at' => new DateTime, 'updated_at' => new DateTime]
        ]);
    }
}

/**
 * GOOGLE APPS / GSUITE HELPERS
 */

if (! function_exists('config_google_api')) {
    function config_google_api($file =  '/storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json',
                               $email = 'sergitur@iesebre.com') {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', base_path($file));
        Config::set('google.admin_email', $email);
    }
}

if (! function_exists('tune_google_client')) {
    function tune_google_client($gsuiteAdminUser  = 'sergitur@iesebre.com')
    {
        app()->extend(\PulkitJalan\Google\Client::class, function ($command, $app) use ($gsuiteAdminUser) {
            $config = $app['config']['google'];
            $user = $gsuiteAdminUser;
            return new Client($config, $user);
        });
    }
}

if (! function_exists('create_sample_google_user')) {
    function create_sample_google_user( $data = [
        'givenName' => 'Usuari',
        'familyName' => 'Esborrar',
        'primaryEmail' => 'usuariesborrar@iesebre.com'
    ]) {
        if (google_user_exists($data['primaryEmail'])) {
            return GoogleUser::get($data['primaryEmail']);
        }
        return GoogleUser::store($data);
    }
}

if (! function_exists('create_sample_ldap_user')) {
    function create_sample_ldap_user( $data = [
        'givenName' => 'Usuari',
        'familyName' => 'Esborrar',
        'primaryEmail' => 'usuariesborrar@iesebre.com'
    ]) {
        // TODO
//        if (google_user_exists($data['primaryEmail'])) {
//            return GoogleUser::get($data['primaryEmail']);
//        }
//        return GoogleUser::store($data);
    }
}

if (! function_exists('google_user_exists')) {
    function google_user_exists($user)
    {
        try {
            $user = (new GoogleDirectory())->user($user);
        } catch (Google_Service_Exception $e) {
            return false;
        }
        if (google_user_check($user)) return true;
        return false;
    }
}

if (! function_exists('google_user_get')) {
    /**
     * Get Google User.
     *
     * @param $user
     * @return mixed|void
     */
    function google_user_get($user)
    {
        return (new GoogleDirectory())->user($user);
    }
}

if (! function_exists('google_user_edit')) {
    /**
     * Edit Google User
     *
     * @param $user
     */
    function google_user_edit($user)
    {
        try {
            (new GoogleDirectory())->editUser($user);
        } catch (Google_Service_Exception $e) {
            dump('Error editing google user. ' . $e->getMessage());
        }

    }
}

if (! function_exists('google_user_create')) {
    /**
     * Create Google User
     *
     * @param $user
     */
    function google_user_create($user)
    {
        try {
            (new GoogleDirectory())->user($user);
        } catch (Google_Service_Exception $e) {
            dump('Error creating google user. ' . $e->getMessage());
        }
    }
}

if (! function_exists('google_user_remove')) {
    function google_user_remove($user)
    {
        (new GoogleDirectory())->removeUser($user);
    }
}

/**
 * GOOGLE GROUPS
 */


if (! function_exists('google_group_exists')) {
    function google_group_exists($group)
    {
        try {
            $group = (new GoogleDirectory())->group($group);
        } catch (Google_Service_Exception $e) {
            return false;
        }
        if (google_group_check($group)) return true;
        return false;
    }
}

if (! function_exists('google_group_get')) {
    /**
     * Get Google Group.
     *
     * @param $group
     * @return mixed|void
     */
    function google_group_get($group)
    {
        return (new GoogleDirectory())->group($group);
    }
}

if (! function_exists('google_group_create')) {
    /**
     * Create Google Group
     *
     * @param $group
     */
    function google_group_create($group)
    {
        try {
            (new GoogleDirectory())->group([
                'name' => $group,
                'email' => $group,
            ]);
        } catch (Google_Service_Exception $e) {
            dump('Error creating google group. ' . $e->getMessage());
        }

    }
}

if (! function_exists('google_group_remove')) {
    function google_group_remove($group)
    {
        try {
            (new GoogleDirectory())->removeGroup($group);
        } catch (Google_Service_Exception $e) {
            if($e->getErrors()[0]['message'] !== "Resource Not Found: groupKey") throw $e;
        }
    }
}

if (! function_exists('google_group_check')) {
    function google_group_check($group) {
        if(get_class($group) === 'stdClass') {
            return property_exists($group,'id') &&
                property_exists($group, 'name') &&
                property_exists($group, 'adminCreated') &&
                property_exists($group, 'aliases') &&
                property_exists($group, 'description') &&
                property_exists($group, 'directMembersCount') &&
                property_exists($group, 'email') &&
                property_exists($group, 'etag') &&
                property_exists($group, 'kind') &&
                property_exists($group, 'nonEditableAliases');
        } else {
            return get_class($group) === 'Google_Service_Directory_Group';
        }
    }
}

if (! function_exists('google_user_check')) {
    function google_user_check($user) {
        if(get_class($user) === 'Google_Service_Directory_User') return true;
        return array_key_exists('id', $user) &&
            array_key_exists('primaryEmail', $user) &&
            array_key_exists('isAdmin', $user) &&
            array_key_exists('familyName', $user) &&
            array_key_exists('fullName', $user) &&
            array_key_exists('lastLoginTime', $user) &&
            array_key_exists('creationTime', $user) &&
            array_key_exists('suspended', $user) &&
            array_key_exists('suspensionReason', $user) &&
            array_key_exists('thumbnailPhotoUrl', $user) &&
            array_key_exists('orgUnitPath', $user) &&
            array_key_exists('organizations', $user);
    }
}

if (! function_exists('get_photo_slugs_from_path')) {
    /**
     * Get photos slugs from path.
     *
     * @param $path
     * @return \Illuminate\Support\Collection|static
     */
    function get_photo_slugs_from_path($path)
    {
        $photos = collect();
        if (Storage::exists($path)) {
            $photos = collect(File::allFiles(Storage::path($path)))->map(function ($photo) {
                return [
                    'file' => $photo,
                    'filename' => $filename = $photo->getFilename(),
                    'slug' => str_slug($filename,'-')
                ];
            });
        }
        return $photos;
    }
}

if (! function_exists('first_or_create_identifier_type')) {
    /**
     * Create contact type if not exists and return new o already existing contact type.
     */
    function first_or_create_identifier_type($name)
    {
        try {
            return IdentifierType::create(['name' => $name]);
        } catch (Illuminate\Database\QueryException $e) {
            return IdentifierType::where('name', $name);
        }
    }
}

if (! function_exists('seed_identifier_types')) {
    /**
     * Create identifier types.
     */
    function seed_identifier_types()
    {
        first_or_create_identifier_type('NIF');
        first_or_create_identifier_type('Pasaporte');
        first_or_create_identifier_type('NIE');
        first_or_create_identifier_type('TIS');
    }
}
if (! function_exists('fake_personal_data_teachers')) {
    function fake_personal_data_teachers() {
        first_or_create_identifier_type('NIF');
        $nif = IdentifierType::findByName('NIF')->id;

        // Note: names are already assigned in initialize_teachers helper
        Teacher::findByCode('041')->user->assignPersonalData([
            'identifier_id' => Identifier::firstOrCreate([
                'value' => '14268002K',
                'type_id' => $nif
            ])->id,
            'birthdate' => Carbon::parse('1978-03-02'),
            'birthplace_id' => Location::findByName('BARCELONA')->id,
            'gender' => 'Home',
            'civil_status' => 'Casat/da',
            'notes' => "Coordinador d'informàtica",
            'mobile' => '679525437',
            'other_mobiles' => '650192821',
            'email' => 'sergiturbadenas@gmail.com',
            'other_emails' => 'acacha@gmail.com,sergitur@iesebre.com',
            'phone' => '977500949',
            'other_phones' => '9677508695,977500949'
        ])->assignAddress(Address::create([
            'name' => 'C/ Beseit',
            'number' => '16',
            'floor' => '4',
            'floor_number' => '2',
            'location_id' => Location::findByName('TORTOSA')->id,
            'province_id' => Province::findByName('TARRAGONA')->id,
        ]))->assignTeacherData([  // Code ius already assigned at initialize_teachers helper
            'administrative_status_id' => AdministrativeStatus::findByName('Substitut/a')->id,
            'specialty_id' => Specialty::findByCode('507')->id,
            'titulacio_acces' => 'Enginyer Superior en Telecomunicacions',
            'altres_titulacions' => 'Postgrau en Programari Lliure',
            'idiomes' => 'Certificat Aptitud Anglès Escola Oficial Idiomes',
            'altres_formacions' => 'Nivell D de Català',
            'data_inici_treball' => '29/09/2006',
            'data_incorporacio_centre' => Carbon::parse('2009-09-01'),
            'data_superacio_oposicions' => 'Juny 2008',
            'perfil_professional' => 'De perfil més guapo sí',
            'lloc_destinacio_definitiva' => 'Al quinto pino!',
        ]);
    }
}

if (! function_exists('autoassign_photos_to_teachers')) {
    function autoassign_photos_to_teachers($path, $tenant)
    {
        $teachersWithoutFoto = Teacher::with(['user'])->get()->filter(function($teacher) {
            if ($teacher->user) return $teacher->user->photo === null;
        });
        $counter= 0;
        $availablephotos = collect(File::allFiles($path));
        foreach ($teachersWithoutFoto as $teacher) {
            $foundPhotos = $availablephotos->filter(function($photo) use ($teacher) {
                return str_contains($photo->getFileName(),$teacher->code);
            });
            if ($foundPhoto = $foundPhotos->first()) {
                $teacher->user->assignPhoto($foundPhoto->getPathname(),$tenant);
                $counter++;
            }
        }

        return $counter;
    }
}

if (!function_exists('initialize_teacher_photos')) {
    function initialize_teacher_photos() {
        $tenant = Config::get('database.connections.tenant.database');
        $src = base_path('storage/photos/' . $tenant . '/teachers/*.*');

        $dest = storage_path('app/' . $tenant . '/teacher_photos');
        shell_exec("cp -r $src $dest");
        autoassign_photos_to_teachers($dest,$tenant);
    }
}

if (!function_exists('initialize_head_departments')) {
    function initialize_head_departments()
    {
        $department = Department::findByCode('PPAS');
        $department->head = User::findByName('Carme Aznar Pedret')->id;
        $department->save();

        $department = Department::findByCode('FOL');
        $department->head = User::findByName('Carmina Andreu Pons')->id;
        $department->save();

        $department = Department::findByCode('ADMINISTRACIÓ');
        $department->head = User::findByName('Eduard Ralda Simó')->id;
        $department->save();

        $department = Department::findByCode('COMERÇ');
        $department->head = User::findByName('Agustí Moreso García')->id;
        $department->save();

        $department = Department::findByCode('ELÈCTRICS');
        $department->head = User::findByName('Xavi Bel Fernandez')->id;
        $department->save();

        $department = Department::findByCode('INFORMÀTICA');
        $department->head = User::findByName('Jordi Varas Aliau')->id;
        $department->save();

        $department = Department::findByCode('MECÀNICA');
        $department->head = User::findByName('Fernando Segura Venezia')->id;
        $department->save();

        $department = Department::findByCode('SANITAT');
        $department->head = User::findByName('Berta Safont Recatalà')->id;
        $department->save();

        $department = Department::findByCode('SERVEIS');
        $department->head = User::findByName('Elena Mauri Cuenca')->id;
        $department->save();

        $department = Department::findByCode('ARTS');
        $department->head = User::findByName('Gerard Domènech Vendrell')->id;
        $department->save();
    }
}

if (!function_exists('initialize_departments')) {
    function initialize_departments()
    {
        Department::firstOrCreate([
            'name' => 'Departament preparació proves d\'accès a superior',
            'shortname' => 'Curs d\'accès | Àngles',
            'code' => 'PPAS',
            'order' => 1,
            'email' => 'ppas@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament de formació i orientació laboral',
            'shortname' => 'Formació i orientació laboral',
            'code' => 'FOL',
            'order' => 2,
            'email' => 'fol@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament d\'administració i gestió',
            'shortname' => 'Administració i gestió',
            'code' => 'ADMINISTRACIÓ',
            'order' => 3,
            'email' => 'administracio@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament de comerç i màrqueting',
            'shortname' => 'Comerç i màrqueting',
            'code' => 'COMERÇ',
            'order' => 4,
            'email' => 'comerc@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament d\'electricitat i electrònica',
            'shortname' => 'Electricitat i electrònica',
            'code' => 'ELÈCTRICS',
            'order' => 5,
            'email' => 'electrics@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament d\'informàtica',
            'shortname' => 'Informàtica',
            'code' => 'INFORMÀTICA',
            'order' => 6,
            'email' => 'informatica@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament de fabricació mecànica',
            'shortname' => 'Fabricació mecànica',
            'code' => 'MECÀNICA',
            'order' => 7,
            'email' => 'mecanica@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament de sanitat',
            'shortname' => 'Sanitat',
            'code' => 'SANITAT',
            'order' => 8,
            'email' => 'sanitat@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament de serveis socioculturals i a la comunitat',
            'shortname' => 'Serveis socioculturals i a la comunitat',
            'code' => 'SERVEIS',
            'order' => 9,
            'email' => 'serveis@iesebre.com'
        ]);

        Department::firstOrCreate([
            'name' => 'Departament d\'Arts gràfiques',
            'shortname' => 'Arts gràfiques',
            'code' => 'ARTS',
            'order' => 10,
            'email' => 'arts@iesebre.com'
        ]);

    }
}

if (!function_exists('name')) {
    /**
     * Name
     *
     * @param $givenName
     * @param $sn1
     * @param string $sn2
     * @return string
     */
    function name($givenName,$sn1, $sn2= '') {
        return trim((ucfirst(strtolower(trim($givenName))) . ' ' . ucfirst(strtolower(trim($sn1))) . ' ' . ucfirst(strtolower(trim($sn2)))));
    }
}

if (!function_exists('format_name')) {

    function format_name($name) {
        return trim(ucfirst(strtolower($name)));
    }
}

if (!function_exists('fullname')) {
    function fullname($givenName, $sn1, $sn2 = '')
    {
        $fullname = trim($sn1);
        if ($sn2) {
            $fullname = $fullname . ' ' . trim($sn2);
        }
        $fullname = $fullname . ', ' . trim($givenName);
        return trim($fullname);
    }
}

if (!function_exists('fullsurname')) {
    function fullsurname( $sn1, $sn2 = '')
    {
        $fullname = trim($sn1);
        if ($sn2) {
            $fullname = $fullname . ' ' . trim($sn2);
        }
        return trim($fullname);
    }
}

if (!function_exists('create_fake_job')) {
    function create_fake_job()
    {
        JobType::create(['name' => 'Professor/a']);
        Force::create([
            'name' => 'Secundària',
            'code' => 'SECUNDARIA'
        ]);

        $family = Family::create([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);
        Specialty::create([
            'name' => 'Informàtica',
            'code' => '507',
            'force_id' => Force::findByCode('SECUNDARIA'),
            'family_id' => $family->id
        ]);

        return Job::firstOrCreate([
            'code' => '01',
            'type_id' => JobType::findByName('Professor/a')->id,
            'specialty_id' => Specialty::findByCode('507')->id,
            'family_id' => Family::findByCode('SANITAT')->id,
            'order' => 1
        ]);
    }
}

if (!function_exists('create_fake_teacher')) {
    /**
     * Create fake teacher.
     *
     * @return mixed
     */
    function create_fake_teacher()
    {
        $user = (new UserRepository())->store(
            (object) [
                'name' => name('Pepe', 'Pardo', 'Jeans'),
                'email' => 'pepepardo@iesebre.com',
                'photo' => 'user_photos/photo.png'
            ]
        );

        $role = Role::firstOrCreate([
            'name' => 'Teacher',
            'guard_name' => 'web'
        ]);

        $user->addRole($role);

        (new TeacherRepository())->store((object) [
            'user_id' => $user->id,
            'code' => Teacher::firstAvailableCode(),
            'administrative_status_id' => 1,
            'specialty_id' => 1,
            'titulacio_acces' => 'Enginyer',
            'altres_titulacions' => 'Master',
            'idiomes' => 'Anglès',
            'altres_formacions' => 'Nivell D Català',
            'perfil_professional' => 'CLIC',
            'data_inici_treball' => '2009',
            'data_incorporacio_centre' => '2008-02-03',
            'data_superacio_oposicions' => 'juny 2006',
            'lloc_destinacio_definitiva' => 'Quinto Pino'
        ]);

        //Create identifier
        $type = IdentifierType::firstOrCreate([
            'name' => 'NIF'
        ]);
        $identifier = Identifier::firstOrCreate([
            'value' => '54895745N',
            'type_id' => $type->id
        ]);

        $location = Location::firstOrCreate([
            'name' => 'TORTOSA',
            'postalcode' => 43500
        ]);

        $province = Province::firstOrCreate([
            'state_id' => 9,
            'name' => 'Tarragona',
            'postal_code_prefix' => '43'
        ]);

        //Create person
        $person = (new PersonRepository())->store((object) [
            'user_id' => $user->id,
            'identifier_id' => $identifier->id,
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'birthdate' => '2008-02-48',
            'birthplace_id' => $location->id,
            'gender' => 'Home',
            'civil_status' => 'Solter/a',
            'phone' => '977858689',
            'other_phones' => '["977485868","977485969"]',
            'mobile' => '678598458',
            'other_mobiles' => '["645698458","678548558"]',
            'email' => 'myemailpepepardo@gmail.com',
            'other_emails' => '["myemailpepepardo@hotmail.com","myemailpepepardo@yahoo.com"]',
            'notes' => 'Bla bla bla'
        ]);

        //Create address
        Address::create([
            'person_id' => $person->id,
            'name' => 'C/Beseit',
            'number' => '24',
            'floor' => '4t',
            'floor_number' => '2a',
            'location_id' => $location->id,
            'province_id' => $province->id
        ]);

        return $user;
    }
}

if (!function_exists('create_fake_audit_log_entries')) {
    function create_fake_audit_log_entries()
    {
        $job = Job::create([
            'code' => '999',
            'specialty_id' => 1,
            'family_id' => 1,
            'order' => 1,
            'notes' => 'Bla bla',
        ]);

        Revision::create([
            'revisionable_type' => Job::class,
            'revisionable_id' => $job->id,
            'user_id' => 1,
            'key' => 'order',
            'old_value' => 1,
            'new_value' => 2
        ]);
    }
}

if (!function_exists('check_audit_log_entry')) {
    function check_audit_log_entry($entry)
    {
        return array_key_exists('id', $entry) &&
            array_key_exists('element', $entry) &&
            array_key_exists('revisionable_type', $entry) &&
            array_key_exists('revisionable_id', $entry) &&
            array_key_exists('type', $entry) &&
            array_key_exists('user', $entry) &&
            array_key_exists('user_id', $entry) &&
            array_key_exists('user_hashid', $entry) &&
            array_key_exists('user_description', $entry) &&
            array_key_exists('key', $entry) &&
            array_key_exists('field_name', $entry) &&
            array_key_exists('old_value', $entry) &&
            array_key_exists('new_value', $entry) &&
            array_key_exists('created_at', $entry) &&
            array_key_exists('formatted_created_at_diff', $entry);

    }
}


if (!function_exists('check_sheet_job')) {
    /**
     * Check sheet job.
     *
     * @param $job
     * @return bool
     */
    function check_sheet_job($job)
    {
        return array_key_exists('id', $job) &&
            array_key_exists('active_user_hash_id', $job) &&
            array_key_exists('active_user_name', $job) &&
            array_key_exists('active_user_email', $job) &&
            array_key_exists('active_user_description', $job);
    }
}

if (!function_exists('check_sheet_job_for_holders')) {
    /**
     * Check sheet job.
     *
     * @param $job
     * @return bool
     */
    function check_sheet_job_for_holders($job)
    {
        return array_key_exists('id', $job) &&
            array_key_exists('code', $job) &&
            array_key_exists('holder_hashid', $job) &&
            array_key_exists('holder_code', $job) &&
            array_key_exists('holder_name', $job) &&
            array_key_exists('holder_email', $job) &&
            array_key_exists('holder_description', $job);
    }
}


if (!function_exists('check_job')) {
    function check_job($job)
    {
        return array_key_exists('id', $job) &&
            array_key_exists('type', $job) &&
            array_key_exists('type_id', $job) &&
            array_key_exists('code', $job) &&
            array_key_exists('holder_id', $job) &&
            array_key_exists('holder_hashid', $job) &&
            array_key_exists('holder_code', $job) &&
            array_key_exists('holder_name', $job) &&
            array_key_exists('holder_description', $job) &&
            array_key_exists('active_user_hash_id', $job) &&
            array_key_exists('active_user_code', $job) &&
            array_key_exists('active_user_name', $job) &&
            array_key_exists('active_user_description', $job) &&
            array_key_exists('substitutes', $job) &&
            array_key_exists('fullcode', $job) &&
            array_key_exists('order', $job) &&
            array_key_exists('family', $job) &&
            array_key_exists('family_id', $job) &&
            array_key_exists('family_code', $job) &&
            array_key_exists('family_description', $job) &&
            array_key_exists('specialty', $job) &&
            array_key_exists('specialty_id', $job) &&
            array_key_exists('specialty_code', $job) &&
            array_key_exists('specialty_description', $job) &&
            array_key_exists('formatted_created_at_diff', $job) &&
            array_key_exists('formatted_created_at', $job) &&
            array_key_exists('formatted_updated_at', $job) &&
            array_key_exists('formatted_updated_at_diff', $job) &&
            array_key_exists('notes', $job);

    }
}

if (!function_exists('check_teacher')) {
    /**
     * Check teacher.
     *
     * @param $teacher
     * @return bool
     */
    function check_teacher($teacher) {
        return array_key_exists('id', $teacher) &&
            array_key_exists('user_id', $teacher) &&
            array_key_exists('code', $teacher) &&
            array_key_exists('formatted_created_at_diff', $teacher) &&
            array_key_exists('formatted_created_at', $teacher) &&
            array_key_exists('formatted_updated_at', $teacher) &&
            array_key_exists('formatted_updated_at_diff', $teacher) &&
            array_key_exists('hashid', $teacher) &&
            array_key_exists('name', $teacher) &&
            array_key_exists('email', $teacher) &&
            array_key_exists('fullname', $teacher) &&
            array_key_exists('department_code', $teacher) &&
            array_key_exists('department', $teacher) &&
            array_key_exists('specialty', $teacher) &&
            array_key_exists('specialty_code', $teacher) &&
            array_key_exists('family', $teacher) &&
            array_key_exists('family_code', $teacher) &&
            array_key_exists('force', $teacher) &&
            array_key_exists('administrative_status', $teacher) &&
            array_key_exists('administrative_status_code', $teacher) &&
            array_key_exists('job', $teacher) &&
            array_key_exists('job_description', $teacher) &&
            array_key_exists('job_start_at', $teacher) &&
            array_key_exists('job_end_at', $teacher) &&
            array_key_exists('job_family', $teacher) &&
            array_key_exists('job_specialty', $teacher) &&
            array_key_exists('job_specialty_code', $teacher) &&
            array_key_exists('job_order', $teacher) &&
            array_key_exists('full_search', $teacher) &&
            array_key_exists('titulacio_acces', $teacher) &&
            array_key_exists('altres_titulacions', $teacher) &&
            array_key_exists('idiomes', $teacher) &&
            array_key_exists('perfil_professional', $teacher) &&
            array_key_exists('altres_formacions', $teacher) &&
            array_key_exists('data_superacio_oposicions', $teacher) &&
            array_key_exists('lloc_destinacio_definitiva', $teacher) &&
            array_key_exists('data_inici_treball', $teacher) &&
            array_key_exists('data_incorporacio_centre', $teacher) &&
            array_key_exists('person_notes', $teacher) &&
            array_key_exists('givenName', $teacher) &&
            array_key_exists('sn1', $teacher) &&
            array_key_exists('sn2', $teacher) &&
            array_key_exists('person_notes', $teacher) &&
            array_key_exists('givenName', $teacher) &&
            array_key_exists('sn1', $teacher) &&
            array_key_exists('sn2', $teacher) &&
            array_key_exists('birthdate', $teacher) &&
            array_key_exists('birthplace', $teacher) &&
            array_key_exists('gender', $teacher) &&
            array_key_exists('phone', $teacher) &&
            array_key_exists('other_phones', $teacher) &&
            array_key_exists('mobile', $teacher) &&
            array_key_exists('other_mobiles', $teacher) &&
            array_key_exists('personal_email', $teacher) &&
            array_key_exists('other_emails', $teacher) &&
            array_key_exists('identifier', $teacher) &&
            array_key_exists('identifier_type', $teacher) &&
            array_key_exists('address_name', $teacher) &&
            array_key_exists('address_number', $teacher) &&
            array_key_exists('address_floor', $teacher) &&
            array_key_exists('address_floor_number', $teacher) &&
            array_key_exists('address_location', $teacher) &&
            array_key_exists('address_postalcode', $teacher) &&
            array_key_exists('address_province', $teacher)  &&
            array_key_exists('media', $teacher);
    }
}

/**
 * CURRÍCULUM
 */

if (! function_exists('initialize_subject_group_tags')) {
    function initialize_subject_group_tags()
    {
        SubjectGroupTag::firstOrCreate([
            'value' => 'Normal',
            'description' => 'Mòdul normal',
            'color' => 'amber',
        ]);

        SubjectGroupTag::firstOrCreate([
            'value' => 'FCT',
            'description' => 'Mòdul Formació en Centres de Treball',
            'color' => 'teal lighten-1',
        ]);

        SubjectGroupTag::firstOrCreate([
            'value' => 'Síntesi',
            'description' => 'Mòdul de síntesi o Projecte',
            'color' => 'cyan lighten-1',
        ]);

        SubjectGroupTag::firstOrCreate([
            'value' => 'Externa',
            'description' => 'Mòdul transversal típus Anglès, FOL...',
            'color' => 'pink lighten-1',
        ]);
    }
}

// SUBJECTS -> Unitat formatives

if (!function_exists('initialize_subjects')) {
    function initialize_subjects()
    {
        $mp_start_date = '2017-09-15';
        $mp_end_date = '2018-06-01';

        // Comú

        // Per estudis i mòduls:

        // Informàtica
//        Mòdul professional 1: sistemes informàtics

        // http://portaldogc.gencat.cat/utilsEADOP/PDF/6958/1444503.pdf
        // O matrícula TIC

        $study = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM'
        ]);

        // No existeix 1DAM -> és comú amb 1rASIX
//        $course1 = Course::create([
//            'name' => 'Desenvolupament Aplicacions Multiplataforma',
//            'code' => '1DAM'
//        ]);
        $course2 = Course::firstOrCreate([
            'code' => '2DAM',
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'order' => 2,
            'study_id' => $study->id
        ]);

//        Mòdul professional (Subject Group) 7: desenvolupament d’interfícies
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Desenvolupament d’interfícies',
            'name' => 'Desenvolupament d’interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);


        Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Disseny i implementació d’interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 79,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => 'Preparació i distribució d’aplicacions',
            'shortname'=> 'Preparació i distribució d’aplicacions',
            'code' =>  'DAM_MP7_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 20
        ]);

        // *************************************
        // * Administració
        // *************************************

        $study = Study::firstOrCreate([
            'name' => 'Gestió administrativa',
            'shortname' => 'Gestió administrativa',
            'code' => 'GAD',
            'subject_groups_number' => 13,
            'subjects_number' => 37
        ]);

        $family = Family::findByCode('COMERÇ');
        $family->addStudy($study);

        $course1 = Course::firstOrCreate([
            'code' => '1GAD',
            'name' => '1r de Gestió administrativa',
            'study_id' => $study->id,
            'order' => 1
        ]);

        $course2 = Course::firstOrCreate([
            'code' => '2GAD',
            'name' => '2n de Gestió administrativa',
            'study_id' => $study->id,
            'order' => 2
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Operacions de compravenda',
            'name' => 'Operacions de compravenda',
            'code' =>  'GAD_MP2',
            'number' => 2,
            'study_id' => $study->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Circuit administratiu de la compravenda',
            'shortname'=> 'Circuit administratiu de la compravenda',
            'code' =>  'GAD_MP2_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 77
        ]);

        Subject::firstOrCreate([
            'name' => "Gestió d'estocs",
            'shortname'=> "Gestió d'estocs",
            'code' =>  'GAD_MP2_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Declaracions fiscals",
            'shortname'=> "Declaracions fiscals",
            'code' =>  'GAD_MP2_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 55
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Operacions de recursos humans',
            'name' => 'Operacions de recursos humans',
            'code' =>  'GAD_MP3',
            'number' => 3,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Selecció i formació",
            'shortname'=> "Selecció i formació",
            'code' =>  'GAD_MP3_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Contractació i retribució",
            'shortname'=> "Contractació i retribució",
            'code' =>  'GAD_MP3_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Processos de l’activitat laboral",
            'shortname'=> "Processos de l’activitat laboral",
            'code' =>  'GAD_MP3_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Operacions de gestió de tresoreria',
            'name' => 'Operacions de gestió de tresoreria',
            'code' =>  'GAD_MP4',
            'number' => 4,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Control de tresoreria",
            'shortname'=> "Control de tresoreria",
            'code' =>  'GAD_MP4_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Instruments financers i d’assegurances",
            'shortname'=> "Instruments financers i d’assegurances",
            'code' =>  'GAD_MP4_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66
        ]);

        Subject::firstOrCreate([
            'name' => "Operacions financeres bàsiques",
            'shortname'=> "Operacions financeres bàsiques",
            'code' =>  'GAD_MP4_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Tècnica comptable',
            'name' => 'Tècnica comptable',
            'code' =>  'GAD_MP5',
            'number' => 5,
            'study_id' => $study->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Patrimoni i metodologia comptable",
            'shortname'=> "Patrimoni i metodologia comptable",
            'code' =>  'GAD_MP5_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Cicle comptable bàsic",
            'shortname'=> "Cicle comptable bàsic",
            'code' =>  'GAD_MP5_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Cicle comptable mitjà",
            'shortname'=> "Cicle comptable mitjà",
            'code' =>  'GAD_MP5_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 99
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Tractament informàtic',
            'name' => 'Tractament informàtic',
            'code' =>  'GAD_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 231,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Tecnologia i comunicacions digitals",
            'shortname'=> "Tecnologia i comunicacions digitals",
            'code' =>  'GAD_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Ordinografia i gravació de dades",
            'shortname'=> "Ordinografia i gravació de dades",
            'code' =>  'GAD_MP7_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Tractament de la informació escrita i numèrica",
            'shortname'=> "Tractament de la informació escrita i numèrica",
            'code' =>  'GAD_MP7_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66
        ]);

        Subject::firstOrCreate([
            'name' => "Tractament de dades i integració d’aplicacions",
            'shortname'=> "Tractament de dades i integració d’aplicacions",
            'code' =>  'GAD_MP7_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Presentacions multimèdia de continguts",
            'shortname'=> "Presentacions multimèdia de continguts",
            'code' =>  'GAD_MP7_UF5',
            'number' => 5,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Eines d’internet per a l’empresa",
            'shortname'=> "Eines d’internet per a l’empresa",
            'code' =>  'GAD_MP7_UF6',
            'number' => 6,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Formació i orientació laboral',
            'name' => 'Formació i orientació laboral',
            'code' =>  'GAD_MP12',
            'number' => 12,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => "Incorporació al treball",
            'shortname'=> "Incorporació al treball",
            'code' =>  'GAD_MP12_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66
        ]);

        Subject::firstOrCreate([
            'name' => "Prevenció dels riscos laborals",
            'shortname'=> "Prevenció dels riscos laborals",
            'code' =>  'GAD_MP12_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Comunicació empresarial i atenció al client',
            'name' => 'Comunicació empresarial i atenció al client',
            'code' =>  'GAD_MP1',
            'number' => 1,
            'study_id' => $study->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Comunicació empresarial oral",
            'shortname'=> "Comunicació empresarial oral",
            'code' =>  'GAD_MP1_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Comunicació empresarial escrita",
            'shortname'=> "Comunicació empresarial escrita",
            'code' =>  'GAD_MP1_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66
        ]);

        Subject::firstOrCreate([
            'name' => "Sistemes d’arxiu",
            'shortname'=> "Sistemes d’arxiu",
            'code' =>  'GAD_MP1_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Atenció al client/usuari",
            'shortname'=> "Atenció al client/usuari",
            'code' =>  'GAD_MP1_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Tractament de la documentació comptable',
            'name' => 'Tractament de la documentació comptable',
            'code' =>  'GAD_MP6',
            'number' => 6,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Preparació i codificació comptable",
            'shortname'=> "Preparació i codificació comptable",
            'code' =>  'GAD_MP6_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 40
        ]);

        Subject::firstOrCreate([
            'name' => "Registre comptable",
            'shortname'=> "Registre comptable",
            'code' =>  'GAD_MP6_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 60
        ]);

        Subject::firstOrCreate([
            'name' => "Comptes anuals bàsics",
            'shortname'=> "Comptes anuals bàsics",
            'code' =>  'GAD_MP6_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 16
        ]);

        Subject::firstOrCreate([
            'name' => "Verificació i control intern",
            'shortname'=> "Verificació i control intern",
            'code' =>  'GAD_MP6_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 16
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Operacions administratives de suport',
            'name' => 'Operacions administratives de suport',
            'code' =>  'GAD_MP8',
            'number' => 8,
            'study_id' => $study->id,
            'hours' => 66,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Selecció i tractament de la informació",
            'shortname'=> "Selecció i tractament de la informació",
            'code' =>  'GAD_MP8_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Operacions logístiques de suport administratiu",
            'shortname'=> "Operacions logístiques de suport administratiu",
            'code' =>  'GAD_MP8_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Anglès',
            'name' => 'Anglès',
            'code' =>  'GAD_MP9',
            'number' => 9,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Anglès tècnic",
            'shortname'=> "Anglès tècnic",
            'code' =>  'GAD_MP9_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 99
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Empresa i administració',
            'name' => 'Empresa i administració',
            'code' =>  'GAD_MP10',
            'number' => 10,
            'study_id' => $study->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => "Innovació i emprenedoria",
            'shortname'=> "Innovació i emprenedoria",
            'code' =>  'GAD_MP10_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Empresa i activitat econòmica",
            'shortname'=> "Empresa i activitat econòmica",
            'code' =>  'GAD_MP10_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 24
        ]);

        Subject::firstOrCreate([
            'name' => "Administracions públiques",
            'shortname'=> "Administracions públiques",
            'code' =>  'GAD_MP10_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 60
        ]);

        Subject::firstOrCreate([
            'name' => "Fiscalitat empresarial bàsica",
            'shortname'=> "Fiscalitat empresarial bàsica",
            'code' =>  'GAD_MP10_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 48
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => "Empresa a l'aula",
            'name' => "Empresa a l'aula",
            'code' =>  'GAD_MP11',
            'number' => 11,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => "Empresa a l'aula",
            'shortname'=> "Empresa a l'aula",
            'code' =>  'GAD_MP11_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 132
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => "Formació en centres de treball",
            'name' => "Formació en centres de treball",
            'code' =>  'GAD_MP13',
            'number' => 13,
            'study_id' => $study->id,
            'hours' => 350,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => "Formació en centres de treball",
            'shortname'=> "Formació en centres de treball",
            'code' =>  'GAD_MP13_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 350
        ]);

        $study = Study::firstOrCreate([
            'name' => 'Administració i finances',
            'shortname' => 'Administració i finances',
            'code' => 'AIF',
        ]);

        $course1 = Course::firstOrCreate([
            'code' => '1AIF',
            'name' => "1r d'Administració i finances",
            'order' => 1,
            'study_id' => $study->id
        ]);

        $course2 = Course::firstOrCreate([
            'code' => '2AIF',
            'name' => "2n d'Administració i finances",
            'order' => 2,
            'study_id' => $study->id
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Comunicació i atenció al client',
            'name' => 'Comunicació i atenció al client',
            'code' =>  'AIF_MP1',
            'number' => 1,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Processos de comunicació oral a l’empresa i atenció al client',
            'shortname'=> 'Processos de comunicació oral a l’empresa i atenció al client',
            'code' =>  'AIF_MP1_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 55
        ]);

        Subject::firstOrCreate([
            'name' => 'Processos de comunicació escrita a l’empresa i atenció al client',
            'shortname'=> 'Processos de comunicació escrita a l’empresa i atenció al client',
            'code' =>  'AIF_MP1_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 44
        ]);

        Subject::firstOrCreate([
            'name' => 'Gestió documental, arxiu i registre',
            'shortname'=> 'Gestió documental, arxiu i registre',
            'code' =>  'AIF_MP1_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Gestió de la documentació jurídica i empresarial',
            'name' => 'Gestió de la documentació jurídica i empresarial',
            'code' =>  'AIF_MP2',
            'number' => 2,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Organització de la documentació jurídica empresarial',
            'shortname'=> 'Organització de la documentació jurídica empresarial',
            'code' =>  'AIF_MP2_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => 'Contractació empresarial',
            'shortname'=> 'Contractació empresarial',
            'code' =>  'AIF_MP2_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => 'Tramitació davant les administracions públiques',
            'shortname'=> 'Tramitació davant les administracions públiques',
            'code' =>  'AIF_MP2_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => "Procés integral de l'activitat comercial",
            'name' => "Procés integral de l'activitat comercial",
            'code' =>  'AIF_MP3',
            'number' => 3,
            'study_id' => $study->id,
            'hours' => 264,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Patrimoni i metodologia comptable',
            'shortname'=> 'Patrimoni i metodologia comptable',
            'code' =>  'AIF_MP3_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => 'Fiscalitat empresarial',
            'shortname'=> 'Fiscalitat empresarial',
            'code' =>  'AIF_MP3_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => 'Gestió administrativa de les operacions de compravenda i tresoreria',
            'shortname'=> 'Gestió administrativa de les operacions de compravenda i tresoreria',
            'code' =>  'AIF_MP3_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66
        ]);

        Subject::firstOrCreate([
            'name' => 'Registre comptable i comptes anuals',
            'shortname'=> 'Registre comptable i comptes anuals',
            'code' =>  'AIF_MP3_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 132
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => "Recursos humans i responsabilitat social",
            'name' => "Recursos humans i responsabilitat social",
            'code' =>  'AIF_MP4',
            'number' => 4,
            'study_id' => $study->id,
            'hours' => 264,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Processos administratius de recursos humans',
            'shortname'=> 'Processos administratius de recursos humans',
            'code' =>  'AIF_MP4_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => 'Reclutament i desenvolupament professional',
            'shortname'=> 'Reclutament i desenvolupament professional',
            'code' =>  'AIF_MP4_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Ofimàtica i procés de la informació',
            'name' => 'Ofimàtica i procés de la informació',
            'code' =>  'AIF_MP5',
            'number' => 5,
            'study_id' => $study->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Tecnologia i comunicacions digitals i processament de dades',
            'shortname'=> 'Tecnologia i comunicacions digitals i processament de dades',
            'code' =>  'AIF_MP5_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => 'Tractament avançat, arxiu i presentació de la informació escrita d’aplicacions',
            'shortname'=> 'Tractament avançat, arxiu i presentació de la informació escrita d’aplicacions',
            'code' =>  'AIF_MP5_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66
        ]);

        Subject::firstOrCreate([
            'name' => 'Gestió de bases de dades, disseny de fulls de càlcul i integració d’aplicacions',
            'shortname'=> 'Gestió de bases de dades, disseny de fulls de càlcul i integració d’aplicacions',
            'code' =>  'AIF_MP5_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Anglès tècnic',
            'name' => 'Anglès tècnic',
            'code' =>  'AIF_MP6',
            'number' => 6,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => 'Anglès tècnic',
            'shortname'=> 'Anglès tècnic',
            'code' =>  'AIF_MP6_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 132
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Gestió de recursos humans',
            'name' => 'Gestió de recursos humans',
            'code' =>  'AIF_MP7',
            'number' => 6,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Procés de contractació',
            'shortname'=> 'Procés de contractació',
            'code' =>  'AIF_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => 'Retribucions, nòmines i obligacions oficials',
            'shortname'=> 'Retribucions, nòmines i obligacions oficials',
            'code' =>  'AIF_MP7_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 66
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Gestió financera',
            'name' => 'Gestió financera',
            'code' =>  'AIF_MP8',
            'number' => 8,
            'study_id' => $study->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Anàlisi i previsió financeres',
            'shortname'=> 'Anàlisi i previsió financeres',
            'code' =>  'AIF_MP8_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Productes del mercat financer i d'assegurances",
            'shortname'=> "Productes del mercat financer i d'assegurances",
            'code' =>  'AIF_MP8_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 66
        ]);

        Subject::firstOrCreate([
            'name' => "Fonts de finançament i selecció d'inversions",
            'shortname'=> "Fonts de finançament i selecció d'inversions",
            'code' =>  'AIF_MP8_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 66
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Compabilitat i fiscalitat',
            'name' => 'Compabilitat i fiscalitat',
            'code' =>  'AIF_MP9',
            'number' => 9,
            'study_id' => $study->id,
            'hours' => 198,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Comptabilitat financera, fiscalitat i auditoria',
            'shortname'=> 'Comptabilitat financera, fiscalitat i auditoria',
            'code' =>  'AIF_MP9_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 132
        ]);

        Subject::firstOrCreate([
            'name' => "Comptabilitat de costos",
            'shortname'=> "Comptabilitat de costos",
            'code' =>  'AIF_MP9_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Anàlisi econòmic, patrimonial i financer",
            'shortname'=> "Anàlisi econòmic, patrimonial i financer",
            'code' =>  'AIF_MP9_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Gestió logística i comercial',
            'name' => 'Gestió logística i comercial',
            'code' =>  'AIF_MP10',
            'number' => 10,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Planificació de l’aprovisionament',
            'shortname'=> 'Planificació de l’aprovisionament',
            'code' =>  'AIF_MP10_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Selecció i control de proveïdors",
            'shortname'=> "Selecció i control de proveïdors",
            'code' =>  'AIF_MP10_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33
        ]);

        Subject::firstOrCreate([
            'name' => "Operativa i control de la cadena logística",
            'shortname'=> "Operativa i control de la cadena logística",
            'code' =>  'AIF_MP10_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Simulació empresarial',
            'name' => 'Simulació empresarial',
            'code' =>  'AIF_MP11',
            'number' => 11,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Simulació empresarial',
            'shortname'=> 'Simulació empresarial',
            'code' =>  'AIF_MP11_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 99
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => "Projecte d'administració i finances",
            'name' => "Projecte d'administració i finances",
            'code' =>  'AIF_MP13',
            'number' => 13,
            'study_id' => $study->id,
            'hours' => 33,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => "Projecte d'administració i finances",
            'shortname'=> "Projecte d'administració i finances",
            'code' =>  'AIF_MP13_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => "Formació en centres de treball",
            'name' => "Formació en centres de treball",
            'code' =>  'AIF_MP14',
            'number' => 14,
            'study_id' => $study->id,
            'hours' => 350,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => "Formació en centres de treball",
            'shortname'=> "Formació en centres de treball",
            'code' =>  'AIF_MP14_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 350
        ]);

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Formació i orientació laboral',
            'name' => 'Formació i orientació laboral',
            'code' =>  'AIF_MP12',
            'number' => 6,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => 'Incorporació al treball',
            'shortname'=> 'Incorporació al treball',
            'code' =>  'AIF_MP12_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66
        ]);

        Subject::firstOrCreate([
            'name' => 'Prevenció de riscos laborals',
            'shortname'=> 'Prevenció de riscos laborals',
            'code' =>  'AIF_MP12_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33
        ]);


        // **************************************************
        // * Arts gràfiques
        // **************************************************

        // Disseny i Edició De Publicacions Impreses
        $study = Study::firstOrCreate([
            'name' => 'Disseny i Edició De Publicacions Impreses',
            'shortname' => 'Disseny i Edició De Publicacions Impreses',
            'code' => 'DEP',
        ]);
        $course1 = Course::firstOrCreate([
            'code' => '1DEP',
            'name' => '1r de Disseny i Edició De Publicacions Impreses',
            'order' => 1,
            'study_id' => $study->id
        ]);
        $course2 = Course::firstOrCreate([
            'code' => '2DEP',
            'name' => '2n de Disseny i Edició De Publicacions Impreses',
            'order' => 2,
            'study_id' => $study->id
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Materials de producció gràfica',
            'name' => 'Materials de producció gràfica',
            'code' =>  'DEP_MP1',
            'number' => 1,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Característiques dels materials de producció gràfica',
            'shortname'=> 'Característiques dels materials de producció gràfica',
            'code' =>  'DEP_MP1_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 50,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Tractaments superficials en la indústria gràfica',
            'shortname'=> 'Tractaments superficials en la indústria gràfica',
            'code' =>  'DEP_MP1_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 17,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Aprovisionament i emmagatzematge en la indústria gràfica',
            'shortname'=> 'Aprovisionament i emmagatzematge en la indústria gràfica',
            'code' =>  'DEP_MP1_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 25,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Qualitat dels materials gràfics',
            'shortname'=> 'Qualitat dels materials gràfics',
            'code' =>  'DEP_MP1_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 40,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Organització dels processos de preimpressió digital',
            'name' => 'Organització dels processos de preimpressió digital',
            'code' =>  'DEP_MP2',
            'number' => 2,
            'study_id' => $study->id,
            'hours' => 105,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Planificació dels processos de preimpressió',
            'shortname'=> 'Planificació dels processos de preimpressió',
            'code' =>  'DEP_MP2_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 35,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Control de qualitat en el tractament d’imatges',
            'shortname'=> 'Control de qualitat en el tractament d’imatges',
            'code' =>  'DEP_MP2_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 70,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Control de qualitat de textos i compaginació',
            'shortname'=> 'Control de qualitat de textos i compaginació',
            'code' =>  'DEP_MP2_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 58,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Control de qualitat de la imposició i obtenció de la forma impressora',
            'shortname'=> 'Control de qualitat de la imposició i obtenció de la forma impressora',
            'code' =>  'DEP_MP2_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 45,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Pla de manteniment i prevenció de riscos en preimpressió',
            'shortname'=> 'Pla de manteniment i prevenció de riscos en preimpressió',
            'code' =>  'DEP_MP2_UF5',
            'number' => 5,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 23,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Software gestió processos de preimpressi',
            'shortname'=> 'Software gestió processos de preimpressi',
            'code' =>  'DEP_MP2_UF6',
            'number' => 6,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Disseny de productes gràfics',
            'name' => 'Disseny de productes gràfics',
            'code' =>  'DEP_MP3',
            'number' => 3,
            'study_id' => $study->id,
            'hours' => 105,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Briefing i documentació del projecte gràfic',
            'shortname'=> 'Briefing i documentació del projecte gràfic',
            'code' =>  'DEP_MP3_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Elements del projecte gràfic',
            'shortname'=> 'Elements del projecte gràfic',
            'code' =>  'DEP_MP3_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Creació, desenvolupament digital i anàlisi d’esbossos',
            'shortname'=> 'Creació, desenvolupament digital i anàlisi d’esbossos',
            'code' =>  'DEP_MP3_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 60,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Planificació i valoració de costos del projecte gràfic',
            'shortname'=> 'Planificació i valoració de costos del projecte gràfic',
            'code' =>  'DEP_MP3_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 38,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Realització de maquetes i preparació d’arts finals digitals',
            'shortname'=> 'Realització de maquetes i preparació d’arts finals digitals',
            'code' =>  'DEP_MP3_UF5',
            'number' => 5,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 60,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => "Software gestió D'imatges",
            'shortname'=> "Software gestió D'imatges",
            'code' =>  'DEP_MP3_UF6',
            'number' => 6,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Gestió de la producció en processos d’edició',
            'name' => 'Gestió de la producció en processos d’edició',
            'code' =>  'DEP_MP4',
            'number' => 4,
            'study_id' => $study->id,
            'hours' => 50,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Planificació de la producció en processos d’edició',
            'shortname'=> 'Planificació de la producció en processos d’edició',
            'code' =>  'DEP_MP4_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 25,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Seguiment de la producció en processos d’edició',
            'shortname'=> 'Seguiment de la producció en processos d’edició',
            'code' =>  'DEP_MP4_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 25,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Gestió de la qualitat en processos d’edició',
            'shortname'=> 'Gestió de la qualitat en processos d’edició',
            'code' =>  'DEP_MP4_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Costos de producció en processos d’edició',
            'shortname'=> 'Costos de producció en processos d’edició',
            'code' =>  'DEP_MP4_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 29,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Producció editorial',
            'name' => 'Producció editorial',
            'code' =>  'DEP_MP5',
            'number' => 5,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Gestió i planificació editorial',
            'shortname'=> 'Gestió i planificació editorial',
            'code' =>  'DEP_MP5_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 40,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Costos i pressupostos de productes editorials',
            'shortname'=> 'Costos i pressupostos de productes editorials',
            'code' =>  'DEP_MP5_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Organització de continguts editorials',
            'shortname'=> 'Organització de continguts editorials',
            'code' =>  'DEP_MP5_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 39,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Disseny estructural d’envàs i embalatge',
            'name' => 'Disseny estructural d’envàs i embalatge',
            'code' =>  'DEP_MP6',
            'number' => 6,
            'study_id' => $study->id,
            'hours' => 66,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Desenvolupament del projecte',
            'shortname'=> 'Desenvolupament del projecte',
            'code' =>  'DEP_MP6_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 40,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Representació i realització de maquetes',
            'shortname'=> 'Representació i realització de maquetes',
            'code' =>  'DEP_MP6_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 26,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Software disseny gràfic vectorial i estructural',
            'shortname'=> 'Software disseny gràfic vectorial i estructural',
            'code' =>  'DEP_MP6_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 33,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Disseny i planificació de projectes editorials multimèdia',
            'name' => 'Disseny i planificació de projectes editorials multimèdia',
            'code' =>  'DEP_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 66,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Especificacions i arquitectura dels projectes editorials multimèdia',
            'shortname'=> 'Especificacions i arquitectura dels projectes editorials multimèdia',
            'code' =>  'DEP_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Planificació i estàndards de projectes editorials multimèdia',
            'shortname'=> 'Planificació i estàndards de projectes editorials multimèdia',
            'code' =>  'DEP_MP7_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Esbossos i elements multimèdia de productes editorials',
            'shortname'=> 'Esbossos i elements multimèdia de productes editorials',
            'code' =>  'DEP_MP7_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 26,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Comercialització de productes gràfics i atenció al client',
            'name' => 'Comercialització de productes gràfics i atenció al client',
            'code' =>  'DEP_MP8',
            'number' => 8,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Prototips de productes editorials multimèdia',
            'shortname'=> 'Prototips de productes editorials multimèdia',
            'code' =>  'DEP_MP8_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 99,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Publicació i manteniment de productes editorials multimèdia',
            'shortname'=> 'Publicació i manteniment de productes editorials multimèdia',
            'code' =>  'DEP_MP8_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 60,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Elaboració de manuals de productes editorials multimèdia',
            'shortname'=> 'Elaboració de manuals de productes editorials multimèdia',
            'code' =>  'DEP_MP8_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 72,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Comercialització de productes gràfics i atenció al client',
            'name' => 'Comercialització de productes gràfics i atenció al client',
            'code' =>  'DEP_MP9',
            'number' => 9,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Comunicació i màrqueting en l’empresa gràfica',
            'shortname'=> 'Comunicació i màrqueting en l’empresa gràfica',
            'code' =>  'DEP_MP9_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 29,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Servei d’atenció al client en l’empresa gràfica',
            'shortname'=> 'Servei d’atenció al client en l’empresa gràfica',
            'code' =>  'DEP_MP9_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 25,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Gestió de vendes de productes i serveis gràfics',
            'shortname'=> 'Gestió de vendes de productes i serveis gràfics',
            'code' =>  'DEP_MP9_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 25,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Gestió de reclamacions i servei postvenda en l’empresa gràfica',
            'shortname'=> 'Gestió de reclamacions i servei postvenda en l’empresa gràfica',
            'code' =>  'DEP_MP9_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Formació i orientació laboral',
            'name' => 'Formació i orientació laboral',
            'code' =>  'DEP_MP10',
            'number' => 10,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Incorporació al treball',
            'shortname'=> 'Incorporació al treball',
            'code' =>  'DEP_MP10_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 66,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Prevenció de riscos laborals',
            'shortname'=> 'Prevenció de riscos laborals',
            'code' =>  'DEP_MP10_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 33,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Empresa i iniciativa emprenedora',
            'name' => 'Empresa i iniciativa emprenedora',
            'code' =>  'DEP_MP11',
            'number' => 11,
            'study_id' => $study->id,
            'hours' => 66,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Empresa i iniciativa emprenedora',
            'shortname'=> 'Empresa i iniciativa emprenedora',
            'code' =>  'DEP_MP11_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 66,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Projecte',
            'name' => 'Projecte',
            'code' =>  'DEP_MP12',
            'number' => 12,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Projecte',
            'shortname'=> 'Projecte',
            'code' =>  'DEP_MP12_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 99,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Formació en centres de treball',
            'name' => 'Formació en centres de treball',
            'code' =>  'DEP_MP13',
            'number' => 13,
            'study_id' => $study->id,
            'hours' => 383,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        // Preimpressió digital
        $study = Study::firstOrCreate([
            'name' => 'Preimpressió digital',
            'shortname' => 'Preimpressió digital',
            'code' => 'PRID',
        ]);
        $course1 = Course::firstOrCreate([
            'code' => '1PRID',
            'name' => '1r de Preimpressió digital',
            'order' => 1,
            'study_id' => $study->id
        ]);
        $course2 = Course::firstOrCreate([
            'code' => '2PRID',
            'name' => '2n de Preimpressió digital',
            'order' => 2,
            'study_id' => $study->id
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Tractament de textos',
            'name' => 'Tractament de textos',
            'code' =>  'PRID_MP1',
            'number' => 1,
            'study_id' => $study->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Equips i programari de tractament de textos',
            'shortname'=> 'Equips i programari de tractament de textos',
            'code' =>  'PRID_MP1_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 15,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Digitalització de textos',
            'shortname'=> 'Digitalització de textos',
            'code' =>  'PRID_MP1_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 50,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Tractament digital de textos',
            'shortname'=> 'Tractament digital de textos',
            'code' =>  'PRID_MP1_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 80,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Aplicació de normes de correcció',
            'shortname'=> 'Aplicació de normes de correcció',
            'code' =>  'PRID_MP1_UF4',
            'number' => 4,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Tractaments d’imatges en mapa de bits',
            'name' => 'Tractaments d’imatges en mapa de bits',
            'code' =>  'PRID_MP2',
            'number' => 2,
            'study_id' => $study->id,
            'hours' => 140,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Classificació i preparació d’originals d’imatge',
            'shortname'=> 'Classificació i preparació d’originals d’imatge',
            'code' =>  'PRID_MP2_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 20,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Obtenció d’imatges digitals',
            'shortname'=> 'Obtenció d’imatges digitals',
            'code' =>  'PRID_MP2_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 40,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Ajust dimensional i tonal de les imatges',
            'shortname'=> 'Ajust dimensional i tonal de les imatges',
            'code' =>  'PRID_MP2_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 80,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Imposició i obtenció digital de la forma impressora',
            'name' => 'Imposició i obtenció digital de la forma impressora',
            'code' =>  'PRID_MP3',
            'number' => 3,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Traçat i imposició digital',
            'shortname'=> 'Traçat i imposició digital',
            'code' =>  'PRID_MP3_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 57,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => 'Obtenció digital de formes impressores',
            'shortname'=> 'Obtenció digital de formes impressores',
            'code' =>  'PRID_MP3_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 75,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Impressió digital',
            'name' => 'Impressió digital',
            'code' =>  'PRID_MP4',
            'number' => 4,
            'study_id' => $study->id,
            'hours' => 72,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        Subject::firstOrCreate([
            'name' => 'Tractament de la informació digital',
            'shortname'=> 'Tractament de la informació digital',
            'code' =>  'PRID_MP4_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 25,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => "Preparació de matèries primeres, consumibles i equips d'impressió
digital",
            'shortname'=> "Preparació de matèries primeres, consumibles i equips d'impressió
digital",
            'code' =>  'PRID_MP4_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 47,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        Subject::firstOrCreate([
            'name' => "Impressió, acabats i manteniment preventiu amb dispositius digitals",
            'shortname'=> "Impressió, acabats i manteniment preventiu amb dispositius digitals",
            'code' =>  'PRID_MP4_UF3',
            'number' => 3,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course1->id,
            'hours' => 93,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Compaginació',
            'name' => 'Compaginació',
            'code' =>  'PRID_MP5',
            'number' => 5,
            'study_id' => $study->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Il·lustració vectorial',
            'name' => 'Il·lustració vectorial',
            'code' =>  'PRID_MP6',
            'number' => 6,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Identificació de materials en preimpressió',
            'name' => 'Identificació de materials en preimpressió',
            'code' =>  'PRID_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 132,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Realització de publicacions electròniques',
            'name' => 'Realització de publicacions electròniques',
            'code' =>  'PRID_MP8',
            'number' => 8,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Formació i orientació laboral',
            'name' => 'Formació i orientació laboral',
            'code' =>  'PRID_MP9',
            'number' => 9,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Empresa e iniciativa emprenedora',
            'name' => 'Empresa e iniciativa emprenedora',
            'code' =>  'PRID_MP10',
            'number' => 10,
            'study_id' => $study->id,
            'hours' => 66,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Anglès tècnic',
            'name' => 'Anglès tècnic',
            'code' =>  'PRID_MP11',
            'number' => 11,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Síntesi',
            'name' => 'Síntesi',
            'code' =>  'PRID_MP12',
            'number' => 12,
            'study_id' => $study->id,
            'hours' => 66,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Formació en centres de treball',
            'name' => 'Formació en centres de treball',
            'code' =>  'PRID_MP13',
            'number' => 13,
            'study_id' => $study->id,
            'hours' => 350,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

    }
}

if (!function_exists('create_sample_position')) {
    function create_sample_position()
    {
        return Position::firstOrCreate([
            'code' => 'TICTAC',
            'name' => 'Coordinador TIC/TAC',
            'shortname' => 'Coord. TIC'
        ]);
    }
}

if (!function_exists('create_sample_course')) {
    function create_sample_course()
    {
        $study = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
        ]);

        return Course::firstOrCreate([
            'code' => '2DAM',
            'name' => 'Segon Curs Desenvolupament Aplicacions Multiplataforma',
            'order' => 2,
            'study_id' => $study->id
        ]);
    }
}

if (!function_exists('create_sample_subject_group')) {
    function create_sample_subject_group()
    {
        $study = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
        ]);
        return SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'description' => 'Bla bla bla',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01',
            'subjects_number' => 3
        ]);
    }
}


if (!function_exists('create_sample_subject')) {
    function create_sample_subject()
    {
        $mp_start_date = '2017-09-15';
        $mp_end_date = '2018-06-01';

        $study = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
        ]);

        //        Mòdul professional (Subject Group) 7: desenvolupament d’interfícies
        $group = SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        $course2 = Course::firstOrCreate([
            'code' => '2DAM',
            'name' => 'Segon Curs Desenvolupament Aplicacions Multiplataforma',
            'order' => 2,
            'study_id' => $study->id
        ]);

        return Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'course_id' => $course2->id,
            'hours' => 79,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

    }
}

if (!function_exists('initialize_fake_subjectGroups')) {
    function initialize_fake_subjectGroups()
    {
        initialize_subject_group_tags();
        $depInformatica = Department::create([
            'name' => 'Departament Informàtica',
            'shortname' => 'Informàtica',
            'code' => 'INFORMÀTICA',
            'order' => 1
        ]);

        $dam = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM',
        ]);
        $dam->assignDepartment($depInformatica);

        $familyInformatica = $informatica = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);
        $dam->assignFamily($familyInformatica);

        SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $dam->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        $gad = Study::firstOrCreate([
            'name' => 'Gestió administrativa',
            'shortname' => 'Gestió administrativa',
            'code' => 'GAD',
        ]);

        SubjectGroup::firstOrCreate([
            'shortname' => 'Operacions de compravenda',
            'name' => 'Operacions de compravenda',
            'code' =>  'GAD_MP2',
            'number' => 2,
            'study_id' => $gad->id,
            'hours' => 165,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        SubjectGroup::firstOrCreate([
            'shortname' => 'Operacions de recursos humans',
            'name' => 'Operacions de recursos humans',
            'code' =>  'GAD_MP3',
            'number' => 3,
            'study_id' => $gad->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 0,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);
    }
}

if (!function_exists('initialize_fake_subjects')) {
    function initialize_fake_subjects()
    {
        $mp_start_date = '2017-09-15';
        $mp_end_date = '2018-06-01';

        $dam = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM',
        ]);

        $depInformatica = Department::create([
            'name' => 'Departament Informàtica',
            'shortname' => 'Informàtica',
            'code' => 'INFORMÀTICA',
            'order' => 1
        ]);
        $dam->assignDepartment($depInformatica);

        $familyInformatica = $informatica = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);
        $dam->assignFamily($familyInformatica);

        // No existeix 1DAM -> és comú amb 1rASIX
        $course2 = Course::firstOrCreate([
            'code' => '2DAM',
            'name' => 'Segon Curs Desenvolupament Aplicacions Multiplataforma',
            'order' => 2,
            'study_id' => $dam->id
        ]);

//        Mòdul professional (Subject Group) 7: desenvolupament d’interfícies
        $group = SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $dam->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
        ]);

        Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $dam->id,
            'course_id' => $course2->id,
            'hours' => 79,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        Subject::firstOrCreate([
            'name' => 'Preparació i distribució d’aplicacions',
            'shortname'=> 'Preparació i distribució d’aplicacions',
            'code' =>  'DAM_MP7_UF2',
            'number' => 2,
            'subject_group_id' => $group->id,
            'study_id' => $dam->id,
            'course_id' => $course2->id,
            'hours' => 20
        ]);
    }
}

if (!function_exists('initialize_fake_week_lessons')) {
    /**
     * Week lesson sempre s'omple amb la info de GPuntis però per fer proves va bé aquest helper
     */
    function initialize_fake_week_lessons()
    {
        WeekLesson::create([
            'code' => 'DAM_MP7_3_2100_2200',
            'day' => 3, // Dimecres
            'start' => '21:00:00',
            'end' => '22:00:00',
//            'job_id' => 1 és potencial no assignem encara a cap plaça/profe
            'subject_group_id' =>  SubjectGroup::findByCode('DAM_MP7')->id,
//            'classroom_id' => TODO
//            'location_id' => // No necessari, encara no tinc locations
        ]);

        WeekLesson::create([
            'code' => 'DAM_MP7_5_1530_1730',
            'day' => 5, // divendres
            'start' => '15:30:00',
            'end' => '17:30:00',
            'subject_group_id' =>  SubjectGroup::findByCode('DAM_MP7')->id,
        ]);
    }
}

if (!function_exists('dayOfWeek')) {
    function dayOfWeek($isoNumber) {
        switch ($isoNumber) {
            case 1:
                return 'monday';
            case 2:
                return 'tuesday';
            case 3:
                return 'wednesday';
            case 4:
                return 'thursday';
            case 5:
                return 'friday';
            case 6:
                return 'saturday';
            case 7:
                return 'sunday';
        }
    }
}

if (!function_exists('check_lesson')) {
    function check_lesson($lesson)
    {
//        dd(json_encode($lesson));
        return array_key_exists('id', $lesson) &&
            array_key_exists('subject_id', $lesson) &&
            array_key_exists('job_id', $lesson) &&
            array_key_exists('classroom_id', $lesson) &&
            array_key_exists('start', $lesson) &&
            array_key_exists('end', $lesson) &&
            array_key_exists('created_at', $lesson) &&
            array_key_exists('updated_at', $lesson);
    }
}

if (!function_exists('initialize_fake_lessons')) {
    function initialize_fake_lessons()
    {
        Lesson::firstOrCreate([
            'subject_id' => 1,
            'job_id' => 1,
            'classroom_id' => 1,
            'start' => new Carbon('2018-09-20 19:00:00'),
            'end' => new Carbon('2018-09-20 20:00:00')
        ]);

        Lesson::firstOrCreate([
            'subject_id' => 1,
            'job_id' => 1,
            'classroom_id' => 1,
            'start' => new Carbon('2018-09-21 17:00:00'),
            'end' => new Carbon('2018-09-21 18:00:00')
        ]);

        Lesson::firstOrCreate([
            'subject_id' => 1,
            'job_id' => 1,
            'classroom_id' => 1,
            'start' => new Carbon('2018-09-19 17:00:00'),
            'end' => new Carbon('2018-09-19 18:00:00')
        ]);
    }
}

if (!function_exists('initialize_dnis')) {
    /**
     * Initialize dnis
     */
    function initialize_dnis($debug = false) {
        $dnis = collect(File::allFiles(base_path('storage/dni')));
        foreach ($dnis as $dni) {
            $name= $dni->getRelativePathName();
            $dniStr = substr($name,0,9);
            $person = Person::findByIdentifier($dniStr);
            if (!$person) {
                if($debug) dump('No person found with DNI: ' . $dniStr);
            }
            else {
                if($debug) dump( 'Adding DNI to ' . $person->name . ' ...');
                $person->copyMedia($dni->getPathname())->toMediaCollection('dnis');
            }
        }
    }
}

if (!function_exists('nospaces')) {
    function nospaces($string) {
        return preg_replace('/\s+/', '', $string);
    }
}



if (!function_exists('propose_user_name')) {
    /**
     * Initialize dnis
     */
    function propose_user_name($name, $sn1)
    {
        return mb_strimwidth(trim(str_slug(nospaces($name))),0,10,'') .
            mb_strimwidth(trim(str_slug(nospaces($sn1))),0,10,'');
    }
}

if (!function_exists('google_groups_check_users')) {
    function google_groups_check_users($users)
    {
        return is_array($users);
    }
}

if (!function_exists('google_groups_check_members')) {
    function google_groups_check_members($members)
    {
        return get_class($members) === 'Google_Service_Directory_Members' &&
               is_array($members->members) &&
               get_class($members->members[0]) === 'Google_Service_Directory_Member';
    }
}

if (!function_exists('google_groups_check_member')) {
    function google_groups_check_member($member)
    {
        return get_class($member) === 'stdClass' &&
            property_exists($member,'email') &&
            property_exists($member,'id') &&
            property_exists($member,'etag') &&
            property_exists($member,'role') &&
            property_exists($member,'type') &&
            property_exists($member,'status');
    }
}

if (!function_exists('get_current_git_commit')) {
    /**
     * https://gist.github.com/stevegrunwell/3363975
     *
     * Get the hash of the current git HEAD
     * @param str $branch The git branch to check
     * @return mixed Either the hash or a boolean false
     */
    function get_current_git_commit($branch = 'master')
    {
        if ($hash = file_get_contents(sprintf('.git/refs/heads/%s', $branch))) {
            return $hash;
        } else {
            return false;
        }
    }
}

if (! function_exists('is_sha1')) {
    function is_sha1($str) {
        return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
    }
}


if (! function_exists('create_fake_incidents')) {
    function create_fake_incidents() {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]);
        $user2 = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        Incident::create([
            'subject' => 'No funciona PC1 aula 5',
            'description' => 'bla bla bla'
        ])->assignUser($user);
        Incident::create([
            'subject' => 'No funciona PC2 aula 25',
            'description' => 'hey hey hey'
        ])->assignUser($user);
        Incident::create([
            'subject' => 'No funciona projecte Sala Mestral',
            'description' => 'jorl jorl jorl'
        ])->assignUser($user2);
    }
}

if (! function_exists('create_setting')) {
    function create_setting($key, $value, $role,$label=null,$hint=null)
    {
        try {
            $setting = new \App\Models\Setting();
            $setting->key = $key;
            $setting->value = $value;
            $setting->role = $role;
            $setting->label = $label;
            $setting->hint = $hint;
            $setting->save();
        } catch (\Exception $e) {

        }

    }
}

if (! function_exists('initialize_settings')) {
    function initialize_settings()
    {
        create_setting(
            'incidents_manager_email',
            'incidencies@iesebre.com',
            'IncidentsManager',
            'Email del/s gestor/s de les incidències',
            'Podeu utilitzar una adreça de correu electrònic personal o d\'un grup de gestors'
            );
        create_setting(
            'positions_manager_email',
            'positions@iesebre.com',
            'PositionsManager',
            'Email del/s gestor/s dels càrrecs',
            'Podeu utilitzar una adreça de correu electrònic personal o d\'un grup de gestors'
        );
    }
}

if (! function_exists('tenant_from_url')) {
    function tenant_from_url($url)
    {
        $host = parse_url($url)['host'];
        $domain = config('app.domain','scool.test');
        if (!ends_with($host, $domain)) return null;
        if ($host === $domain) return null;
        return (explode('.' . $domain, $host)[0]);
    }
}

if (! function_exists('tenant_from_current_url')) {
    function tenant_from_current_url()
    {
        return tenant_from_url(url()->current());
    }
}

if (! function_exists('ellipsis')) {
    function ellipsis($text,$max=50)
    {
        $ellipted = strlen($text) > $max ? substr($text,0,$max)."..." : $text;
        return $ellipted;
    }
}

if (! function_exists('map_collection')) {
    function map_collection($collection)
    {
        return $collection->map(function($item) {
            return $item->map();
        });
    }
}

if (! function_exists('map_simple_collection')) {
    function map_simple_collection($collection)
    {
        return $collection->map(function($item) {
            return $item->mapSimple();
        });
    }
}

if (! function_exists('initialize_incidents_module')) {
    function initialize_incidents_module()
    {
        initialize_incident_tags();
    }
}

if (! function_exists('initialize_incident_tags')) {
    function initialize_incident_tags()
    {
        IncidentTag::firstOrCreate([
            'value' => 'Manteniment centre',
            'description' => 'Persianes, florescents, electricitat, etc...',
            'color' => 'amber',
        ]);

        IncidentTag::firstOrCreate([
            'value' => 'Maninfo',
            'description' => 'Manteniment Informàtica: problemes maquinari, programari, connexió a internet, aplicacions pròpies',
            'color' => 'teal lighten-1',
        ]);

        IncidentTag::firstOrCreate([
            'value' => 'Dep. Informàtica',
            'description' => 'Incidències aules i departament informàtica',
            'color' => 'cyan lighten-1',
        ]);

        IncidentTag::firstOrCreate([
            'value' => 'Moodle i web del centre',
            'description' => 'Gestió de Moodle i web del centre',
            'color' => 'pink lighten-1',
        ]);
    }
}

if (! function_exists('sample_logs')) {
    function sample_logs()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $incident = Incident::create([
            'subject' => 'No va res Aula 12',
            'description' => 'Bla bla bla'
        ]);
        $incident->assignUser($user1);

        $log1 = Log::create([
            'text' => 'Ha creat la incidència TODO_LINK_INCIDENCIA',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'loggable_id' => $incident->id,
            'loggable_type' => Incident::class,
            'user_id' => $user1->id,
            'icon' => 'home',
            'color' => 'teal'
        ]);
        $log2 = Log::create([
            'text' => 'Ha modificat la incidència TODO_LINK_INCIDENCIA',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'loggable_id' => 1,
            'loggable_type' => Incident::class,
            'user_id' => $user2->id,
            'icon' => 'home',
            'color' => 'teal'
        ]);
        $log3 = Log::create([
            'text' => 'Ha modificat la incidència TODO_LINK_INCIDENCIA',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'loggable_id' => 1,
            'loggable_type' => Incident::class,
            'user_id' => $user2->id,
            'icon' => 'home',
            'color' => 'teal'
        ]);
        $log4 = Log::create([
            'text' => 'BLA BLA BLA',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'OtherModule',
            'loggable_id' => 1,
            'loggable_type' => User::class,
            'user_id' => $user2->id,
            'icon' => 'home',
            'color' => 'teal'
        ]);
        return [$log1,$log2,$log3,$log4];
    }
}

if (! function_exists('sample_moodle_user_array')) {
    function sample_moodle_user_array($username = null) {
        $username = $username ? $username : 'pepepardo@iesebre.com';
        return $user = (object) [
            'id' => 10,
            'username' => $username,
            'firstname' => 'Pepe',
            'lastname' => 'Pardo Jeans',
            'fullname' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'department' => '',
            'institution' => "Institut de l'Ebre",
            'idnumber' => '131',
            'firstaccess' => 1381338817,
            'lastaccess' => 1401207111,
            'auth' => 'nologin',
            'suspended' => false,
            'confirmed' => true,
            'lang' => 'ca',
            'theme' => '',
            'timezone' => '99',
            'mailformat' => 1,
            'city' => 'La Senia',
            'profileimageurlsmall' => 'https://secure.gravatar.com/avatar/46440f886cfe06f9193812d8905d2e79?s=35&d=mm'
        ];
    }
}

if (! function_exists('sample_ldap_user_array')) {
    function sample_ldap_user_array($employeeNumber = null, $email = null, $uid = null) {
        $employeeNumber = $employeeNumber ? $employeeNumber : 689;
        $email = $email ? $email : 'marimar1@iesebre.com';
        $uid = $uid ? $uid : 'marimar1';

        return $user = (object) [
            'objectClass' => [
                "top",
                "gosaAccount",
                "highSchoolUser",
                "person",
                "inetOrgPerson",
                "organizationalPerson",
                "extensibleObject",
                "irisPerson",
                "posixAccount",
                "shadowAccount",
                "sambaSamAccount"
            ],
            'base_dn' => 'ou=All,dc=iesebre,dc=com',
            'rdn' => 'cn=Mar Imar,ou=people,ou=Sanitat,ou=Profes',
            'cn' => 'Mar Imar',
            'dn' => 'cn=Mar Ferrero,ou=people,ou=Sanitat,ou=Profes,ou=All,dc=iesebre,dc=com',
            'uid' => $uid,
            'userpassword' => "{MD5}jB5Dpewr34fFFHCDSnrvOuOg==",
            'passwordtype' => 'MD5',
            'uidnumber' => '4618',
            'gidnumber' => '513',
            'homedirectory' => '/samba/homes/marferrero',
            'sambasid' => 'S-1-5-21-4045161930-1404234508-34347741366-10236',
            'sambarid' => '10236','givenname' => 'Mar',
            'sn' => 'Imar ',
            'sn1' => 'Imar',
            'sn2' => 'Forcada',
            'irispersonaluniqueid' => '48293270D',
            'highschooluserid' => '201112-1047',
            'highschoolpersonalemail' => 'marimar1@hotmail.com',
            'email' => $email,
            'employeetype' => 'profe',
            'employeenumber' => $employeeNumber,
            'l' => 'Ontinyent',
            'st' => 'València',
            'telephonenumber' => '977500949',
            'mobile' => '671244770',
            'postalCode' => '46870',
            'createtimestamp' => '20110907092601Z',
            'creatorsName' => 'cn=admin,dc=iesebre,dc=com',
            'creatorsNameRDN' => 'cn=admin',
            'modifiersName' => 'cn=admin,dc=iesebre,dc=com',
            'modifiersNameRDN' => 'cn=admin',
            'modifyTimestamp' => '20140612103427Z',
            'modifyFormatted' => '10 => 34 => 27 12-06-2014',
            'modifyHuman' => '4 anys abans',
            'createTimestamp' => '20110907092601Z',
            'createFormatted' => '09 => 26 => 01 07-09-2011',
            'createHuman' => '7 anys abans',
            'jpegphoto' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/7Sl89GhvdG9zaG9wIDMuMAA4QklNBAQAAAAAAEscAVoAAxslRxwBWgADGyVHHAIAAAIAABwCUAASSW5zdGl0dXQgZGUgbCdFYnJlHAI3AAgyMDExMDkwMhwCPAALMTMyMTMxKzAwMDAAOEJJTQQlAAAAAAAQBCEqzWf59ydsJ0mVHH+S0jhCSU0EOgAAAAAAkwAAABAAAAABAAAAAAALcHJpbnRPdXRwdXQAAAAFAAAAAENsclNlbnVtAAAAAENsclMAAAAAUkdCQwAAAABJbnRlZW51bQAAAABJbnRlAAAAAENscm0AAAAATXBCbGJvb2wBAAAAD3ByaW50U2l4dGVlbkJpdGJvb2wAAAAAC3ByaW50ZXJOYW1lVEVYVAAAAAEAAAA4QklNBDsAAAAAAbIAAAAQAAAAAQAAAAAAEnByaW50T3V0cHV0T3B0aW9ucwAAABIAAAAAQ3B0bmJvb2wAAAAAAENsYnJib29sAAAAAABSZ3NNYm9vbAAAAAAAQ3JuQ2Jvb2wAAAAAAENudENib29sAAAAAABMYmxzYm9vbAAAAAAATmd0dmJvb2wAAAAAAEVtbERib29sAAAAAABJbnRyYm9vbAAAAAAAQmNrZ09iamMAAAABAAAAAAAAUkdCQwAAAAMAAAAAUmQgIGRvdWJAb+AAAAAAAAAAAABHcm4gZG91YkBv4AAAAAAAAAAAAEJsICBkb3ViQG/gAAAAAAAAAAAAQnJkVFVudEYjUmx0AAAAAAAAAAAAAAAAQmxkIFVudEYjUmx0AAAAAAAAAAAAAAAAUnNsdFVudEYjUHhsQFIAAAAAAAAAAAAKdmVjdG9yRGF0YWJvb2wBAAAAAFBnUHNlbnVtAAAAAFBnUHMAAAAAUGdQQwAAAABMZWZ0VW50RiNSbHQAAAAAAAAAAAAAAABUb3AgVW50RiNSbHQAAAAAAAAAAAAAAABTY2wgVW50RiNQcmNAWQAAAAAAADhCSU0D7QAAAAAAEABIAAAAAQACAEgAAAABAAI4QklNBCYAAAAAAA4AAAAAAAAAAAAAP4AAADhCSU0D8gAAAAAACgAA////////AAA4QklNBA0AAAAAAAQAAAAeOEJJTQQZAAAAAAAEAAAAHjhCSU0D8wAAAAAACQAAAAAAAAAAAQA4QklNJxAAAAAAAAoAAQAAAAAAAAACOEJJTQP1AAAAAABIAC9mZgABAGxmZgAGAAAAAAABAC9mZgABAKGZmgAGAAAAAAABADIAAAABAFoAAAAGAAAAAAABADUAAAABAC0AAAAGAAAAAAABOEJJTQP4AAAAAABwAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAADhCSU0ECAAAAAAAEAAAAAEAAAJAAAACQAAAAAA4QklNBB4AAAAAAAQAAAAAOEJJTQQaAAAAAANhAAAABgAAAAAAAAAAAAABPAAAAPoAAAAWADgAOQAgAE0AYQByACAARgBlAHIAcgBlAHIAbwAgAEYAbwByAGMAYQBkAGEAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAPoAAAE8AAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAEAAAAAAABudWxsAAAAAgAAAAZib3VuZHNPYmpjAAAAAQAAAAAAAFJjdDEAAAAEAAAAAFRvcCBsb25nAAAAAAAAAABMZWZ0bG9uZwAAAAAAAAAAQnRvbWxvbmcAAAE8AAAAAFJnaHRsb25nAAAA+gAAAAZzbGljZXNWbExzAAAAAU9iamMAAAABAAAAAAAFc2xpY2UAAAASAAAAB3NsaWNlSURsb25nAAAAAAAAAAdncm91cElEbG9uZwAAAAAAAAAGb3JpZ2luZW51bQAAAAxFU2xpY2VPcmlnaW4AAAANYXV0b0dlbmVyYXRlZAAAAABUeXBlZW51bQAAAApFU2xpY2VUeXBlAAAAAEltZyAAAAAGYm91bmRzT2JqYwAAAAEAAAAAAABSY3QxAAAABAAAAABUb3AgbG9uZwAAAAAAAAAATGVmdGxvbmcAAAAAAAAAAEJ0b21sb25nAAABPAAAAABSZ2h0bG9uZwAAAPoAAAADdXJsVEVYVAAAAAEAAAAAAABudWxsVEVYVAAAAAEAAAAAAABNc2dlVEVYVAAAAAEAAAAAAAZhbHRUYWdURVhUAAAAAQAAAAAADmNlbGxUZXh0SXNIVE1MYm9vbAEAAAAIY2VsbFRleHRU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NzAuMC0zMDAuMCBtbSIgYXV4OkltYWdlTnVtYmVyPSIxNTUiIGF1eDpGbGFzaENvbXBlbnNhdGlvbj0iMC8xIiBhdXg6T3duZXJOYW1lPSJJbnN0aXR1dCBkZSBsJ0VicmUiIGF1eDpGaXJtd2FyZT0iMS4wLjIiIHhtcDpNb2RpZnlEYXRlPSIyMDExLTA5LTA4VDE3OjUxOjE4KzAyOjAwIiB4bXA6Q3JlYXRlRGF0ZT0iMjAxMS0wOS0wMlQxMzoyMTozMSIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAxMS0wOS0wOFQxNzo1MToxOCswMjowMCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1LjEgV2luZG93cyIgcGhvdG9zaG9wOkRhdGVDcmVhdGVkPSIyMDExLTA5LTAyVDEzOjIxOjMxIiBwaG90b3Nob3A6TGVnYWN5SVBUQ0RpZ2VzdD0iOEY4OEU5MkQ5QzUzRTc5Qzk5MEVCNUQxQjA1NzlBRkIiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJBZG9iZSBSR0IgKDE5OTgpIiB4bXBNTTpEb2N1bWVudElEPSI5RkUzNzlDNjc2RkM3NTJFNUZCQkE1OTA3N0IxRUU4QiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2MzY1NTMzNjJDREFFMDExQTU3NkZGM0U2OUIzOTc3RCIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSI5RkUzNzlDNjc2RkM3NTJFNUZCQkE1OTA3N0IxRUU4QiI+IDxkYzpjcmVhdG9yPiA8cmRmOlNlcT4gPHJkZjpsaT5JbnN0aXR1dCBkZSBsJ0VicmU8L3JkZjpsaT4gPC9yZGY6U2VxPiA8L2RjOmNyZWF0b3I+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249InNhdmVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjI1Mjk5ODZBMkJEQUUwMTFBNTc2RkYzRTY5QjM5NzdEIiBzdEV2dDp3aGVuPSIyMDExLTA5LTA4VDE3OjAyOjM2KzAyOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ1M1LjEgV2luZG93cyIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6NjM2NTUzMzYyQ0RBRTAxMUE1NzZGRjNFNjlCMzk3N0QiIHN0RXZ0OndoZW49IjIwMTEtMDktMDhUMTc6NTE6MTgrMDI6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDUzUuMSBXaW5kb3dzIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDwvcmRmOlNlcT4gPC94bXBNTTpIaXN0b3J5PiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8P3hwYWNrZXQgZW5kPSJ3Ij8+/9sAQwADAgICAgIDAgICAwMDAwQGBAQEBAQIBgYFBgkICgoJCAkJCgwPDAoLDgsJCQ0RDQ4PEBAREAoMEhMSEBMPEBAQ/9sAQwEDAwMEAwQIBAQIEAsJCxAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQ/8AAEQgAuQCTAwERAAIRAQMRAf/EAB4AAAEEAwEBAQAAAAAAAAAAAAAEBQYHAQMICQIK/8QASBAAAgEDAwIDBQMGCwcEAwAAAQIDBAURAAYSEyEHMUEIFCJRYSMycQkVQoGRoRYkMzZSYnKxs8HwNHN0dbK00Rc1N+GDwvH/xAAbAQABBQEBAAAAAAAAAAAAAAAAAQIDBAUGB//EADkRAAICAQMDAQYEBQMDBQAAAAAB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'
        ];
    }
}

if (! function_exists('sample_google_user_array')) {
    function sample_google_user_array($id = null, $employeeId = null, $personalEmail= null, $primaryEmail= null) {
        $id = $id ? $id : '113829626411274751363';
        $employeeId = $employeeId ? $employeeId : 689;
        $personalEmail = $personalEmail ? $personalEmail : 'personal@email.com';
        $primaryEmail = $primaryEmail ? $primaryEmail : 'usuari@iesebre.com';
        return $user = (object) [
            'firstname' => 'Pepe',
            'creationTime' => '2015-09-22T15:31:45.000Z',
            'employeeId' => $employeeId,
            'familyName' => 'Cuadrada Canillo',
            'fullName' => 'Aaron Cuadrada Canillo',
            'givenName' => 'Aaron',
            'id' => $id,
            'isAdmin' => false,
            'json' => "",
            'lastLoginTime' => '2018-10-09T15:39:19.000Z',
            'mobile' => null,
            'orgUnitPath' => '/All/Alumnes',
            'organizations' => null,
            'personalEmail' => $personalEmail,
            'primaryEmail' => $primaryEmail,
            'suspended' => false,
            'suspensionReason' => null,
            'thumbnailPhotoUrl' => null
        ];
    }
}

if (! function_exists('create_sample_moodle_user')) {
    function create_sample_moodle_user( $data = [
        'username' => 'usuariesborrar',
        'firstname' => 'usuari',
        'lastname' => 'esborrar',
        'email' => 'usuariesborrar@gmail.com',
        'password' => '123456'
    ]) {
        try {
            return MoodleUser::store($data);
        } catch(\Exception $e) {
            return MoodleUser::get('usuariesborrar@gmail.com');
        }
    }
}

if (! function_exists('git')) {
    function git() {
        return Cache::remember('git_info', 5, function () {
            Carbon::setLocale(config('app.locale'));
            return collect([
                'branch' => git_current_branch(),
                'commit' => git_current_commit(),
                'commit_short' => git_current_commit(true),
                'full_info' => git_current_commit_full_info(),
                'author_name' => git_current_commit_author_name(),
                'author_email' => git_current_commit_author_email(),
                'message' => git_current_commit_message(),
                'timestamp' => $timestamp = git_current_commit_timestamp(),
                'date' => $carbonDate = Carbon::createFromTimestamp($timestamp),
                'date_human_original' => git_current_commit_date_human(),
                'date_human' => $carbonDate->diffForHumans(Carbon::now()),
                'date_formatted' => $carbonDate->format('h:i:sA d-m-Y'),
                'origin' => git_remote_origin_url()
            ]);
        });
    }
}

if (! function_exists('git_current_branch')) {
    function git_current_branch() {
        exec('git name-rev --name-only HEAD', $output);
        return $output[0];
    }
}

if (! function_exists('git_current_commit')) {
    function git_current_commit($short = false) {
        $short ? exec('git rev-parse --short HEAD', $output) : exec('git rev-parse HEAD', $output) ;
        return $output[0];
    }
}

if (! function_exists('git_current_commit_full_info')) {
    function git_current_commit_full_info() {
        exec('git log -1', $output);
        return $output;
    }
}

if (! function_exists('git_current_commit_author_name')) {
    function git_current_commit_author_name() {
        exec("git log -1 --pretty=format:'%an'", $output);
        return $output[0];
    }
}

if (! function_exists('git_current_commit_author_email')) {
    function git_current_commit_author_email() {
        exec("git log -1 --pretty=format:'%ae'", $output);
        return $output[0];
    }
}

if (! function_exists('git_current_commit_message')) {
    function git_current_commit_message()
    {
        exec("git log -1 --pretty=format:'%s'", $output);
        return $output[0];
    }
}

if (! function_exists('git_current_commit_timestamp')) {
    function git_current_commit_timestamp()
    {
        exec("git log -1 --pretty=format:'%at'", $output);
        return $output[0];
    }
}

if (! function_exists('git_current_commit_date')) {
    function git_current_commit_date()
    {
        exec("git log -1 --pretty=format:'%ad'", $output);
        return $output[0];
    }
}

if (! function_exists('git_current_commit_date_human')) {
    function git_current_commit_date_human()
    {
        exec("git log -1 --pretty=format:'%ar'", $output);
        return $output[0];
    }
}

if (! function_exists('git_remote_origin_url')) {
    function git_remote_origin_url()
    {
        exec("git config --get remote.origin.url", $output);
        return $output[0];
    }
}

if (! function_exists('get_pusher_apps_from_tenants')) {
    function get_pusher_apps_from_tenants()
    {
        $tenants = Tenant::all();
        return $tenants->map(function($tenant) {
           return [
               'id' => "$tenant->pusher_app_id",
               'name' => $tenant->name,
               'key' => $tenant->pusher_app_key,
               'secret' => $tenant->pusher_app_secret,
               'enable_client_messages' => $tenant->pusher_enable_client_messages ? true : false,
               'enable_statistics' => $tenant->pusher_enable_client_messages ? true : false,
           ] ;
        })->toArray();
    }
}

if (! function_exists('public_env')) {
    function public_env()
    {
        return collect([
            'app_env' => app()->environment()
        ]);
    }
}


/**
 * ************************************* CURRICULUM ****************************************
 */

if (! function_exists('create_sample_studies')) {
    function create_sample_studies() {
        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $informatica = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);
        $dam->assignFamily($informatica);
        $depInformatica = Department::create([
            'name' => 'Departament Informàtica',
            'shortname' => 'Informàtica',
            'code' => 'INFORMÀTICA',
            'order' => 1
        ]);
        $dam->assignDepartment($depInformatica);
        $loe = StudyTag::create([
            'value' => 'LOE',
            'description' => 'Ley Orgànica de Educación'
        ]);
        $dam->assignTag($loe);
        $fp = StudyTag::create([
            'value' => 'FP',
            'description' => 'Formació Professional'
        ]);
        $dam->assignTag($fp);

        $asix = Study::create([
            'name' => 'Administració Sistemes Informàtics i Xarxes',
            'shortname' => 'Adm. Sistemes i Xarxes',
            'code' => 'ASIX'
        ]);
        $asix->assignFamily($informatica);
        $asix->assignDepartment($depInformatica);
        $asix->assignTag($loe);
        $asix->assignTag($fp);

        $smx = Study::create([
            'name' => 'Sistemes Microinformàtics i Xarxes',
            'shortname' => 'Sistemes',
            'code' => 'SMX'
        ]);
        $smx->assignFamily($informatica);
        $smx->assignDepartment($depInformatica);
        $smx->assignTag($loe);
        $smx->assignTag($fp);

        $smx = Study::create([
            'name' => 'Farmaci bla bla bla',
            'shortname' => 'Farmacia',
            'code' => 'FAR'
        ]);
        $depsanitat = Department::create([
            'name' => 'Departament Sanitat',
            'shortname' => 'Sanitat',
            'code' => 'SANITAT',
            'order' => 2
        ]);
        $sanitat = Family::create([
            'name' => 'Sanitat',
            'code' => 'Sanitat',
        ]);
        $smx->assignFamily($sanitat);
        $smx->assignDepartment($depsanitat);
        $smx->assignTag($loe);
        $smx->assignTag($fp);

        return collect([$dam,$asix,$smx]);
    }
}

if (! function_exists('create_sample_study')) {
    function create_sample_study()
    {
        $department = Department::create([
            'name' => "Departament d'Informàtica",
            'shortname' => 'Informàtica',
            'code' => 'INF',
            'order' => 1
        ]);

        $family = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF',
        ]);

        return Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
            'department_id' => $department->id,
            'family_id' => $family->id
        ]);
    }
}

if (! function_exists('initialize_curriculum_module')) {
    function initialize_curriculum_module()
    {
        initialize_studies();
        initialize_subject_group_tags();
    }
}

if (! function_exists('initialize_studies')) {
    function initialize_studies()
    {
        initialize_studies_tags();
    }
}

if (! function_exists('initialize_studies_tags')) {
    function initialize_studies_tags()
    {
        StudyTag::firstOrCreate([
            'value' => 'LOE',
            'description' => "Lley Orgànica d'Educació",
            'color' => 'amber darken-2',
        ]);

        StudyTag::firstOrCreate([
            'value' => 'LOGSE',
            'description' => "Lley Orgànica General del Sistema Educatiu",
            'color' => 'teal lighten-1',
        ]);

        StudyTag::firstOrCreate([
            'value' => 'FP',
            'description' => 'Formació Professional',
            'color' => 'pink lighten-1',
        ]);

        StudyTag::firstOrCreate([
            'value' => 'CFGS',
            'description' => 'Cicle Formatiu de Grau Superior',
            'color' => 'orange lighten-2',
        ]);

        StudyTag::firstOrCreate([
            'value' => 'CFGM',
            'description' => 'Cicle Formatiu de Grau Mitjà',
            'color' => 'green lighten-2',
        ]);

        StudyTag::firstOrCreate([
            'value' => 'Curs Accés',
            'description' => "Cursos d'accés",
            'color' => 'cyan lighten-1',
        ]);
    }
}

if (! function_exists('set_sample_notifications_to_user')) {
    function set_sample_notifications_to_user($user) {
        $user->notify(new SampleNotification('Notification 1'));
        $user->notify(new SampleNotification('Notification 2'));
        $user->notify(new SampleNotification('Notification 3'));
    }
}

if (! function_exists('sample_notifications')) {
    function sample_notifications() {
        $user1 = factory(User::class)->create([
            'name' => 'Homer Simpson',
            'email' => 'homer@lossimpsons.com'
        ]);
        $user2 = factory(User::class)->create([
            'name' => 'Bart Simpson',
            'email' => 'bart@lossimpsons.com'
        ]);
        $user1->notify(new SampleNotification('Sample Notification 1'));
        $user2->notify(new SampleNotification('Sample Notification 2'));
    }
}

if (! function_exists('create_sample_person')) {
    function create_sample_person()
    {
        $user = factory(User::class)->create([
            'email' => 'pepepardojeans@gmail.com',
            'name' => 'Pepe Pardo Jeans',
            'email_verified_at' => Carbon::now(),
            'last_login' => Carbon::now(),
            'last_login_ip' => '127.0.0.1'
        ]);
        $googleUser = GoogleUser::create([
            'google_id' => '89778458778446589798',
            'google_email' => 'pepepardo@iesebre.com'
        ]);
        $user->assignGoogleUser($googleUser);
        seed_identifier_types();
        $identifier = Identifier::create([
            'value' => '14868003K',
            'type_id' => 1
        ]);
        $location = Location::create([
            'name' => 'Tortosa',
            'postalcode' => 43500
        ]);
        $person = Person::create([
            'identifier_id' => $identifier->id,
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'birthdate' => Carbon::createFromFormat('d-m-Y','02-03-1978'),
            'birthplace_id' => $location->id,
            'gender' => 'Home',
            'civil_status' => 'Solter/a',
            'phone' => '977504678',
            'other_phones' => '',
            'mobile' => '650478758',
            'other_mobiles' => '',
            'email' => 'pepepardojeans@gmail.com',
            'other_emails' => '',
            'notes' => 'Bla Bla Bla'
        ]);
        $person->assignUser($user);
        return $person;
    }
}

if (! function_exists('create_sample_people')) {
    function create_sample_people() {
        $user = factory(User::class)->create([
            'email' => 'pepepardojeans@gmail.com',
            'name' => 'Pepe Pardo Jeans'
        ]);
        $person = Person::create([
//            'identifier_id' => '',
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'birthdate' => Carbon::createFromFormat('d-m-Y','02-03-1978'),
//            'birthplace_id' => 'Jeans',
            'gender' => 'Home',
            'civil_status' => 'Solter/a',
            'phone' => '977504678',
            'other_phones' => '',
            'mobile' => '650478758',
            'other_mobiles' => '',
            'email' => 'pepepardojeans@gmail.com',
            'other_emails' => '',
            'notes' => 'Bla Bla Bla'
        ]);
        $person->assignUser($user);

        $user2 = factory(User::class)->create([
            'email' => 'pepepringao@gmail.com',
            'name' => 'Pepe Pringao'
        ]);
        $person2 = Person::create([
//            'identifier_id' => '',
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'birthdate' => Carbon::now(),
//            'birthplace_id' => '',
            'gender' => 'Dona',
            'civil_status' => 'Solter/a',
            'phone' => '977454673',
            'other_phones' => '',
            'mobile' => '650758421',
            'other_mobiles' => '',
            'email' => 'pepepringaogmail.com',
            'other_emails' => '',
            'notes' => 'Bla Bla Bla 1'
        ]);
        $person2->assignUser($user2);


        $user3 = factory(User::class)->create([
            'email' => 'homersimpson@gmail.com',
            'name' => 'Homer Simpson'
        ]);
        $person3 = Person::create([
            'user_id' => $user3->id,
//            'identifier_id' => '',
            'givenName' => 'Homer',
            'sn1' => 'Simpson',
            'birthdate' => Carbon::now(),
//            'birthplace_id' => '',
            'gender' => 'Dona',
            'civil_status' => 'Solter/a',
            'phone' => '977789573',
//            'other_phones' => '',
            'mobile' => '650778521',
//            'other_mobiles' => '',
            'email' => 'homersimpson.com',
//            'other_emails' => '',
            'notes' => 'Bla Bla Bla 2'
        ]);
        $person3->assignUser($user3);

    }
}

if (! function_exists('sample_people')) {
    function sample_people()
    {
        Person::create([
//            'identifier_id' => '',
            'givenName' => 'Nomllargquetecagas sdasda asd asd asd asdasd asdas das asd ',
            'sn1' => 'Cognom1llarquetecagas',
            'sn2' => 'Cognom2llarquetecagas',
            'birthdate' => Carbon::now(),
//            'birthplace_id' => '',
            'gender' => 'Dona',
            'civil_status' => 'Solter/a',
            'phone' => '977789573',
//            'other_phones' => '',
            'mobile' => '650778521',
//            'other_mobiles' => '',
            'email' => 'NomllargquetecagasCognom1llarquetecagas@gmail.com',
//            'other_emails' => '',
            'notes' => 'Bla Bla Bla 2'
        ]);
    }
}

if (! function_exists('is_valid_uuid')) {
    /**
     * Check if a given string is a valid UUID
     *
     * @param   string  $uuid   The string to check
     * @return  boolean
     */
    function is_valid_uuid( $uuid )
    {

        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            return false;
        }
        return true;
    }
}

if (! function_exists('ldap_md5_hash')) {
    function ldap_md5_hash($password)	{
        return  "{MD5}".base64_encode( pack('H*', md5($password)));
    }
}

if (! function_exists('ldap_sha1_hash')) {
    function ldap_sha1_hash($password)	{
        return  "{SHA}" . base64_encode(pack("H*",sha1($password)));
    }
}

if (! function_exists('ldap_nt_password')) {
    function ldap_nt_password($password)	{
        $cr = new Crypt_CHAP_MSv2();
        return strtoupper(bin2hex($cr->ntPasswordHash($password)));
    }
}

if (! function_exists('ldap_lm_password')) {
    function ldap_lm_password($password)	{
        $cr = new Crypt_CHAP_MSv2();
        return strtoupper(bin2hex($cr->lmPasswordHash($password)));
    }
}
