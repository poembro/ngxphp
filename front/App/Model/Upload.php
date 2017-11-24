<?php
/**
 * @Copyright (C), 2011-, King.
 * @Name: Upload.php
 * @Author: King
 * @Version: Beta 1.0
 * @Date: 2014-12-30下午2:52:46
 * @Description: 上传模型
 * @Class List:
 * 1.
 * @Function List:
 * 1.
 * @History:
 * <author> <time> <version > <desc>
 * King 2014-12-30下午2:52:46 Beta 1.0 第一次建立该文件
 */
namespace App\Model; 

use Lib\FileUpload;   
  
class Upload extends Nig\Data\Base
{  
	/**
	 * @desc 得到上传目录
	 * @access public
	 * @return string
	 */
	public function getUploadDir()
	{ 
		return  APPLICATION_PATH . 'public/upload/';
	}

	/**
	 * 上传图片
	 * @access public
	 * @param string $file 文件域名称
	 * @param int $thumbWidth 缩略图宽度
	 * @param int $thumbHeight 缩略图高度
	 * @return array
	 */
	public function image($file, $thumbWidth = 250, $thumbHeight = 300)
	{
		 $upload = new FileUpload();
		 $savePath = $this->getUploadDir();
		 $folder = date('Ym') . '/';
         $config = array(
	           'savePath' => $savePath . $folder, //保存目录
	           'maxSize' => 1024 * 2, //文件最大字节，以K为单位,默认为2M
	           'allowType' => 'jpg,png,gif,jpeg', //允许上传类型
         );
         $siteUrl = 'http://'.$_SERVER['HTTP_HOST'].'/upload/'; 
         
         $ret = array('ret' => 1, 'msg' => '');
         if (!$upload->uploadFile($_FILES[$file], $config))
         {
         	 $ret['msg'] = $upload->getError();
         }
         else
         {
         	 $info = $upload->getFileInfo();
         	 $ret['ret'] = 0;
         	 $ret['data'] = array(
         	 	'url' => $siteUrl . $folder  . $info['name'],
         	 	'filepath' => $info['filepath']
         	 );

         	 if ($thumbWidth && $thumbHeight)
         	 {
         	 	 $thumb = str_replace('.' . $info['type'], 's.' . $info['type'], $info['name']);
         	 	 $this->centerThumb($info['file_path'], $savePath . $folder . $thumb, $thumbWidth, $thumbHeight);
         	     $ret['data']['thumb'] = $siteUrl . $folder . '/' . $thumb;
         	     $ret['data']['url'] = $siteUrl . $folder . '/' . $thumb;
         	     unlink ( $info['file_path']);
         	 }
         }

         return $ret;
	}

	/**
	 * @desc 上传文件
	 * @access public
	 * @param string $file 文件域名称
	 * @return void
	 */
	public function file($file)
	{
		$upload = new FileUpload();
		$savePath = $this->getUploadDir();
		$folder = date('Ym') . '/';
		$config = array(
			'savePath' => $savePath . $folder, //保存目录
			'maxSize' => 1024 * 20, //文件最大字节，以K为单位,默认为2M
			'allowType' => 'rar,zip,png,jpg,gif,doc,pdf,docx,wps,xls', //允许上传类型
		);
		
		$siteUrl = 'http://'.$_SERVER['HTTP_HOST'].'/upload/'; 
		$ret = array('ret' => 1, 'msg' => '');
		if (!$upload->uploadFile($_FILES[$file], $config))
		{
			$ret['msg'] = $upload->getError();
		}
		else
		{
			$info = $upload->getFileInfo();
			$ret['ret'] = 0;
			$ret['data'] = array(
				'url' => $siteUrl . $folder . '/' . $info['name'],
				'name' => $info['name']
			);
		}

		return $ret;
	}

