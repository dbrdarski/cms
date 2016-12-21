<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'type');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role','role_id', 'permission_id');
    }
    public function addRole($p){
        return $this->create([
            'name' => $p['name'],
            'description' => isset($p['description']) ? $p['description'] : ""
        ]);
    }    
}
