<?php
/**
 * @Author  Bing Dev Team
 * @License http://opensource.org/licenses/MIT	MIT License
 * @Link    http://bingphp.com    <itbing@sina.cn>
 * @Since   Version 1.0.0
 * @Date:   2017/3/13
 * @Time:   13:45
 */

class CurlRequest
{

    private $url;           // API  地址
    private $ch;            // CURL 对象
    private $data = [];     // API  参数
    private $info = [];     // CURL 执行信息

    public function __construct($url='',$param=[])
    {
        if(!empty($url))
        {
            $this->url = $url;
        }
        if(!empty($param))
        {
            $this->data = $param;
        }

        $this->ch = curl_init();
        // 默认不输出
        curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,true);

    }


    /**
     * 调用 Get 并且用数组方式传值 类型API 接口
     *
     * @return mixed
     */
    public function arrayGet()
    {
        if($this->validateParam()) {

            $fullUrl = $this->handleParam($this->data);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST,false);
            curl_setopt($this->ch, CURLOPT_URL, $fullUrl);
        }
            return $this->exec();
    }

    /**
     * 调用 Post 并且用数组方式传值 类型API 接口
     *
     * @return mixed
     */
    public function arrayPost()
    {
        if($this->validateParam()) {

            curl_setopt($this->ch, CURLOPT_URL, $this->url);
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST,false);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
            return $this->exec();
        }
    }

    /**
     * 调用 Post 并且用json数据流方式传值 类型API 接口
     *
     * @return mixed
     */
    public function jsonPost()
    {
        if($this->validateParam()) {

            if(!is_object(json_decode($this->data))){
                return 'API parameter can not be json.';
            }
            curl_setopt($this->ch, CURLOPT_URL, $this->url);
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST,false);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($this->data)
            ));
            return $this->exec();
        }
    }

    /**
     * 获取 CURL 执行信息
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * 执行 CURL
     *
     * @return bool|mixed
     */
    private function exec()
    {
        try
        {
            $result =  curl_exec($this->ch);
            if(curl_errno($this->ch))
            {
                throw new Exception(curl_error($this->ch));
            }

            $this->info = curl_getinfo($this->ch);
            return $result;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            return false;
        }

    }

    /**
     * 效验 API 接口参数
     *
     * @return bool
     */
    private function validateParam()
    {
        try
        {
            if(empty($this->url))
            {
                throw new Exception('API url can not be null.');
            }

            if(empty($this->data))
            {
                throw new Exception('API parameter can not be null.');
            }

            return true;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 拼接参数形成完整 URL
     *
     * @param $param
     * @return string
     */
    private function handleParam($param)
    {
        if(is_array($param) && count($param) > 0)
        {
            $paramStr ='';
            foreach ($param as $key=>$value)
            {
                $paramStr .= $key.'='.$value .'&';
            }
            return $this->url .'?'. substr($paramStr,0,-1);
        }
        else
        {
            return $this->url .'?'.$param;
        }
    }


    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }
}