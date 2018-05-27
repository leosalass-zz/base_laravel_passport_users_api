<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserState extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_states';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'short_description'];

    public function users(){
        return $this->hasMany('App\User', 'id_state', 'id');
    }
}
