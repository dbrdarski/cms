<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'description'
    ];

    public function roles(){
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id','role_id');
    }
    public function add($name, $description){
        return $this->create([
            'name' => $name,
            'description' => isset($description) ? $description : ""
        ]);
    }    
}
