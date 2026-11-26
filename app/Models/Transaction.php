<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id','showroom_id','quantity','total_price','status'];

    public function buyer(){
        return $this->belongsTo(user::class,'user_id');
    }

    public function car(){
        return $this->belongsTo(Showroom::class,'showroom_id');
    }
}
