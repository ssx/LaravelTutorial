<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $incrementing = false;

    public function leader()
    {
        return $this->belongsTo(
           Tutor::class, 'lead_tutor_id'
        );
    }

    public function tutorials()
    {
        return $this->hasMany(Tutorial::class);
    }

    public function getUniqueTutorsAttribute()
    {
        $tutors = collect([$this->leader]);
        foreach ($this->tutorials as $tutorial) {
            foreach ($tutorial->tutors as $tutor) {
               $tutors->add($tutor);
            }
        }

        return $tutors->uniqueStrict('id');
    }
}
