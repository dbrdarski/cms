<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Subscription extends Model
{
    protected $table = 'course_student';

    protected $fillable = [
        'grade',
        'rating'
    ];

    public function gradeStudent($grade)
    {
        $t = $this->create([
            'grade' => $grade
        ]);
        return $this;
    }
    public function rateCourse($rating)
    {
        $t = $this->create([
            'rating' => $rating
        ]);
        return $this;
    }
}