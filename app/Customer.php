<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $fillable =['id', 'username', 'email', 'password' , 'token'];
    public $timestamps = true;
}
