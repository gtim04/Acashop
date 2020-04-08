<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable = ['name', 'price', 'user_id', 'stock', 'description', 'image'];
	
    public function category(){
        return $this->belongsToMany(Category::class)->using('App\CategoryProduct')->withTimestamps();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function order(){
        return $this->belongsToMany(Order::class)->using('App\OrderProduct')->withTimestamps()->withPivot('quantity', 'totalProduct');
    }
}
