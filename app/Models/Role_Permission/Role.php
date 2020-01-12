<?php


namespace App\Models\Role_Permission;
use App\Traits\UsesUuid;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * App\Models\Role_Permission\Role
 *
 * @property string $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role_Permission\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role_Permission\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role_Permission\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role_Permission\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role_Permission\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role_Permission\Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role_Permission\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role_Permission\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role_Permission\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends SpatieRole
{
    use UsesUuid;
    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Check whether current role is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === Acl::ROLE_ADMIN;
    }

}