	/**
	 * 居中缩略
	 * @access public
	 * @param string $srcFile 原路径
	 * @param string $dstFile 缩略路径
	 * @param int $newHeight 宽度
	 * @param int $newHeight 高度
	 * @return bool
	 */
	public function centerThumb($srcFile, $dstFile, $newWidth=0 , $newHeight=0)
	{
		$vSrc_file = $srcFile;
		$vDst_file = $dstFile;
		if(file_exists($dstFile))
		{
			return true;
		}

		if($newWidth == 0 && $newHeight == 0)
		{
			return ;
		}

		if(!file_exists($srcFile))
		{
			return;
		}

		// 图像类型
		$type = pathinfo($srcFile, PATHINFO_EXTENSION);
		$support_type = array('jpg' , 'png' , 'gif', 'jpeg');
		if(!in_array($type, $support_type))
		{
			return;
		}

		$fun = $type == 'jpg' ? 'imagecreatefromjpeg' : 'imagecreatefrom' . $type;
		$srcImg = $fun($srcFile);

		$w = imagesx($srcImg);
		$h = imagesy($srcImg);
		if($newWidth == 0 )
		{
			$newWidth = $w * ($newHeight / $h);
		}

		if($newHeight == 0 )
		{
			$newHeight = $h * ($newWidth/$w);
		}

		$ratioW = 1.0 * $newWidth / $w;
		$ratioH = 1.0 * $newHeight / $h;
		$ratio = 1.0;

		// 生成的图像的高宽比原来的都小，或都大 ，原则是 取大比例放大，取大比例缩小（缩小的比例就比较小了）
		if( ($ratioW < 1 && $ratioH < 1) || ($ratioW > 1 && $ratioH > 1))
		{
			$ratio = $ratioW < $ratioH ? $ratioH : $ratioW;  // 情况一，宽度的比例比高度方向的小，按照高度的比例标准来裁剪或放大

			// 定义一个中间的临时图像，该图像的宽高比 正好满足目标要求
			$inter_w=(int)($newWidth / $ratio);
			$inter_h=(int) ($newHeight / $ratio);
			$interImg=imagecreatetruecolor($inter_w , $inter_h);
			$srcx = (int)(($w - $inter_w)/2);
			$srcy = (int)(($h - $inter_h)/2);
			imagecopy($interImg, $srcImg, 0,0,$srcx,$srcy,$inter_w,$inter_h);
			// 生成一个以最大边长度为大小的是目标图像$ratio比例的临时图像
			// 定义一个新的图像
			$newImg=imagecreatetruecolor($newWidth,$newHeight);
			imagecopyresampled($newImg,$interImg,0,0,0,0,$newWidth,$newHeight,$inter_w,$inter_h);
		} // end if 1
		// 2 目标图像 的一个边大于原图，一个边小于原图 ，先放大平普图像，然后裁剪
		// =if( ($ratioW < 1 && $ratioH > 1) || ($ratioW >1 && $ratioH <1) )
		else
		{
			$ratio=$ratioH > $ratioW ? $ratioH : $ratioW; //取比例大的那个值
			// 定义一个中间的大图像，该图像的高或宽和目标图像相等，然后对原图放大
			$inter_w = (int)($w * $ratio);
			$inter_h = (int)($h * $ratio);
			if($ratioH>$ratioW){
				$srcx = (int)(($inter_w - $w)/2);
				$srcy = 0;
			}else{
				$srcx = 0;
				$srcy = (int)(($inter_h - $h)/2);
			}
			$interImg = imagecreatetruecolor($inter_w , $inter_h);
			//将原图缩放比例后裁剪
			imagecopyresampled($interImg,$srcImg,0,0,$srcx,$srcy,$inter_w,$inter_h,$w,$h);
			// 定义一个新的图像
			$newImg=imagecreatetruecolor($newWidth,$newHeight);
			imagecopy($newImg, $interImg, 0,0,0,0,$newWidth,$newHeight);
		}// if3

		$fun = $type == 'jpg' ? 'imagejpeg' : 'image' . $type;
		$fun($newImg, $dstFile,9); // 存储图像

		return true;
	}
}
