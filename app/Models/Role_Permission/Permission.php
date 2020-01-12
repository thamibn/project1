<?php


namespace App\Models\Role_Permission;

use App\Traits\UsesUuid;
use Illuminate\Database\Query\Builder;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @method static allowed()
 */
class Permission extends SpatiePermission
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
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', Acl::PERMISSION_PERMISSION_MANAGE);
    }

}
