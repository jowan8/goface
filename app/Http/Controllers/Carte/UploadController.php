<?php
namespace App\Http\Controllers\Carte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller{

    /**
     * 上传文件
    */
    public function index(){

        return view('filetest');
    }

    /*
     * 上传文件统一接口，所有文件置于tmp文件下，数据完全提交之后放置到指定目录
     */
    public function upload_file( Request $request )
    {

        //获取文件
        $file = $request->file('goface_img');
        //$url_path = storage_path().'/uploads/'.date('Ym').'/'.date('d').'/';
        if ($file) {
            //创建指定目录
            $url_path = storage_path() . '/tmp/';
            $is_dir = make_directory($url_path);

            if ($is_dir) {
                $rule = ['jpg', 'png', 'gif', 'pdf'];
                if ($file->isValid()) {

                    $clientName = $file->getClientOriginalName();

                    $entension = $file->getClientOriginalExtension();

                    if (!in_array($entension, $rule)) {
                        jsonout(400,'invalid file');
                    }
                    $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;

                    $path = $file->move($url_path, $newName);

                    if ($path) {
                        //$namePath = $url_path . $newName;
                        jsonout(200,'upload success',['file_name'=>$newName]);
                    } else {
                        jsonout(400,'upload failed');
                    }

                }
                return false;
            }

        }
        jsonout(400,'get file failed');
    }

}

