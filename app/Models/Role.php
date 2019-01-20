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
      'name' => 'Teacher'
    ];

    const STUDENT = [
        'name' => 'Student'
    ];

    const JANITOR = [
        'name' => 'Janitor'
    ];

    const ADMINISTRATIVE_ASSISTANT = [
        'name' => 'AdministrativeAssistant'
    ];

    const FAMILIAR = [
        'name' => 'Familiar'
    ];

    const USERS_MANAGER = [
        'name' => 'UsersManager'
    ];

    const STAFF_MANAGER = [
        'name' => 'StaffManager'
    ];

    const TEACHERS_MANAGER = [
        'name' => 'TeachersManager'
    ];

    const PHOTO_TEACHERS_MANAGER = [
        'id' => 9,
        'name' => 'PhotoTeachersManager'
    ];

    const LESSONS_MANAGER = [
        'name' => 'LessonsManager'
    ];

    const INCIDENTS = [
        'name' => 'Incidents'
    ];

    const INCIDENTS_MANAGER = [
        'name' => 'IncidentsManager'
    ];

    const CHANGELOG_MANAGER = [
        'name' => 'ChangelogManager'
    ];
    const MOODLE_MANAGER = [
        'name' => 'MoodleManager'
    ];
    const PEOPLE_MANAGER = [
        'name' => 'PeopleManager'
    ];

    const CURRICULUM = [
        'name' => 'Curriculum'
    ];
    const CURRICULUM_MANAGER = [
        'name' => 'CurriculumManager'
    ];

    const POSITIONS = [
        'name' => 'Positions'
    ];

    const POSITIONS_MANAGER = [
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
