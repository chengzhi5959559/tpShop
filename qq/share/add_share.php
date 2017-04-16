<?php
require_once("../comm/config.php");

function add_share()
{
    //发布一条动态的接口地址, 不要更改!!
    $url = "https://graph.qq.com/share/add_share?"
        ."access_token=".$_SESSION["access_token"]
        ."&oauth_consumer_key=".$_SESSION["appid"]
        ."&openid=".$_SESSION["openid"]
        ."&format=json"
        ."&title=".urlencode($_REQUEST["title"])
        ."&url=".urlencode($_REQUEST["url"])
        ."&comment=".urlencode($_REQUEST["comment"])
        ."&summary=".urlencode($_REQUEST["summary"])
        ."&images=".urlencode($_REQUEST["images"]);

    //echo $url;

    $ret = file_get_contents($url);
}

//接口调用示例：
$ret = add_share();
echo $ret;
?>
