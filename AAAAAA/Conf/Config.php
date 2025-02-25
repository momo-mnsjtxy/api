<?php
$host = "127.0.0.1";
$name = "v2";
$pass = "WZWK2ScmDCmCBBHX";
$dbname = "v2";
$conn = new mysqli($host, $name, $pass, $dbname);
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
session_start(); //开启session
//日志写入函数
function Logs($api,$conn){
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = time();
    $sql = "INSERT INTO log (name, ip, time) VALUES ('$api','$ip','$time')";
    $conn->query($sql);
};
//xml输出函数
function xml($arr)
{
    $xml = "<root>";
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $xml .= "<" . $key . ">" . xml($val) . "</" . $key . ">";
        } else {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        }
    }
    $xml .= "</root>";
    header("content-type:text/xml;charset=utf-8");
    return $xml;
};
//json输出函数
function json($arr){
    header("content-type:application/json;charset=utf-8");
    echo json_encode($arr);
};
?>