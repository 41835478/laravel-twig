<?php
/**
 * Created by PhpStorm.
 * User: river
 * Date: 2017/12/15
 * Time: 12:06
 */

namespace App\Http\Helper;

use Illuminate\Http\Request;
use Storage;
use File;

class Upload
{
    public static $upload_url = "http://10.10.11.121:8080";

    public static function upload_file(Request $request,$business_id=null){

        if(!empty($request->file('file'))){
            $file = $request->file('file');//获取文件
            $fileName = md5(time() . rand(0, 10000)) . '.' . $file->getClientOriginalName();//随机名称+获取客户的原始名称
            if (!empty($business_id)){
                $directory = "images/{$business_id}/";
            }else{
                $directory = "images/".date("YmdHis")."/";
            }

            $put_check = Storage::disk("uploads")->put($directory . $fileName, File::get($file));//通过Storage put方法存储   File::get获取到的是文件内容
            if ($put_check){
                return self::$upload_url."/".$directory.$fileName;
            }
            return false;
        }

    }
}