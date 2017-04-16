<?php
return array(
	'HTML_CACHE_ON'     =>    FALSE, // 开启静态缓存
	'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
	'HTML_FILE_SUFFIX'  =>    '.html', // 设置静态缓存文件后缀
	// 可以为前台的每个页面单独配置
	'HTML_CACHE_RULES'  =>     array(
		// 为首页生成一个1小时的缓存页面，页面名叫index.shtml
		'Index:index' => array('index', 3600), 
		// 为商品详情页中的每件商品都生成一个缓存文件
		'Index:goods' => array('{id|goodsdir}/goods_{id}', 3600),
	)
);
// 每100个页面放到一个目录下（不要把所有的文件都放到一个目录下）
function goodsdir($id)
{
	return ceil($id/100);  // 计算所在目录的名称
}