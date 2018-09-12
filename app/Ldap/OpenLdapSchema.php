<?php

namespace App\Ldap;

use Adldap\Schemas\OpenLDAP;

/**
 * Class OpenLdapSchema
 * @package App\Ldap
 */
class OpenLdapSchema extends OpenLDAP
{
    /**
     * {@inheritdoc}
     */
    public function user()
    {
        return 'posixAccount';
    }
}