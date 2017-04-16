<?php
require_once("../comm/utils.php");

function add_blog()
{
	//发表QQ空间日志的接口地址, 不要更改!!
    $url  = "https://graph.qq.com/blog/add_one_blog";
    $data = "access_token=".$_SESSION["access_token"]
        ."&oauth_consumer_key=".$_SESSION["appid"]
        ."&openid=".$_SESSION["openid"]
        ."&format=".$_POST["format"]
        ."&title=".$_POST["title"]
        ."&content=".$_POST["content"];

    $ret = do_post($url, $data); 
    return $ret;
}

//接口调用示例：
$ret = add_blog();
echo $ret;
?>
