<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class  Product extends Model
{
    protected $fillable = [
        'name', 'price', 'quantity'
    ];

    public function images(){
        return $this->hasMany(Image::class);
    }
}
