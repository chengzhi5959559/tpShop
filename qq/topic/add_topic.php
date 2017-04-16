<?php
require_once("../comm/utils.php");

function add_topic()
{
	//发表QQ空间日志的接口地址, 不要更改!!
    $url  = "https://graph.qq.com/shuoshuo/add_topic";
    $data = "access_token=".$_SESSION["access_token"]
        ."&oauth_consumer_key=".$_SESSION["appid"]
        ."&openid=".$_SESSION["openid"]
        ."&format=".$_POST["format"]
        ."&richtype=".$_POST["richtype"]
        ."&richval=".urlencode($_POST["richval"])
        ."&con=".urlencode($_POST["con"])
        ."&lbs_nm=".$_POST["lbs_nm"]
        ."&lbs_x=".$_POST["lbs_x"]
        ."&lbs_y=".$_POST["lbs_y"]
        ."&third_source=".$_POST["third_source"];

    //echo $data;
    $ret = do_post($url, $data); 
    return $ret;
}

//接口调用示例：
$ret = add_topic();
echo $ret;
?>
