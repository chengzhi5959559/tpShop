<?php
function sendMail($to, $title, $content)
{
	require_once('./PHPMailer_v5.1/class.phpmailer.php');
    $mail = new PHPMailer();
    // 设置为要发邮件
    $mail->IsSMTP();
    // 是否允许发送HTML代码做为邮件的内容
    $mail->IsHTML(TRUE);
    // 是否需要身份验证
    $mail->SMTPAuth=TRUE;
    $mail->CharSet='UTF-8';
    /*  邮件服务器上的账号是什么 */
    $mail->From=C('MAIL_ADDRESS');
    $mail->FromName=C('MAIL_FROM');
    $mail->Host=C('MAIL_SMTP');
    $mail->Username=C('MAIL_LOGINNAME');
    $mail->Password=C('MAIL_PASSWORD');
    // 发邮件端口号默认25
    $mail->Port = 25;
    // 收件人
    $mail->AddAddress($to);
    // 邮件标题
    $mail->Subject=$title;
    // 邮件内容
    $mail->Body=$content;
    return($mail->Send());
}
function removeXSS($val)
{
	// 实现了一个单例模式，这个函数调用多次时只有第一次调用时生成了一个对象之后再调用使用的是第一次生成的对象（只生成了一个对象），使性能更好
	static $obj = null;
	if($obj === null)
	{
		require('./HTMLPurifier/HTMLPurifier.includes.php');
		$config = HTMLPurifier_Config::createDefault();
		// 保留a标签上的target属性
		$config->set('HTML.TargetBlank', TRUE);
		$obj = new HTMLPurifier($config);  
	}
	return $obj->purify($val);  
}
/**
 * 上传图片并生成缩略图
 * 用法：
 * $ret = uploadOne('logo', 'Goods', array(
			array(600, 600),
			array(300, 300),
			array(100, 100),
		));
	返回值：
	if($ret['ok'] == 1)
		{
			$ret['images'][0];   // 原图地址
			$ret['images'][1];   // 第一个缩略图地址
			$ret['images'][2];   // 第二个缩略图地址
			$ret['images'][3];   // 第三个缩略图地址
		}
		else 
		{
			$this->error = $ret['error'];
			return FALSE;
		}
 *
 */
function uploadOne($imgName, $dirName, $thumb = array())
{
	// 上传LOGO
	if(isset($_FILES[$imgName]) && $_FILES[$imgName]['error'] == 0)
	{
		$rootPath = C('IMG_rootPath');
		$upload = new \Think\Upload(array(
			'rootPath' => $rootPath,
		));// 实例化上传类
		$upload->maxSize = (int)C('IMG_maxSize') * 1024 * 1024;// 设置附件上传大小
		$upload->exts = C('IMG_exts');// 设置附件上传类型
		/// $upload->rootPath = $rootPath; // 设置附件上传根目录
		$upload->savePath = $dirName . '/'; // 图片二级目录的名称
		// 上传文件 
		// 上传时指定一个要上传的图片的名称，否则会把表单中所有的图片都处理，之后再想其他图片时就再找不到图片了
		$info   =   $upload->upload(array($imgName=>$_FILES[$imgName]));
		if(!$info)
		{
			return array(
				'ok' => 0,
				'error' => $upload->getError(),
			);
		}
		else
		{
			$ret['ok'] = 1;
		    $ret['images'][0] = $logoName = $info[$imgName]['savepath'] . $info[$imgName]['savename'];
		    // 判断是否生成缩略图
		    if($thumb)
		    {
		    	$image = new \Think\Image();
		    	// 循环生成缩略图
		    	foreach ($thumb as $k => $v)
		    	{
		    		$ret['images'][$k+1] = $info[$imgName]['savepath'] . 'thumb_'.$k.'_' .$info[$imgName]['savename'];
		    		// 打开要处理的图片
				    $image->open($rootPath.$logoName);
				    $image->thumb($v[0], $v[1])->save($rootPath.$ret['images'][$k+1]);
		    	}
		    }
		    return $ret;
		}
	}
}
// 显示图片
function showImage($url, $width='', $height='')
{
	if(empty($url))
		return ;
	$url = C('IMG_URL').$url;
	if($width)
		$width = "width='$width'";
	if($height)
		$height = "height='$height'";
	echo "<img src='$url' $width $width />";
}
// 删除图片：参数：一维数组：所有要删除的图片的路径
function deleteImage($images)
{
	// 先取出图片所在目录
	$rp = C('IMG_rootPath');
	foreach ($images as $v)
	{
		// @错误抵制符：忽略掉错误,一般在删除文件时都添加上这个
		@unlink($rp . $v);
	}
}
// 判断批量上传的数组中有没有上传至少一张图片
function hasImage($imgName)
{
	foreach ($_FILES[$imgName]['error'] as $v)
	{
		if($v == 0)
			return TRUE;
	}
	return FALSE;
}
function attr_id_sort($a, $b)
{
	if ($a['attr_id'] == $b['attr_id'])
		return 0;
	return ($a['attr_id'] < $b['attr_id']) ? -1 : 1;
}









