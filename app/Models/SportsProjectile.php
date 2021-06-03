<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsProjectile extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function exercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Exercise');
    }
}
