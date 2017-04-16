<?php
require_once("../comm/utils.php");

function add_weibo()
{
	//发表微博的接口地址, 不要更改!!
    $url  = "https://graph.qq.com/wb/add_weibo";
    $data = "access_token=".$_SESSION["access_token"]
        ."&oauth_consumer_key=".$_SESSION["appid"]
        ."&openid=".$_SESSION["openid"]
        ."&format=".$_POST["format"]
        ."&type=".$_POST["type"]
        ."&content=".urlencode($_POST["content"])
        ."&img=".urlencode($_POST["img"]);

    //echo $data;
    $ret = do_post($url, $data); 
    return $ret;
}

//接口调用示例：
$ret = add_weibo();
echo $ret;
?>
