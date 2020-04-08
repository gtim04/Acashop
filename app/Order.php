<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = ['code', 'user_id', 'total'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsToMany(Product::class)->using('App\OrderProduct')->withTimestamps()->withPivot('quantity', 'totalProduct');
    }
}
