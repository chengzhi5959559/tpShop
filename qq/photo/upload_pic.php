<?php

require_once("../comm/utils.php");

function upload_pic()
{
	//上传照片的接口地址, 不要更改!!
    $url  = "https://graph.qq.com/photo/upload_pic";

    $params["access_token"] = $_SESSION["access_token"]; 
    $params["oauth_consumer_key"] = $_SESSION["appid"];
    $params["openid"] = $_SESSION["openid"];
    $params["photodesc"] = urlencode($_POST["photodesc"]);
    $params["title"] = urlencode($_POST["title"]);
    $params["albumid"] = urlencode($_POST["albumid"]);
    $params["x"] = $_POST["x"];
    $params["y"] = $_POST["y"];
    $params["format"] = $_POST["format"];
    
    //处理上传图片
    foreach ($_FILES as $filename => $filevalue)
    {   
        $tmpfile = dirname($filevalue["tmp_name"])."/".$filevalue["name"];
        move_uploaded_file($filevalue["tmp_name"], $tmpfile);
        $params[$filename] = "@$tmpfile";
    }

    $ret =  do_post($url, $params);
    unlink($tmpfile);
    //echo $tmpfile;
    return $ret;

}


//接口调用示例：
$ret = upload_pic();
echo $ret;
?>
