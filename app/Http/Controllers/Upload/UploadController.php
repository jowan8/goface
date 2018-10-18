<?php
namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller{

    /**
     * 上传文件
     */
    public function index(){

        return view('filetest');
    }

    public function upload_file( Request $request )
    {
        $file = $request->file('goface_img');

        //$url_path = storage_path().'/upload/'.date('Ym').'/'.date('d').'/';
        $base_url='/upload/tmp/';
        $url_path=public_path().$base_url;
        $is_dir=make_directory($url_path);

        if($is_dir){
            $rule = ['jpg', 'png', 'gif','pdf'];
            if ($file->isValid()) {
                $clientName = $file->getClientOriginalName();
                //$tmpName = $file->getFileName();
                //$realPath = $file->getRealPath();
                $entension = $file->getClientOriginalExtension();
                if (!in_array($entension, $rule)) {
                    return jsonout(400,'no picture');
                }
                $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
                $path = $file->move($url_path, $newName);
                if($path){
                    //$namePath = $url_path . $newName;
                    return jsonout(200,'success',['url'=>$base_url.$newName]);
                }else{
                    return jsonout(400,'failed');
                }

            }
            return false;
        }
        return false;
    }

}
