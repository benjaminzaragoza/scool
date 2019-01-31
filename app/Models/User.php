<?php

namespace App\Models;

use App\Http\Resources\Tenant\UserCollection;
use App\Models\Traits\ApiURI;
use App\Notifications\VerifyEmail;
use App\Notifications\WelcomeEmailNotification;
use Cache;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

use App\Models\Traits\FormattedDates;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;
use Session;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Storage;

/**
 * Class User.
 *
 * @package App
 */
class User extends Authenticatable implements HasMedia, MustVerifyEmailContract
{
    use Notifiable, HasApiTokens, HasRoles, FormattedDates, Impersonate, HasMediaTrait, MustVerifyEmail, ApiURI;

    const DEFAULT_PHOTO = 'default.png';
    const PHOTOS_PATH = 'user_photos';
    const DEFAULT_PHOTO_PATH = 'img/' . self::DEFAULT_PHOTO;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','mobile', 'password','photo','photo_hash','user_type_id'
    ];

    protected $appends = [
        'formatted_created_at',
        'formatted_updated_at',
        'hashid',
        'full_search',
        'all_permissions'
    ];

    protected $dates = [
        'last_login',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return bool
     */
    public function impersonatedBy()
    {
        if ($this->isImpersonated()) return User::findOrFail(Session::get('impersonated_by'));
        return null;
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('photos')->useDisk('local');

        $this->addMediaCollection('docs')->useDisk('local');
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->isSuperAdmin();
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return !$this->isSuperAdmin();
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->hashedKey();
    }

    /**
     * Hashed key.
     * @return string
     */
    protected function hashedKey()
    {
        $hashids = new \Hashids\Hashids(config('scool.salt'));
        return $hashids->encode($this->getKey());
    }

    /**
     * @return mixed
     */
    public function isSuperAdmin()
    {
        return $this->admin;
    }

    /**
     * Create user if no user with same email exists.
     *
     * @param $data
     * @return mixed
     */
    public static function createIfNotExists($data)
    {
        if (! $user = self::where('email','=',$data['email'])->first()) return self::create($data);
        return $user;
    }


    /**
     * Assign role but not fail if role is already assigned.
     *
     * @param $role
     * @return $this
     */
    public function addRole($role)
    {
        if (!$this->hasRole($role)) $this->assignRole($role);
        return $this;
    }

    /**
     * Remove role but not fail if role is already removed.
     *
     * @param $role
     * @return $this
     */
    public function rmRole($role)
    {
        if ($this->hasRole($role)) $this->removeRole($role);
        return $this;
    }

    /**
     * Get the jobs for the user.
     */
    public function jobs()
    {
        return $this->belongsToMany(Job::class,'employees')->withPivot('start_at', 'end_at')->as('employee')
            ->withTimestamps();
    }

    /**
     * Assign job.
     *
     * @param $job
     * @param bool $holder
     * @param null $start
     * @param null $end
     * @return $this
     */
    public function assignJob($job, $holder = true, $start = null, $end = null)
    {
        $this->jobs()->save($job,['holder' => $holder, 'start_at' => $start, 'end_at' => $end]);
        return $this;
    }

    /**
     * Assign job of type teacher to user
     *
     * @param $job
     * @param $administrativeStatus
     * @return User
     */
    public function assignTeacherJob($job, $administrativeStatus)
    {
        if (is_object($administrativeStatus)) $administrativeStatus = $administrativeStatus->id;
        if (!is_object($job)) $job = Job::findOrFail($job);
        $start_at = null;
        $holder = true;
        if (AdministrativeStatus::findByName('Substitut/a')->id === $administrativeStatus) {
            $start_at = Carbon::now();
            $holder = false;
        }
        if (AdministrativeStatus::findByName('Interí/na')->id === $administrativeStatus) {
            $start_at = Carbon::now();
        }

        return $this->assignJob($job, $holder, $start_at);
    }

    /**
     * Unassign job.
     *
     * @param $job
     * @return $this
     */
    public function unassignJob($job)
    {
        $this->jobs()->detach($job->id);
        return $this;
    }

    /**
     * Unassign jobs.
     *
     * @return $this
     */
    public function unassignJobs()
    {
        DB::table('employees')->where('user_id', $this->id)->delete();
        return $this;
    }

    /**
     * Lastname.
     *
     * @return null|string
     */
    public function lastname()
    {
        if ($this->person) {
            $lastname = $this->person->sn1;
            if ($this->person->sn2) $lastname = $lastname . ' ' . $this->person->sn2;
            return $lastname;
        }
        return null;
    }

    /**
     * Assign full name.
     *
     * @param $fullname
     * @return $this
     */
    public function assignFullName($fullname)
    {
        if ($this->person) {
            $this->person->givenName = $fullname['givenName'];
            $this->person->sn1 = $fullname['sn1'];
            $this->person->sn2 = $fullname['sn2'];
            $this->person->save();
        } else {
            $person = Person::create($fullname);
            $person->user_id = $this->id;
            $person->save();
        }
        return $this;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send the welcome email notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendWelcomeEmailNotification($token)
    {
        $this->notify(new WelcomeEmailNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Scope a query to only include users without job.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return User::doesntHave('jobs')->get();
    }

    /**
     * Scope a query to only include teacher users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTeachers($query)
    {
        return $query->role('Teacher')
            ->whereHas('jobs', function ($query) {
                $query->where('type_id','=',JobType::findByName('Professor/a')->id);
            })->whereHas('jobs', function ($query) {
                $query->where('type_id','=',JobType::findByName('Professor/a')->id);
        });
    }

    /**
     * Get the initial/proposed photo name.
     *
     * @param  string  $value
     * @return string
     */
    public function getPhotoNameAttribute($value)
    {
        return $this->id . '_' . str_slug($this->name) . '_' . str_slug($this->email);
    }

    /**
     * Get the photo path prefix.
     *
     * @param  string  $value
     * @return string
     */
    public function getPhotoPathAttribute($value)
    {
        return 'user_photos/';
    }

    /**
     * formatted_updated_at_date attribute.
     *
     * @return mixed
     */
    public function getLastLoginFormattedAttribute()
    {
        return optional($this->last_login)->format('h:i:sA d-m-Y');
    }

    /**
     * formatted_created_at_date_diff attribute.
     *
     * @return mixed
     */
    public function getLastLoginDiffAttribute()
    {
        Carbon::setLocale(config('app.locale'));
        return optional($this->last_login)->diffForHumans(Carbon::now());
    }

    /**
     * Get the photo path prefix.
     *
     * @param  string  $value
     * @return string
     */
    public function getHashIdAttribute($value)
    {
        return $this->hashedKey();
    }

    /**
     * Obtain photo tenant path.
     *
     * @param $photo
     * @param $tenant
     * @return mixed|string
     */
    public function obtainPhotoTenantPath($photo, $tenant)
    {
        $ext = pathinfo($photo, PATHINFO_EXTENSION);
        $path = preg_replace('#/+#','/',
            $tenant. '/' . $this->photo_path . '/'. $this->photo_name);
        if($ext) {
            $path = $path . '.' . $ext;
        }
        return $path;
    }

    /**
     * Assign photo to user.
     *
     * @param $photo
     * @return $this
     */
    public function assignPhoto($photo,$tenant)
    {
        $path = $this->obtainPhotoTenantPath($photo,$tenant);
        if (starts_with($photo, $storage = Storage::disk('local')->path(''))) {
            $photo = str_after($photo, $storage);
        }
        if(Storage::exists($path)) Storage::delete($path);
        Storage::disk('local')->move($photo, $path);
        //Remove extra slashes from path like user_photos//photo.png
        $this->photo = $path;
        $this->photo_hash = md5($path);
        $this->save();
        return $this;
    }

    /**
     * Unassign photo.
     */
    public function unassignPhoto($destinationPath)
    {
        if(Storage::exists($destinationPath)) Storage::delete($destinationPath);
        Storage::disk('local')->move($this->photo, $destinationPath);
        $this->photo = '';
        $this->photo_hash = '';
        $this->save();
        return $this;
    }

    /**
     * Get the teacher record associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the person record associated with the user.
     */
    public function person()
    {
        return $this->hasOne(Person::class);
    }

    /**
     * Assign teacher to user.
     *
     * @param $teacher
     * @return $this
     */
    public function assignTeacher($teacher)
    {
        $teacher->user_id = $this->id;
        $this->user_type_id = UserType::TEACHER;
        $this->save();
        $teacher->save();
        return $this;
    }

    /**
     * Email to send notifications.
     *
     * @param $notification
     * @return array|mixed
     */
    public function routeNotificationForMail($notification)
    {
        if ($this->person && $this->person->email) return [$this->email,$this->person->email];
        return $this->email;
    }

    /**
     * Assign personal data.
     *
     * @param $data
     * @return $this
     */
    public function assignPersonalData($data)
    {
        if($this->person) {
            $this->person->identifier_id = isset($data['identifier_id']) ? $data['identifier_id'] : null;
            $this->person->birthdate = isset($data['birthdate']) ? $data['birthdate'] : null;
            $this->person->birthplace_id = isset($data['birthplace_id']) ? $data['birthplace_id'] : null;
            $this->person->gender = isset($data['gender']) ? $data['gender'] : null;
            $this->person->mobile = isset($data['mobile']) ? $data['mobile'] : null;
            $this->person->other_mobiles = isset($data['other_mobiles']) ? json_encode(explode(',',$data['other_mobiles'])) : null;
            $this->person->phone = isset($data['phone']) ? $data['phone'] : null;
            $this->person->other_phones = isset($data['other_phones']) ? json_encode(explode(',',$data['other_phones'])) : null;
            $this->person->email = isset($data['email']) ? $data['email'] : null;
            $this->person->other_emails = isset($data['other_emails']) ? json_encode(explode(',',$data['other_emails'])) : null;
            $this->person->notes = isset($data['notes']) ? $data['notes'] : null;
            $this->person->civil_status = isset($data['civil_status']) ? $data['civil_status'] : null;
            $this->person->save();
        } else {
            $person = Person::create([
                'identifier_id' => isset($data['identifier_id']) ? $data['identifier_id'] : null,
                'birthdate' => isset($data['birthdate']) ? $data['birthdate'] : null,
                'birthplace_id' => isset($data['birthplace_id']) ? $data['birthplace_id'] : null,
                'gender' => isset($data['gender']) ? $data['gender'] : null,
                'mobile' => isset($data['mobile']) ? $data['mobile'] : null,
                'other_mobiles' => isset($data['other_mobiles']) ? json_encode(explode(',',$data['other_mobiles'])) : null,
                'phone' => isset($data['phone']) ? $data['phone'] : null,
                'other_phones' => isset($data['other_phones']) ? json_encode(explode(',',$data['other_phones'])) : null,
                'email' => isset($data['email']) ? $data['email'] : null,
                'other_emails' => isset($data['other_emails']) ? json_encode(explode(',',$data['other_emails'])) : null,
                'notes' => isset($data['notes']) ? $data['notes']  : null,
                'civil_status' => isset($data['civil_status']) ? $data['civil_status']  : null
            ]);
            $person->user_id = $this->id;
            $person->save();
        }
        return $this;
    }

    /**
     * Assign address.
     *
     * @param Address $address
     * @return $this
     */
    public function assignAddress(Address $address)
    {
        if ($this->person) {
            $address->person_id = $this->person->id;
        } else {
            $person = Person::create();
            $person->assignUser($this->id);
            $address->person_id = $person->id;
        }
        $address->save();
        return $this;
    }

    /**
     * @param GoogleUser $googleUser
     */
    public function assignGoogleUser(GoogleUser $googleUser)
    {
        $googleUser->user_id = $this->id;
        $googleUser->save();
    }

    /**
     * Assign teacher data.
     *
     * @param $data
     * @return $this
     */
    public function assignTeacherData($data)
    {
        if ($this->teacher) {
            $this->teacher->administrative_status_id = isset($data['administrative_status_id']) ? $data['administrative_status_id'] : null;
            $this->teacher->specialty_id = isset($data['specialty_id']) ? $data['specialty_id'] : null;
            $this->teacher->titulacio_acces = isset($data['titulacio_acces']) ? $data['titulacio_acces'] : null;
            $this->teacher->altres_titulacions = isset($data['altres_titulacions']) ? $data['altres_titulacions'] : null;
            $this->teacher->idiomes = isset($data['idiomes']) ? $data['idiomes'] : null;
            $this->teacher->altres_formacions = isset($data['altres_formacions']) ? $data['altres_formacions'] : null;
            $this->teacher->perfil_professional = isset($data['perfil_professional']) ? $data['perfil_professional'] : null;
            $this->teacher->data_inici_treball = isset($data['data_inici_treball']) ? $data['data_inici_treball'] : null;
            $this->teacher->data_incorporacio_centre = isset($data['data_incorporacio_centre']) ? $data['data_incorporacio_centre'] : null;
            $this->teacher->data_superacio_oposicions = isset($data['data_superacio_oposicions']) ? $data['data_superacio_oposicions'] : null;
            $this->teacher->lloc_destinacio_definitiva = isset($data['lloc_destinacio_definitiva']) ? $data['lloc_destinacio_definitiva'] : null;
            $this->teacher->save();
        } else {
            $teacher = Teacher::create([
                'code' => $data['code'],
                'administrative_status_id' => isset($data['administrative_status_id']) ? $data['administrative_status_id'] : null,
                'specialty_id' => isset($data['specialty_id']) ? $data['specialty_id'] : null,
                'titulacio_acces' => isset($data['titulacio_acces']) ? $data['titulacio_acces'] : null,
                'altres_titulacions' => isset($data['altres_titulacions']) ? $data['altres_titulacions'] : null,
                'idiomes' => isset($data['idiomes']) ? $data['idiomes'] : null,
                'altres_formacions' => isset($data['altres_formacions']) ? $data['altres_formacions'] : null,
                'perfil_professional' => isset($data['perfil_professional']) ? $data['perfil_professional'] : null,
                'data_inici_treball' => isset($data['data_inici_treball']) ? $data['data_inici_treball'] : null,
                'data_incorporacio_centre' => isset($data['data_incorporacio_centre']) ? $data['data_incorporacio_centre'] : null,
                'data_superacio_oposicions' => isset($data['data_superacio_oposicions']) ? $data['data_superacio_oposicions'] : null,
                'lloc_destinacio_definitiva' => isset($data['lloc_destinacio_definitiva']) ? $data['lloc_destinacio_definitiva'] : null
            ]);
            $teacher->user_id = $this->id;
            $teacher->save();
        }

        return $this;
    }

    /**
     * The positions that belong to the user.
     */
    public function positions()
    {
        return $this->belongsToMany(Position::class)->withTimestamps();
    }

    /**
     * Assignar un càrrec a un usuari
     *
     * @param $position
     * @return $this
     */
    public function assignPosition($position)
    {
        $this->positions()->save($position);
        return $this;
    }

    /**
     * Dessasignar un càrrec a un usuari
     *
     * @param $position
     * @return $this
     */
    public function removePosition($position)
    {
        $position = is_object($position) ? $position->id : $position;
        $this->positions()->detach($position);
        return $this;
    }

    public function isTeacher()
    {
        if (
            $this->hasRole('Teacher') &&
            $this->teacher &&
            $this->teacher->code &&
            $this->jobs()->first() &&
            $this->jobs()->first()->type_id == JobType::findByName('Professor/a')->id
            ) return true;
        return false;
    }

    /**
     * Find by name.
     *
     * @param $name
     * @return mixed
     */
    public static function findByName($name)
    {
        return self::where('name','=',$name)->first();
    }

    /**
     * Find by email.
     *
     * @param $email
     * @return mixed
     */
    public static function findByEmail($email)
    {
        return self::where('email','=',$email)->first();
    }

    /**
     * Get users.
     *
     * @return mixed
     */
    public static function getUsers()
    {
        return (new UserCollection(User::with('roles','permissions','person','googleUser','moodleUser')->get()))->transform();
    }

    /**
     * Get the google user record associated with the user.
     */
    public function googleUser()
    {
        return $this->hasOne(GoogleUser::class);
    }

    /**
     * Get the moodle user record associated with the user.
     */
    public function moodleUser()
    {
        return $this->hasOne(MoodleUser::class);
    }

    /**
     * Map simple
     * @return array
     */
    public function mapSimple()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'isSuperAdmin' => (boolean) $this->isSuperAdmin(),
            'user_type_id' => $this->user_type_id,
            'user_type' => $this->user_type,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'photo' => $this->photo,
            'photo_hash' => $this->photo_hash,
            'email_verified_at' => $this->email_verified_at,
            'mobile_verified_at' => $this->mobile_verified_at,
            'last_login' => $this->last_login,
            'last_login_formatted' => $this->last_login_formatted,
            'last_login_ip' => $this->last_login_ip,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_updated_at' => $this->formatted_updated_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'hashid' => $this->hashid,
            'full_search' => $this->full_search,
            'api_uri' => $this->api_uri
        ];
    }

    /**
     * Get the user's corporativeEmail.
     *
     * @param  string  $value
     * @return string
     */
    public function getCorporativeEmailAttribute($value)
    {
        return optional($this->googleUser)->google_email;
    }

    /**
     * Map
     * @return array
     */
    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'isSuperAdmin' => (boolean) $this->isSuperAdmin(),
            'user_type_id' => $this->user_type_id,
            'user_type' => $this->user_type,
            'person_id' => optional($this->person)->id,
            'givenName' => optional($this->person)->givenName,
            'sn1' => optional($this->person)->sn1,
            'sn2' => optional($this->person)->sn2,
            'lastname' => $this->lastname(),
            'email' => $this->email,
            'hash_id' => $this->hash_id,
            'photo' => $this->photo,
            'photo_hash' => $this->photo_hash,
            'corporativeEmail' => optional($this->googleUser)->google_email,
            'googleId' => optional($this->googleUser)->google_id,
            'moodleId' => optional($this->moodleUser)->moodle_id,
            'moodleUsername' => optional($this->moodleUser)->moodle_username,
            'email_verified_at' => $this->email_verified_at,
            'mobile_verified_at' => $this->mobile_verified_at,
            'mobile' => $this->mobile,
            'last_login' => $this->last_login,
            'last_login_formatted' => $this->last_login_formatted,
            'last_login_diff' => $this->last_login_diff,
            'last_login_ip' => $this->last_login_ip,
            'roles' => $this->userRoles(),
            'permissions' => $this->userPermissions(),
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'formatted_updated_at' => $this->formatted_updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'admin' => $this->admin,
            'hashid' => $this->hashid,
            'full_search' => $this->full_search,
            'all_permissions' => $this->all_permissions,
            'api_uri' => $this->api_uri
        ];
    }

    /**
     * @return mixed
     */
    public function userPermissions()
    {
        if ($this->isSuperAdmin()) {
            return Cache::rememberForever('permissionNames', function () {
                return Permission::all()->pluck('name')->unique()->toArray();
            });
        }
        return $this->permissions->pluck('name')->unique()->toArray();
    }

    /**
     * @return mixed
     */
    public function userRoles()
    {
        if ($this->isSuperAdmin()) {
            return Cache::rememberForever('roleNames', function () {
                return Role::all();
            });
        }
        return $this->roles;
    }

    /**
     * full_search_attribute
     *
     * @param $value
     * @return string
     */
    public function getFullSearchAttribute($value)
    {
        return "$this->email $this->name";
    }

    /**
     * user_type
     *
     * @param $value
     * @return string
     */
    public function getUserTypeAttribute($value)
    {
        if ($this->user_type_id) return UserType::TYPES[$this->user_type_id];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * Get all user permissions.
     *
     * @return bool
     */
    public function getAllPermissionsAttribute()
    {
        return Cache::rememberForever('user_all_permissions', function () {
            return $this->getAllPermissions();
        });
    }

    /**
     * Link.
     *
     * @return string
     */
    public function link()
    {
        return '<a target="_blank" href="/' . $this->apiURI . '/' . $this->id . '">' . $this->name . '</a>';
    }

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return $this->mobile;
    }
}
