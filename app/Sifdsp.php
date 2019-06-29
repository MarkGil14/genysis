<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sifdsp extends Model
{

    protected $fillable = [
        'AccountId', 'FirstName', 'LastName', 'AccountName'
    ];
    
    protected $table = "sifdsp";
    public $timestamps = false;


}
