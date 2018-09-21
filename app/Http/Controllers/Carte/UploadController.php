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

    public function upload_file( Request $request )
    {

        $file = $request->file('file1');

        //$url_path = storage_path().'/uploads/'.date('Ym').'/'.date('d').'/';

        $url_path=storage_path().'/uploads/tmp/';
        $is_dir=make_directory($url_path);

        if($is_dir){
            $rule = ['jpg', 'png', 'gif','pdf'];
            if ($file->isValid()) {
                $clientName = $file->getClientOriginalName();
                //$tmpName = $file->getFileName();
                //$realPath = $file->getRealPath();
                $entension = $file->getClientOriginalExtension();
                if (!in_array($entension, $rule)) {
                    return '格式不合法';
                }
                $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
                $path = $file->move($url_path, $newName);
                if($path){
                    $namePath = $url_path . $newName;
                    return $namePath;
                }else{
                    return false;
                }

            }
            return false;
        }
        return false;
    }

}

