<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model as Model;
use \Core\Containers\Environment;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'type',
        'image',
        'description',
        'state'
    ];

    // private $userRoles = ['admin', 'lecturer', 'student'];


    public function courses()
    {
        return $this->hasMany(Course::class, 'lecturer_id');
    }
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'type');
    }
    public function lecturer()
    {
        $this->type = 2;
        return $this;
    }
    public function admin()
    {
        $this->type = 1;
        return $this;
    }
    public function student()
    {
        $this->type = 3;
        return $this;
    }
    public function getStateAttribute($value)
    {
        return Environment::getGlobal('account_states')[$value];
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = array_search($value, Environment::getGlobal('account_states'));
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    // public function setPassword($password)
    // {
    //     $this->update([
    //         'password' => password_hash($password, PASSWORD_DEFAULT)
    //     ]);
    // }

    public function setDescription($description)
    {
        $this->update([
            'description' => $description
        ]);
    }
    public function setImage($image)
    {
        $this->update([
            'image' => $image
        ]);
    }

    public function add($username, $email, $fname, $lname, $password, $image = '', $description = '')
    {
        $user = $this->create([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'type' => $this->type,
            'first_name' => $fname,
            'last_name' => $lname,
            'image' => $image,
            'description' => $description
        ]);
        return $user['attributes']['id'];
    }

    public function createCourse($p){
        return $this->courses()->create([
            'title' => $p['title'],
            'slug' => $p['slug'],
            'description' => isset($p['description']) ? $p['description'] : "",
            'video' => isset($p['video']) ? $p['video'] : "",
            'color' => isset($p['color']) ? $p['color'] : 0,
            'image' => isset($p['image']) ? $p['image'] : "",
            'level' => isset($p['level']) ? $p['level'] : 0
        ]);
    }    
}
