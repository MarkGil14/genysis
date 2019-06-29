<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sifitem extends Model
{
    protected $fillable = [
        'MaterialCode', 'Description', 'MaterialGroup', 'ConversionId'
    ];
    protected $table = "sifitem";
    protected $primaryKey = 'MaterialCode';
    public $timestamps = false;



}
