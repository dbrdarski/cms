<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model as Model;
use \Core\Containers\Environment;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'level',
        'color',
        'image',
        'video',
        'active'
    ];

    public function getColorAttribute($value)
    {
        return Environment::getGlobal('colors')[$value];
    }
    public function getLevelAttribute($value)
    {
        return Environment::getGlobal('levels')[$value];
    }

    public function setLevelAttribute($value)
    {
        $this->attributes['level'] = array_search($value, Environment::getGlobal('levels'));
    }

    public function setColorAttribute($value)
    {
        $this->attributes['color'] = array_search($value, Environment::getGlobal('colors'));
    }    

    public function users()
    {
        return $this->belongsTo(User::class, 'lecturer_id', 'id');
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'student_id', 'id');
    }
    public function lessions()
    {
        return $this->hasMany(Lession::class, 'course_id', 'id');
    }
    public function publish()
    {
        $this->active = true;
        return $this;
    }
    public function unpublish()
    {
        $this->active = false;
        return $this;
    }
    // public function subscriptions()
    // {
    //     return $this->hasMany(Subscription::class);
    // }

    public function addCourse($title, $slug)
    {
        $t = $this->create([
            'title' => $title,            
            'slug' => $slug
        ]);

        // var_dump($t->id);
        return $t->id;
    }

    public function createLession($p){
        return $this->lessions()->create([
            'title' => $p['title'],
            'lession_slug' => $p['lession_slug'],
            'description' => isset($p['description']) ? $p['description'] : "",
            'video' => isset($p['video']) ? $p['video'] : "",
        ]);
    }    
}
