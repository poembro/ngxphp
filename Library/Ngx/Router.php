<?php 
/**
 * @Copyright (C),
 * @Author poembro
 * @Date: 2017-11-08 12:37:46
 * @Description Router  请求参数类
 */
namespace Ngx;

class Router
{
    protected $_permitted_uri_chars = 'a-z 0-9~%.:_\-';
    public $uri_string = ''; 
    public $segments = []; 
    private static $_instance; 
	public $suffix = '.html'; 

    public static function getInstance()
    {
        if (! self::$_instance)
        {
            self::$_instance = new self();
        }
    
        return self::$_instance;
	}
	
	public function getRule()
	{
		$uri = '';
        if (PHP_SAPI === 'cli')
        {
			$args = array_slice($_SERVER['argv'], 1);
			$uri =  $args ? implode('/', $args) : ''; 
        }
        else
        {
            $uri = $this->_parse_request_uri();
        }
 
        return $this->_set_uri_string($uri) ;
	}
	
	public function setRule($uri)
	{
        return $this->_set_uri_string($uri) ;
	}
    
	protected function _parse_request_uri()
	{
		if ( ! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']))
		{
			return '';
		}

		// parse_url() returns false if no host is present, but the path or query string
		// contains a colon followed by a number
		$uri = parse_url('http://dummy'.$_SERVER['REQUEST_URI']);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path']) ? $uri['path'] : '';

		if (isset($_SERVER['SCRIPT_NAME'][0]))
		{
			if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
			{
				$uri = (string) substr($uri, strlen($_SERVER['SCRIPT_NAME']));
			}
			elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
			{
				$uri = (string) substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
		}

		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0)
		{
			$query = explode('?', $query, 2);
			$uri = $query[0];
			$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
		}
		else
		{
			$_SERVER['QUERY_STRING'] = $query;
		}

		parse_str($_SERVER['QUERY_STRING'], $_GET);

		if ($uri === '/' OR $uri === '')
		{
			return '/';
		}

		return $this->_remove_relative_directory($uri);
	}
	
	
	protected function _remove_relative_directory($uri)
	{
		$uris = array();
		$tok = strtok($uri, '/');
		while ($tok !== FALSE)
		{
			if (( ! empty($tok) OR $tok === '0') && $tok !== '..')
			{
				$uris[] = $tok;
			}
			$tok = strtok('/');
		}

		return implode('/', $uris);
	}


	protected function _set_uri_string($str)
	{
		$str = $this->remove_invisible_characters($str, FALSE);
		$this->uri_string = trim($str, '/'); 
		if ($this->uri_string !== '')
		{
			if (($this->suffix = '.html') !== '')
			{
				$slen = strlen($this->suffix); 
				if (substr($this->uri_string, -$slen) === $this->suffix)
				{
					$this->uri_string = substr($this->uri_string, 0, -$slen);
				}
			}

			$this->segments[0] = NULL;
			foreach (explode('/', trim($this->uri_string, '/')) as $val)
			{
				$val = trim($val); 
				$this->filter_uri($val);

				if ($val !== '')
				{
					$this->segments[] = $val;
				}
			}

			unset($this->segments[0]);
        }

        return $this->segments;
    }
 
 
	public function filter_uri(&$str)
	{
		if ( ! empty($str) && ! preg_match('/^[' . $this->_permitted_uri_chars . ']+$/i' . (UTF8_ENABLED ? 'u' : ''), $str))
		{
			throw new \Exception('The URI you submitted has disallowed characters.', 400);
		}
	}


	public function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

		return $str;
    } 
}

