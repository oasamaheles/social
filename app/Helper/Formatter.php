<?php

namespace App\Helper;

class Formatter
{

    /**
     * @param $data
     * @param $message
     * @return array|false
     */
    public static function formatter($data,$message){
        if (isset($data)){
            $data = ['status'=>true,'messages'=>$message,'data'=>$data];
        } else {
            $data = ['status'=>false,'messages'=>'Data not Found'];
        }
        return response()->json($data);
    }
}
