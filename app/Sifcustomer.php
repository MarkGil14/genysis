<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sifcustomer extends Model
{
    protected $fillable = [
        'AccountId', 'Name', 'Channel', 'Street', 'City'
    ];
    protected $table = "sifcustomer";
    public $timestamps = false;
    protected $primaryKey = "AccountId";

}
