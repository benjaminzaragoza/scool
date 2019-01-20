<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role: Decorator of Spatie Role.
 *
 * @package App\Models
 */
class Role extends SpatieRole
{
    use FormattedDates, ApiURI;

    const TEACHER = [
      'id' => 1,
      'name' => 'Teacher'
    ];

    const STUDENT = [
        'id' => 2,
        'name' => 'Student'
    ];

    const JANITOR = [
        'id' => 3,
        'name' => 'Janitor'
    ];

    const ADMINISTRATIVE_ASSISTANT = [
        'id' => 4,
        'name' => 'AdministrativeAssistant'
    ];

    const FAMILIAR = [
        'id' => 5,
        'name' => 'Familiar'
    ];

//    const MANAGER = [
//        'id' => 2,
//        'name' => 'Manager'
//    ];
//
//    const ADMIN = [
//        'id' => 2,
//        'name' => 'Student'
//    ];

    const USERS_MANAGER = [
        'id' => 6,
        'name' => 'UsersManager'
    ];

    const STAFF_MANAGER = [
        'id' => 7,
        'name' => 'StaffManager'
    ];

    const TEACHERS_MANAGER = [
        'id' => 8,
        'name' => 'TeachersManager'
    ];

    const PHOTO_TEACHERS_MANAGER = [
        'id' => 9,
        'name' => 'PhotoTeachersManager'
    ];

    const LESSONS_MANAGER = [
        'id' => 10,
        'name' => 'LessonsManager'
    ];

    const INCIDENTS = [
        'id' => 11,
        'name' => 'Incidents'
    ];

    const INCIDENTS_MANAGER = [
        'id' => 12,
        'name' => 'IncidentsManager'
    ];

    const CHANGELOG_MANAGER = [
        'id' => 13,
        'name' => 'ChangelogManager'
    ];
    const MOODLE_MANAGER = [
        'id' => 14,
        'name' => 'MoodleManager'
    ];
    const PEOPLE_MANAGER = [
        'id' => 15,
        'name' => 'PeopleManager'
    ];
//    const Superadmin = [
//        'id' => 2,
//        'name' => 'Superadmin'
//    ];

    const CURRICULUM = [
        'id' => 16,
        'name' => 'Curriculum'
    ];
    const CURRICULUM_MANAGER = [
        'id' => 17,
        'name' => 'CurriculumManager'
    ];

    const POSITIONS = [
        'id' => 18,
        'name' => 'Positions'
    ];

    const POSITIONS_MANAGER = [
        'id' => 19,
        'name' => 'PositionsManager'
    ];

    const ROLES = [
        Role::TEACHER,
        Role::STUDENT,
        Role::JANITOR,
        Role::ADMINISTRATIVE_ASSISTANT,
        Role::FAMILIAR,
        Role::USERS_MANAGER,
        Role::STAFF_MANAGER,
        Role::TEACHERS_MANAGER,
        Role::PHOTO_TEACHERS_MANAGER,
        Role::LESSONS_MANAGER,
        Role::INCIDENTS,
        Role::INCIDENTS_MANAGER,
        Role::CHANGELOG_MANAGER,
        Role::MOODLE_MANAGER,
        Role::PEOPLE_MANAGER,
        Role::CURRICULUM,
        Role::CURRICULUM_MANAGER,
        Role::POSITIONS,
        Role::POSITIONS_MANAGER,
        Role::CURRICULUM_MANAGER,
        Role::CURRICULUM_MANAGER,
    ];

    public $guarded = [];

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'api_uri' => $this->api_uri,

            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
        ];
    }
}
