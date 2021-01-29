<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Request 请求参数类
 */
namespace Ngx;

class Request
{
    private $_conf = [];
    private static $_instance;
 
    public static function getInstance()
    {
        if (! self::$_instance)
        {
            self::$_instance = new self();
        }
    
        return self::$_instance;
    }
    
    /**
     * Class constructor 
     * @return    void
     */
    public function __construct()
    {
        $this->_sanitize_globals();
    }

    protected function _sanitize_globals()
    {
        if (is_array($_GET))
        {
            foreach ($_GET as $key => $val)
            {
                $_GET[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
        }

        // Clean $_POST Data
        if (is_array($_POST))
        {
            foreach ($_POST as $key => $val)
            {
                $_POST[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
        }

        if (is_array($_COOKIE))
        {
            unset(
                $_COOKIE['$Version'],
                $_COOKIE['$Path'],
                $_COOKIE['$Domain']
            );

            foreach ($_COOKIE as $key => $val)
            {
                if (($cookie_key = $this->_clean_input_keys($key)) !== FALSE)
                {
                    $_COOKIE[$cookie_key] = $this->_clean_input_data($val);
                }
                else
                {
                    unset($_COOKIE[$key]);
                }
            }
        }

        // Sanitize PHP_SELF
        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']); 
    }

    protected function _clean_input_keys($str, $fatal = TRUE)
    {
        if ( ! preg_match('/^[a-z0-9:_\/|-]+$/i', $str))
        {
            if ($fatal === TRUE)
            {
                return FALSE;
            }
            else
            {
                throw new \Exception(' Disallowed Key Characters!');
            }
        }
        return $str;
    }

    protected function _clean_input_data($str)
    {
        if (is_array($str))
        {
            $new_array = [];
            foreach (array_keys($str) as $key)
            {
                $new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($str[$key]);
            }
            return $new_array;
        }
 
        // Remove control characters 
        $str = $this->remove_invisible_characters($str, FALSE);
  
        return $str;
    }

    public function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded)
        {
            $non_displayables[] = '/%0[0-8bcef]/';    // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/';    // url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127

        do
        {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }

    /**
     * @desc 获取完整URL
     * @access public
     * @param void
     * @return string
     */
    public function getRawUrl()
    {
        return '/' . Router::getInstance()->uri_string;
    }

    public function isAjax()
    {
        return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }
    
    public function getClientIp()
    { 
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        if (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        
        return $_SERVER['REMOTE_ADDR'];
    }
}
 
