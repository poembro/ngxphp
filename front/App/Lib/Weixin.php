<?php
namespace App\Lib;
/**
 * 微信接口类
 * @author Aiden
 * @copyright 
 */  
class Weixin
{
  
	/**
	 * 微信登录-获取code
	 * @access public
	 * @param int null  
	 * @return string
	 */
    public function index()
    {
        file_put_contents('login.log', date("Y-m-d H:i:s", time()) . '进入登录,内容：' . $_GET['callback'] . "\n\r", FILE_APPEND);

        $_SESSION['callback'] = isset($_GET['callback']) && $_GET['callback'] != null ? $_GET['callback'] : "http://" . $_SERVER['HTTP_HOST'] . "/index.php/app/home/index";
        //标识用户是进入登录状态

        // header("Location:index.php/admin/login/login");
        //$this->load->view('welcome_message');
        //首先判断用户是否登录

        if ($_SESSION['mid']) {
            //不作操作直接跳走去首页
            header("location:" . $_SESSION['callback']);
            //header("location:http://".$_SERVER['HTTP_HOST']."/index.php/app/home/index");
            return true;
        }

        $redirect_url = "http://" . $_SERVER['HTTP_HOST'] . "/index.php/welcome/getAccessToken";

        $state = "J5s6W8ed51QW";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . APPID . "&redirect_uri=" . $redirect_url . "&response_type=code&scope=snsapi_base&state=" . $state . "#wechat_redirect ";

        header("location:$url");
    }


    //通过code换取网页授权access_token
    public function getAccessToken()
    {

        if ($_GET['code'] && $_GET['state'] == "J5s6W8ed51QW") {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . APPID . "&secret=" . APPSECRET . "&code=" . $_GET['code'] . "&grant_type=authorization_code";
            $res = $this->curl_get($url);
            $access_token = $res->access_token;
            if ($access_token) {
                file_put_contents('application/logs/login/login.log', date("Y-m-d H:i:s", time()) . '进入获取token,token：' . json_encode($access_token) . "\n\r", FILE_APPEND);

                $res->createtime = time();
                //吧token 存入文本文件，避免重复操作数据库
//                   //var_dump($res);
//                    $re=file_put_contents("public/app/access_token.txt",json_encode($res));
//
//                    if(!$re){
////                        echo "文件写入错误";
////                        exit;
//                        $this->index();//重新来一遍试试
//                    }
                $_SESSION['openid1'] = $res->openid;
                $re = $this->userinfo($access_token, $res->openid);//获取用户信息
                if ($re) {
                    file_put_contents('application/logs/login/login.log', date("Y-m-d H:i:s", time()) . '返回用户信息,内容：' . json_encode($re) . "\n\r", FILE_APPEND);
                    //  header("location:" . $_SESSION['callback']);
                } else {
                    echo "拉取用户信息失败，请刷新试试。";
                    exit;
                }

            } else {
                echo "获取失败，请刷新试试。";
                exit;
            }

        } else {
            exit('用户不允许授权！');
        }
    }

    //拉取用户信息
    public function userinfo($token, $openid)
    {
        file_put_contents('application/logs/login/login.log', date("Y-m-d H:i:s", time()) . '进入拉取用户信息,参数内容：' . json_encode($token) . "\n\r", FILE_APPEND);

        $_SESSION['openid1'] = $openid ? $openid : $_SESSION['openid1'];
        if ($_SESSION['openid1']) {
            file_put_contents('application/logs/login/login.log', date("Y-m-d H:i:s", time()) . '进入拉取用户信息,存在openid：' . json_encode($openid) . "\n\r", FILE_APPEND);

            //查询数据库
            $this->load->model('member/member_model');
            $re = $this->member_model->get_one('id', 'openid1="' . $_SESSION['openid1'] . '"');
            if ($re) {
                file_put_contents('application/logs/login/login.log', date("Y-m-d H:i:s", time()) . '进入拉取用户信息,存在用户：' . json_encode($re) . "\n\r", FILE_APPEND);

                $_SESSION['mid'] = $re['id'];
                //跳到首页
                header("location:" . $_SESSION['callback']);
                //header("location:http://weixin.xysh.com/index.php/app/home/index");
                exit;
            }
            //$token=$this->getToken();
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $token . "&openid=" . $_SESSION['openid1'] . "&lang=zh_CN ";
            $res2 = $this->curl_get($url);
            file_put_contents('application/logs/login/login.log', date("Y-m-d H:i:s", time()) . '进入拉取用户信息,返回内容：' . json_encode($res2) . "\n\r", FILE_APPEND);

            //注册信息
            if ($res2) {
                //数据重组
                $data = array(
                    'openid1' => $res2->openid,
                    'nickname' => $res2->nickname,
                    'headimgurl' => $res2->headimgurl,
                    'createtime' => time()
                );
               // $sql="INSERT INTO `hd_member` (`openid1`, `nickname`, `headimgurl`, `createtime`) VALUES ($res2->openid,$res2->nickname,$res2->headimgurl,time())";

                file_put_contents('application/logs/login/loginsql.log', date("Y-m-d H:i:s", time()) . '插入用户数据：' . json_encode($data) . "\n\r", FILE_APPEND);

                //插入数据库
                $this->load->model('member/member_model');
                $re = $this->member_model->add($data);//返回插入的id
                file_put_contents('application/logs/login/loginsql.log', date("Y-m-d H:i:s", time()) . '插入用户，sql：' . $this->db->last_query() . "\n\r", FILE_APPEND);
                file_put_contents('application/logs/login/loginsql.log', date("Y-m-d H:i:s", time()) . '插入用户，返回：' . json_encode($re) . "\n\r", FILE_APPEND);

                if ($re) {
                    $_SESSION['mid'] = $re;
                    //这里应该是调到首页去
                    //header(location:.getenv("HTTP_REFERER"));
                    header("location:" . $_SESSION['callback']);
                    //header("location:http://weixin.xysh.com/index.php/app/home/index");
                    return true;
                } else {
                    return $res2;
                }
            } else {
                echo "微信接口没有获取到用户信息";
                exit;
            }


        } else {
            echo "没有得到用户唯一身份。";
            exit;
            // return false;
        }
    }


    function curl_get($url)
    {
        $ch = curl_init();
        // 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        if (stripos($url, "https://") !== FALSE) {
            //curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        }
        // 执行并获取HTML文档内容
        $output = curl_exec($ch);
        // 释放curl句柄
        $err_code = curl_errno($ch);

        curl_close($ch);

        return json_decode($output);
    }


}
