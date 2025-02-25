<?php
include_once('./Config.php');
if($_POST['LoginOut'] == "1"){
	unset($_SESSION['Login']);
	unset($_SESSION['Time']);
	$Result = [ 'code' => 1 , 'msg' => '退出成功'];
	echo json($Result);
	exit();
}
$UserName = $_POST['UserName'];
$Password = $_POST['Password'];
$Md5Password = md5($Password);
if(!isset($UserName)){
	$Result = [ 'code' => 0 , 'msg' => '用户名为空'];
	echo json($Result);
}elseif (!isset($Password)) {
	$Result = [ 'code' => 0 , 'msg' => '密码为空'];
	echo json($Result);
}else{
	if(strpos($UserName, "@")){
		if ($conn->query("SELECT Email Password FROM UserName WHERE Email='$UserName' AND Password='$Md5Password'")->num_rows > 0) {
			$_SESSION['Login'] = $UserName;
			$_SESSION['LoginTime'] = time();
			$Result = [ 'code' => 1 , 'msg' => '登陆成功'];
			echo json($Result);
		}else{
			$Result = [ 'code' => 0 , 'msg' => '账号或密码错误'];
			echo json($Result);
		}
	}else{
		if ($conn->query("SELECT UserName Password FROM UserName WHERE UserName='$UserName' AND Password='$Md5Password'")->num_rows > 0) {
			$_SESSION['Login'] = $UserName;
			$_SESSION['LoginTime'] = time();
			$Result = [ 'code' => 1 , 'msg' => '登陆成功'];
			echo json($Result);
		}else{
			$Result = [ 'code' => 0 , 'msg' => '账号或密码错误'];
			echo json($Result);
		}
	}
}