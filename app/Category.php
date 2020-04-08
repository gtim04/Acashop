<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function product(){
        return $this->belongsToMany(Product::class)->using('App\CategoryProduct');
    }
}
