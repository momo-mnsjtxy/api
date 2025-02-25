<?php
include_once('./Config.php');
$UserName = $_POST['UserName'];
$Email = $_POST['Email'];
$Password = $_POST['Password'];
$IsPassword = $_POST['IsPassword'];
$Md5Password = md5($Password);
if(!isset($UserName)){
	$Result = [ 'code' => 0 , 'msg' => '用户名为空'];
	echo json($Result);
}elseif (!isset($Email)) {
	$Result = [ 'code' => 0 , 'msg' => '邮箱账号为空'];
	echo json($Result);
}elseif (!isset($Password)) {
	$Result = [ 'code' => 0 , 'msg' => '密码为空'];
	echo json($Result);
}elseif (!isset($IsPassword)) {
	$Result = [ 'code' => 0 , 'msg' => '确定密码为空'];
	echo json($Result);
}elseif ($Password !== $IsPassword) {
	$Result = [ 'code' => 0 , 'msg' => '两次密码不一致'];
	echo json($Result);
}elseif (!preg_match('/^[a-zA-Z0-9_-]{4,16}$/',$UserName)) {
	$Result = [ 'code' => 0 , 'msg' => '用户名由4-16位数字字母汉字和下划线组成'];
	echo json($Result);
}elseif (!preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/',$Email)) {
	$Result = [ 'code' => 0 , 'msg' => '邮箱格式错误'];
	echo json($Result);
}elseif (!preg_match('/^.*(?=.{6,18})(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*? ]).*$/',$Password)) {
	$Result = [ 'code' => 0 , 'msg' => '密码由6-18位数包括大小写字母数字特殊字符'];
	echo json($Result);
}elseif ($conn->query("SELECT UserName FROM UserName WHERE UserName='$UserName'")->num_rows > 0) {
	$Result = [ 'code' => 0 , 'msg' => '用户名已存在'];
	echo json($Result);
}elseif ($conn->query("INSERT INTO UserName (UserName, Email, Password) VALUES ('$UserName', '$Email', '$Md5Password')") === TRUE) {
	$Result = [ 'code' => 1 , 'msg' => '账号注册成功'];
	echo json($Result);
}else{
	$Result = [ 'code' => 0 , 'msg' => '账户注册失败'];
	echo json($Result);
}
?>