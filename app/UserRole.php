<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserRol extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_roles';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'short_description'];

    public function permissions(){
        return $this->belongsToMany('App\UserPermission', 'user_role_has_user_permissions', 'id_rol', 'id_permission')
            ->withTimestamps();
    }

    public function users(){
        return $this->belongsToMany('App\User', 'user_has_roles', 'id_rol', 'id_user')
            ->withTimestamps();
    }
}
