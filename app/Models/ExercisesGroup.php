<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExercisesGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function workout(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Workout');
    }

    public function exercises(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Exercise', 'exercises_group_exercises', 'exercise_group_id', 'exercise_id')->withPivot('id', 'approaches', 'repetition', 'weight');
    }
}
