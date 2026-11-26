<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable implements JWTSubject
{

    use HasFactory, Notifiable;

    //for login
    protected $fillable =[
        'name',
        'email',
        'password',
    ];

    protected $hidden =[
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function GetJWTIdentifier(){

        return $this->getKey();
    }

    public function GetJWTCustomClaims(): array{
        return [];
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function ownedCars()
    {
        return $this->belongsToMany(Showroom::class, 'transaction')
            ->withpivot('quantity', 'total_price', 'status')
            ->withTimeStamps();
    }
}
