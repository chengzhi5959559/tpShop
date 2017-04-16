<?php

require_once("../comm/config.php");

function list_album()
{
    //获取相册列表的接口地址, 不要更改!!
    $url = "https://graph.qq.com/photo/list_album?"
        ."access_token=".$_SESSION["access_token"]
        ."&oauth_consumer_key=".$_SESSION["appid"]
        ."&openid=".$_SESSION["openid"]
        ."&format=json";
    //echo $url;
    $ret = file_get_contents($url);
    return $ret;
}

//接口调用示例：
$ret = list_album();
$arr = json_decode($ret, true);
print_r($arr);
?>
