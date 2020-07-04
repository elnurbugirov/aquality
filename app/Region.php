<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name'
    ];

    public function users(){
        // return $this->belongsTo(User::class);
        return $this->hasMany(User::class);
    }
}
