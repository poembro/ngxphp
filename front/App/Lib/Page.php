<?php
namespace App\Lib;
/**
 * 分页类
 * @author Aiden
 * @copyright
 *
 */ 
class Page
{

	/**
	 * 当前网址
	 * @var string
	 * @access private
	 */
	private static $_url;

	/**
	 * 得到分页信息
	 * @access public
	 * @param int $total 总记录数
	 * @param int $pageSize 每页记录数
	 * @param int $page 当前页数
	 * @return string
	 */
	public static function get($total, $pageSize = 25, $url = '', $page = 0, $maxPagecount = 0)
	{  
		$nowUrl = $_SERVER["REQUEST_URI"];
		$url = empty ( $url ) ? $nowUrl : str_replace ( '%s', '{p}', $url );
		if (empty($page))
		{
			$page = intval($_GET['page']);
		}
		$info = self::showPage($page, $total, $pageSize, $url, $maxPagecount);
		$limit = $info['limit'];
		unset($info['limit']);
		return array (
			'page' => $info,
			'limit' => $limit);
	}

	/**
	 * AJAX分页
	 * @access public    
	 * @return string
	 */
	public static function ajaxPage($total, $pageSize = 25, $back, $page = 0, $maxPagecount = 0)
	{
		$info = self::showPage($page, $total, $pageSize, "{p}", $maxPagecount);	 
		$back = $back . '(%d)';
		$string = '';
		if ($info['pagecount'] > 1)
		{
			$string = '<a href="javascript:'.( $info['prev'] ? sprintf($back, (int)$info['prev']) : 'javascript:;' ).'">上一页</a>';
			foreach ($info['num'] as $p => $url)
			{
				$class = $p == $info['page'] ? 'class="on"' : '';
				$string .= '<a '.$class.' href="javascript:'. sprintf($back, $p) .'">'.$p.'</a>';
			}		
			
			$string .= '<a href="javascript:'.( $info['next'] ? sprintf($back, (int)$info['next']) : 'javascript:;' ).'">下一页</a>';
		}

		$limit = $info['limit'];
		unset($info['limit']);
		return array (
				'page' => $string,
				'limit' => $limit);
	}
	
	/**
	 * 显示分类
	 * @access public
	 * @param int $page 当前页灵敏
	 * @param int $total 总记录数
	 * @param int $pageSize 每页记录数
	 * @param string $url 当前网址
	 * @return void
	 */
	public static function showPage($page, $total, $pageSize, $url, $maxPage = 0)
	{
		$total = (int)$total;
		$page = (int)$page;
		$pageSize = (int)$pageSize;
		$pageCount = 0;
		$page = $page;
		$pageCount = $total <= $pageSize ? 1 : ceil($total / $pageSize);
		if ($maxPage)
		{
			$pageCount = min($pageCount, $maxPage);
		}
		$page = $page > $pageCount ? $pageCount : $page;
		$page = max(1, $page);
		$url = self::_getUrl($url);
		self::$_url = $url;
		$info['page'] = $page;
		$info['pagecount'] = $pageCount;
		$info['total'] = $total;
		$info['url'] = $url;
		$offset = ($page - 1) * $pageSize;
		$info['limit'] = $offset . ',' . $pageSize;
		$info['home'] = self::_getPage(1);
		$info['prev'] = $page == 1 ? '' : self::_getPage($page - 1);
		$info['end'] = self::_getPage($pageCount);
		$info['next'] = $page == $pageCount ? '' : self::_getPage($page + 1);
		$num = 3;
		$startPage = $page - $num > 0 ? $page - 3 : 1;
		if ($page < $num)
		{
			$startPage = 1;
			$endPage = $num * 2;
		}
		elseif ($page + $num >= $pageCount)
		{
			$endPage = $pageCount;
			$startPage = $page - ($num * 2 - ($pageCount - $page));
			$startPage = max(1, $startPage);
		}
		else
		{
			$endPage = $page + 3;
		}
		$endPage = min($pageCount, $endPage);
		for ($startPage; $startPage <= $endPage; $startPage++)
		{
			$info['num'][$startPage] = self::_getPage($startPage);
		}
		return $info;
	}

	/**
	 * 得到当前网址
	 * @access public
	 * @return string
	 */
	private static function _getUrl($url)
	{ 
		if (strpos($url, '{p}') == false)
		{ 
			$parseUrl = parse_url($url);  
			$urlQuery = isset($parseUrl['query']) ? $parseUrl['query'] : '';
			if (! empty($urlQuery))
			{ 
				$urlQuery = preg_replace("/(^|&)page=([^&])*$/", "", $urlQuery);
				$url = str_replace($parseUrl['query'], $urlQuery, $url);
				$url .= $urlQuery ? "&page" : "page";
			}
			else
			{    
				$url .= "?page";
			} 
		 	$url .= '={p}';
		}
		return $url;
	}

	/**
	 * 替换%s为页数，从而得到完整的网址
	 * @access public
	 * @param int $page 页数
	 * @return string
	 */
	private static function _getPage($page)
	{
		$url = str_replace('{p}', $page, self::$_url);
		return $url;
	}

}