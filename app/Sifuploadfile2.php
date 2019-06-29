<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sifuploadfile2 extends Model
{
    //
    protected $fillable = [
        'user_id', 'file_name', 'date_uploaded'
    ];
    protected $table = "sifuploadfile";
    public $timestamps = false;

}
