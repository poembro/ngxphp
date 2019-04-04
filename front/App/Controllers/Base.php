<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description  Auth 传统的php框架思维中的控制器
 */
namespace App\Controllers;

use App\Lib\Mcrypt;

class Base 
{ 
    protected $code = [];

    protected $me = []; 
    
    public function __construct($req, $res)
    {
        $request_url = $req->get('PATH_INFO');
    }
     
    /**
     * @desc 格式化输出json
     * @access public
     * @param int $ret 状态码 0 - 成功 1 - 失败
     * @param string $msg 状态说明信息
     * @param array $data 附加数据
     */
    protected function outJson($code  = 0, $msg = null, $data = array())
    {
        if (empty($this->code))
        {
            $this->code = include_once APPLICATION_PATH . 'Config/Code.php';
        }

        $codes = $this->code['data'];
        if (!isset($codes[$code]))
        {
            $code  = -1;
        }
        if (is_null($msg))
        {
            $msg = $codes[$code];
        }
        $data = array(
            'ret' => (string)$code,
            'msg' => $msg,
            'data' => $data,
            'timestamp' => time(),
        );
        
        $callback = isset($_GET["jsonpCallback"]) ? $_GET["jsonpCallback"] : '';
        $callback = trim($callback);
        $string = ($callback != '') ? "try{\n" . $callback . '(' . json_encode($data, JSON_UNESCAPED_UNICODE) . ");\n}catch(e){}\n" : json_encode($data, JSON_UNESCAPED_UNICODE);
        echo $string;
        exit();
    }
    
    public function __destruct()
    {
        if (\Ngx\Config::get('ext')['debug'])
        {
            print_r(\Ngx\Log::getQuery());
        }
    }
}
