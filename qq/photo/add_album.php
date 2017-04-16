<?php
require_once("../comm/utils.php");

function add_album()
{
	//创建QQ空间相册的接口地址, 不要更改!!
    $url  = "https://graph.qq.com/photo/add_album";
    $data = "access_token=".$_SESSION["access_token"]
        ."&oauth_consumer_key=".$_SESSION["appid"]
        ."&openid=".$_SESSION["openid"]
        ."&format=".$_POST["format"]
        ."&albumname=".urlencode($_POST["albumname"])
        ."&albumdesc=".urlencode($_POST["albumdesc"])
        ."&priv=".$_POST["priv"];

    //echo $data;

    $ret =  do_post($url, $data); 
    return $ret;
}

//接口调用示例：
$ret = add_album();
echo $ret;
?>
