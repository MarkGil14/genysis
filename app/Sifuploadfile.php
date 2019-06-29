<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sifuploadfile extends Model
{
    //
    protected $fillable = [
        'user_id', 'file_name'
    ];
    protected $table = "sifuploadfile";
    public $timestamps = false;

    public static function uploadCSVFile($csvFile)
    {            
        $arrFile = [];
        if(is_array($csvFile)){
            foreach($csvFile as $file){
                $file_path = self::uploadFile($file,'uploads');
                $arrFile[] = [
                    'file_name' => $file_path,
                    'user_id' => '1'
                ];
            }
        }else {
            $file_path = self::uploadFile($csvFile,'uploads');
            $arrFile[] = [
                'file_name' => $file_path,
                'user_id' => '1'
            ];
        }
            self::insert($arrFile);
    }


    public static function uploadFile($file, $path)
    {
        $file_path = $file;
        $file_path_new = time() . $file_path->getClientOriginalName();
        $file_path->move($path, $file_path_new);
        return $path . $file_path_new;
    }


}
