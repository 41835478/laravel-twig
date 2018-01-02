<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Models\BusinessImages;
use Storage;
use File;
use App\Http\Helper\Helper;

class ImagesController extends Controller
{
    public $upload_url = "http://10.10.11.121:8080";


    public function index(Request $request){
        $per_pagesize = $request->input("per_pagesize", 1);
        $page = $request->input("page", 3);
        $business_id = $request->business_info->id;
        $params["business_id"] = $business_id;
        $offset = ($page - 1);
        if ($offset <= 0) {
            $offset = 0;
        }
        $data = BusinessImages::where(function ($query) use ($params) {
            $query->where("business_id", $params["business_id"]);
        })->orderby("id", "asc")->select("id","image_url")->offset($offset)->paginate($per_pagesize);
        if ($data->isEmpty()){
            $result= [] ;
        }else{
            $result = Helper::pageFormat($data);
        }
        return response()->json(["status" => true, "data" => $result]);

    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');//获取文件
        $fileName = md5(time() . rand(0, 10000)) . '.' . $file->getClientOriginalName();//随机名称+获取客户的原始名称
        $business_id = $request->business_info->id;
        $directory = "images/{$business_id}/";
        $put_check = Storage::disk("uploads")->put($directory . $fileName, File::get($file));//通过Storage put方法存储   File::get获取到的是文件内容
        if ($put_check) {
            $image_url = $this->upload_url . "/" . $directory . $fileName;
            BusinessImages::insert([
                'business_id' => $business_id,
                'image_url' => $image_url
            ]);
            return response()->json(["status" => true,"data"=>["image_url"=>$image_url]]);
        }else{
            return response()->json(["status" => false,"errors"=>"There was an error uploading. Please try again later"]);
        }
    }





}
