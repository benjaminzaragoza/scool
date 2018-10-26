<?php

use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * Class TenantDatabaseSeeder
 */
class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        seed_provinces();
        $this->call(LocationsTableSeeder::class);

        initialize_menus();
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        create_tenant_admin_user();
        create_tenant_user_without_permissions();
        create_other_tenant_admin_users();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_departments();
        initialize_specialities();
        initialize_administrative_statuses();
        initialize_users();
        initialize_teachers();
        initialize_substitutes();
        initialize_head_departments();
        seed_identifier_types();
        inititialize_teachers_personal_data();
        inititialize_substitutes_personal_data();
        initialize_teacher_photos();

        initialize_janitors();
        initialize_administrative_assistants();
        seed_identifier_types();
        initialize_positions();

        initialize_subjects();
    }
}
