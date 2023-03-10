<?php

namespace App;

use App\Behaviors\Listable;
use App\Http\Requests\Request;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $login
 * @property string $password
 * @property integer $location_id
 * @property integer $business_unit_id
 * @property integer $branch_id
 * @property integer $department_id
 * @property integer $manager_id
 * @property boolean $vip
 * @property boolean $is_ad
 * @property string $remember_token
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Group[] $groups
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLocationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereBusinessUnitId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereBranchId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDepartmentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereManagerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereVip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereIsAd($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User technicians()
 * @method static \Illuminate\Database\Query\Builder|\App\User selection($empty = false)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements CanResetPassword
{
    use SoftDeletes, Notifiable;

    protected $fillable = [
        'name', 'email', 'login', 'password', 'location_id', 'location_id', 'business_unit_id',
        'branch_id', 'department_id', 'manager_id', 'vip', 'is_ad', 'phone', 'mobile1', 'mobile2', 'job',
        'manager_id', 'group_ids', 'role_ids', 'employee_id', 'extra_fields', 'is_disabled',
        'password_reset',
        'last_login_date',
        'last_reset_password_date', 'signature'
    ];

    protected $casts = ['extra_fields' => 'array'];
    protected $hidden = [
        'password', 'remember_token',
    ];

    use Listable;

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany('App\Group', 'group_supervisor', 'user_id', 'group_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function scopeTechnicians(Builder $query)
    {
        return $query->whereHas('groups', function (Builder $q) {
            $q->support();
        });
    }

    public function scopeEmployees(Builder $query)
    {
        return $query->whereNotNull('employee_id')
            ->where('employee_id', '<>', 0)
            ->orderBy('name', 'ASC');
    }

    function scopeActive($query)
    {
        return $query->where('is_disabled', 0);
    }

    function scopeApprovers($query)
    {
        return $query->whereNotNull('email')
            ->where('is_disabled', 0)
            ->where('employee_id', '<>', 0);
    }

    public function isTechnician()
    {
        return $this->groups()->technician()->exists();
    }

    public function isSupport()
    {
        return $this->groups()->support()->exists();
    }

    public function isAdmin()
    {
        return $this->groups()->admin()->exists();
    }

    public function isReporting()
    {
        return $this->groups()->reporting()->exists();
    }

    public function isTechnicainSupervisor($ticket)
    {
        if ($ticket->technician) {
            $groups = $ticket->technician->groups;

            foreach ($groups as $group) {
                if (in_array($this->id, $group->supervisors->pluck('id')->toArray())) {
                    return true;
                }
            }
        }


        return false;
    }

    public function hasGroup($group)
    {
        if (is_a($group, Group::class)) {
            $group_id = $group->id;
        } else {
            $group_id = $group;
        }

        return $this->groups->contains('id', $group_id);
    }

    public function scopeRequesterList(Builder $query)
    {
        $users = collect();

        $dbUsers = $query->select(['id', 'name', 'email'])->orderBy('name')->get();

        foreach ($dbUsers as $user) {
            $users->put($user->id, sprintf('%s <%s>', $user->name, $user->email));
        }

        return $users;
    }

    public function scopeQuickSearch(Builder $query)
    {
        if (\Request::has('search')) {
            $query->where(function (Builder $q) {
                $term = '%' . \Request::get('search') . '%';
                $q->where('login', 'LIKE', $term)
                    ->orWhere('name', 'LIKE', $term)
                    ->orWhere('email', 'LIKE', $term)
                    ->orWhere('employee_id', 'LIKE', $term);
            });
        }

        return $query;
    }

    public function getGroupIdsAttribute()
    {
        return $this->groups->pluck('id')->toArray();
    }

    public function setGroupIdsAttribute($group_ids)
    {
        self::saved(function ($ticket) use ($group_ids) {
            $ticket->groups()->sync($group_ids);
        });

        return $this;
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        if (is_a($role, Role::class)) {
            $role_id = $role->id;
        } else {
            $role_id = $role;
        }

        return $this->groups->contains('id', $role_id);
    }

    function getFoldersAttribute()
    {
        if (auth()->user()->isAdmin()) {
            return ReportFolder::all();
        }

        $folders = ReportFolder::where('user_id', auth()->id())->get();

        $authorized_reports = ReportFolder::whereIn('id', Report::whereIn('id', ReportUser::where('user_id', auth()->id())
            ->pluck('report_id')->toArray())->pluck('folder_id'))->get();


        return $folders->merge($authorized_reports);
    }


    function reply_templates()
    {
        return $this->hasMany(ReplyTemplate::class);
    }

    public function hasTasks(Ticket $ticket)
    {
        return $ticket->tasks()->where('technician_id', auth()->id())->exists();
    }


    function getProfileNameAttribute()
    {
        $user = $this->name;
        $arrayName = explode(' ', $user);
        return substr($arrayName[0], 0) . ' ' . (isset($arrayName[1]) ? substr($arrayName[1], 0) : '');
    }


    function toRequesterJson($loadFromSAP = false)
    {
        $systemUserInformation = [
            'id' => $this->id,
            'name' => $this->name,
            'employee_id' => $this->employee_id ?? 'Not Assigned',
            'company' => $this->business_unit->name ?? 'Not Assigned',
            'email' => $this->email ?? 'Not Assigned',
            'job_title' => $this->job ?? 'Not Assigned',
            'department' => $this->department->name ?? 'Not Assigned',
            'mobile1' => $this->mobile1 ?? 'Not Assigned',
            'mobile2' => $this->mobile2 ?? 'Not Assigned',
            'phone' => $this->phone ?? 'Not Assigned',
            'manager_name' => $this->manager ? $this->manager->name : 'Not Assigned',
            'manager_email' => $this->manager ? $this->manager->email : 'Not Assigned',
        ];
        $sapInformation = [];

        if ($loadFromSAP && $this->employee_id) {
            $sapInformation = $this->loadFromSAP();
        }

        return array_merge($systemUserInformation, $sapInformation);

    }

    function loadFromSAP()
    {
        $user = \App\User::where('employee_id', $this->employee_id)->first();
        $sapApi = new \App\Helpers\SapApi($user);
        $sapApi->getUserInformation();
        if ($sapApi->sapUser) {
            return $sapApi->sapUser->getEmployeeSapInformation();
        }
        return [];
    }

    function upload($requestFileName)
    {

        $request = request();

        $file = $request->file($requestFileName);
        $name = 'signature.png';

        $folder = storage_path("app/public/signatures/" . $this->id . '/');

        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }
        $path = $folder . $name;

        if (is_file($path)) {
            $filename = uniqid() . '_' . $name;
            $path = $folder . $filename;
        }

        $file->move($folder, $name);

        return "/signatures/" . $this->id . '/' . $name;
    }

    static function getDirectManager($id)
    {
        return User::where('employee_id', trim($id))->first() ? User::where('employee_id', trim($id))->first()->id : null;
    }

    static function getDepartment($departmentName, $businessUnitId)
    {
        $department = Department::where('name', $departmentName)->first();
        if (!$department) {
            $department = Department::create(['name' => $departmentName, 'business_unit_id' => $businessUnitId]);
        }

        return $department->id;
    }

    function getLastGeneratedPayslipAttribute()
    {
        return UserProcess::where('employee_id', auth()->user()->employee_id)->first();
    }
}
  