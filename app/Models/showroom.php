<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showroom extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'description'

    ];

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }


}
