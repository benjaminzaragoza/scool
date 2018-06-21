<?php

namespace App;

use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tenant.
 *
 * @package App
 */
class Tenant extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Establish a connection with the tenant's database.
     */
    public function connect()
    {
        if (! $this->connected()) {
            tenant_connect(
                $this->hostname,
                $this->username,
                $this->password,
                $this->database
            );
        }
    }

    /**
     * Configure.
     */
    public function configure()
    {
        configure_tenant($this);
    }

    /**
     * @return array
     */
    public function test()
    {
        return test_connection(
            $this->hostname,
            $this->username,
            $this->password,
            $this->database
        );
    }

    /**
     * @return array
     */
    public function testAdminUser($password)
    {
        return test_user($this->user, $this, $password);
    }

    /**
     * Get the user that owns the tenant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Check if the current tenant connection settings matches the company's database settings.
     *
     * @return bool
     */
    private function connected()
    {
        $connection = Config::get('database.connections.tenant');

        return $connection['username'] == $this->username &&
            $connection['password'] == $this->password &&
            $connection['database'] == $this->database;
    }

    /**
     * Find Tenant by subdomain.
     *
     * @param $subdomain
     */
    public static function findBySubdomain($subdomain) {
        return self::where('subdomain',$subdomain)->firstOrFail();
    }
}
