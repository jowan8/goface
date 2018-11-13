<?php
/**
 * Created by phpstome.
 * User: PANCOOL
 * Date: 2018/1/12
 * Time: 16:10
 */
/*
  * 验证身份证的方法  by jowan
  * @param $id 身份证号码
  * @return boolean
  */
if(!function_exists('is_idcard')){

    function is_idcard($id)
    {
        $id = strtoupper($id);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if (!preg_match($regx, $id)) {
            return false;
        }
        if (15 == strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) {
                return false;
            } else {
                return true;
            }
        } else {
            //检查18位
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) //检查生日日期是否正确
            {
                return false;
            } else {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ($i = 0; $i < 17; $i++) {
                    $b = (int)$id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id, 17, 1)) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
}
/*
 * 输出json数据的方法  by JOWAN
 * @param $code 返回状态码 int 200-成功,400-客户端请求有误,401-用户未授权,402-第三方错误,403-内容禁止访问(已授权),429-频繁请求,500-服务器错误
 * @param $message 返回状态信息 string
 * @param $data (需要转化json的)数组数据 array()
 * @param $status 需要返回的额外的字段(用来区分多种状态)
 * @return string
 */
if(!function_exists('jsonout')) {

    function jsonout($code, $message, $data = null, $status = null)
    {
        header('content-type:text/json');

        if (!in_array($code, [200, 400, 401, 402, 403, 429, 500])) {
            echo json_encode(array('code' => 500, 'message' => 'unknow code '));
            exit;
        }

        if ($code !== 200) {
            echo json_encode(array('code' => $code, 'message' => $message));
            exit;
        } else {
            if (is_null($status)) {//没有有其他的状态
                if (is_null($data)) {
                    echo json_encode(array('code' => $code, 'message' => $message));
                    exit;
                } else {
                    echo json_encode(array('code' => $code, 'message' => $message, 'data' => $data));
                    exit;
                }
            } else {
                if (is_null($data)) {
                    echo json_encode(array('code' => $code, 'message' => $message, 'status' => $status));
                    exit;
                } else {
                    echo json_encode(array('code' => $code, 'message' => $message, 'status' => $status, 'data' => $data));
                    exit;
                }
            }
        }
    }
}
/*
 * 日志记录  by JOWAN
 * @param $module string 出错所在的模块
 * @param $message string 出错的信息
 * @param $error_level int 错误等级 emergency->800、alert->700、critical->600、error->500、warning->400、notice->300、info->200 和 debug->100
 * @param $error array 出错的用户信息
 * @return string
 */
if(!function_exists('logs')) {

    function logs($module, $message, $error_level, $error = array())
    {
        if (!is_string($module) || empty($module)) {
            return '模块名不合法';
        }

        if (!is_string($message) || empty($message)) {
            return '错误信息不完整';
        }

        $error_level_arr = [100, 200, 300, 400, 500, 600, 700, 800];
        if (!in_array($error_level, $error_level_arr)) {
            return '错误等级有误';
        }
        $log = new \Monolog\Logger($module);
        $log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path() . '/logs/' . $module . '/' . date('Y-m-d') . '.log'), $error_level);//\Monolog\Logger::WARNING
        switch ($error_level) {
            case 100;
                $log->debug($message, $error);
                return true;
                break;
            case 200;
                $log->info($message, $error);
                return true;
                break;
            case 300;
                $log->notice($message, $error);
                return true;
                break;
            case 400;
                $log->warning($message, $error);
                return true;
                break;
            case 500;
                $log->error($message, $error);
                return true;
                break;
            case 600;
                $log->critical($message, $error);
                return true;
                break;
            case 700;
                $log->alert($message, $error);
                return true;
                break;
            case 800;
                $log->emergency($message, $error);
                return true;
                break;
        }
    }
}
/*
 * 判断邮箱是否合法
 * @param $email 待判断的邮箱地址
 * @return boolean true or false
 */
if(!function_exists('is_valid_email')){
    function is_valid_email($email)
    {
        if(!is_string($email)){
            return false;
        }
        $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        if(preg_match($pattern,$email)){
            return true;
        } else{
            return false;
        }
    }
}

/*
 * 判断手机号是否合法
 * @param $mobile 待判断的手机
 * @return boolean true or false
 */
if(!function_exists('is_valid_phone')){
    function is_valid_phone($mobile)
    {
        if(!is_numeric($mobile)){
            return false;
        }
        $pattern="/^1[3456789]{1}\d{9}$/";
        if(preg_match($pattern,$mobile)){
            return true;
        } else{
            return false;
        }
    }
}

if(!function_exists('clean_xss')){
    function clean_xss(&$string, $low = False)
    {

        if (! is_array ( $string ))
        {
            $string = trim ( $string );
            $string = strip_tags ( $string );
            $string = htmlspecialchars ( $string );

            if ($low)
            {
                return True;
            }
            $string = str_replace ( array ('"', "\\", "'", "/", "..", "../", "./", "//" ), '', $string );
            $no = '/%0[0-8bcef]/';
            $string = preg_replace ( $no, '', $string );
            $no = '/%1[0-9a-f]/';
            $string = preg_replace ( $no, '', $string );
            $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
            $string = preg_replace ( $no, '', $string );
            return True;
        }
        $keys = array_keys ( $string );
        foreach ( $keys as $key )
        {
            clean_xss ( $string [$key] );
        }
    }
}


/*
 * 移动文件到指定目录
 * @param $old_filename 旧文件的路径和文件名
 * @param $new_filename 新文件的路径和文件名
 * @return boolean true or false
 */
if(!function_exists('move_file')){

    function move_file($old_file_path_and_name,$new_file_path)
    {
        //旧文件是不是存在
        if(!is_file($old_file_path_and_name)){
            return false;
        }

        $old_filename=substr($old_file_path_and_name,strrpos($old_file_path_and_name,'/')+1);
        if(!is_dir($new_file_path) ){

            make_directory($new_file_path);
        }

        $new_filename=$new_file_path.$old_filename;

         if(rename($old_file_path_and_name,$new_filename)){
             return $old_filename;
         }
         return false;
    }
}

/*
 *递归的创建目录
 */
if(!function_exists('make_directory')){

    function make_directory( $dir ){

        return  is_dir ( $dir ) or make_directory(dirname( $dir )) and  mkdir ( $dir , 0777);

    }

}


