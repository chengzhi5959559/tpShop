<?php
return array(
	'DB_TYPE'=>'mysql',
	'DB_HOST'=>'127.0.0.1',
	'DB_NAME'=>'php34',
	'DB_USER'=>'root',
	'DB_PWD'=>'',
	'DB_PREFIX'=>'php34_',
	'DB_CHARSET'=>'utf8',
	'DB_PORT'=>'3306',
	/********** 图片相关的配置 ************/
	'IMG_maxSize' => '3M',
	'IMG_exts' => array('jpg', 'pjpeg', 'bmp', 'gif', 'png', 'jpeg'),
	'IMG_rootPath' => './Public/Uploads/',
	'IMG_URL' => '/Public/Uploads/',
	/*************************** 页面提示 ****************************************/
	'TMPL_ACTION_ERROR'     =>  './Application/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  './Application/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
	/********** 修改I函数底层过滤时使用的函数 ***********/
	'DEFAULT_FILTER' => 'trim,removeXSS',
	/********* MD5时用来复杂化的 ****************/
	'MD5_KEY' => 'fdsa#@90#_jl329$9lfds!129',
	/************** 发邮件的配置 ***************/
	'MAIL_ADDRESS' => 'php28_28@163.com',   // 发货人的email
	'MAIL_FROM' => 'php28_28',      // 发货人姓名
	'MAIL_SMTP' => 'smtp.163.com',      // 邮件服务器的地址
	'MAIL_LOGINNAME' => 'php28_28',   
	'MAIL_PASSWORD' => 'php123123',
);