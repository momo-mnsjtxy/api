<?php
include('../conf/config.php');
$ip = $_SERVER['REMOTE_ADDR'];
$time = time();
$sql = "INSERT INTO log (name, ip, time) VALUES ('Baidu','$ip','$time')";
$conn->query($sql);
if(!isset($_GET['domain'])||empty($_GET['domain'])||$_GET['domain']==''){
    header('Content-type: text/html;charset=UTF-8');
    $test = 'http'. (isset($_SERVER['HTTPS']) ? 's://' : '://').$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'?domain=www.gqink.cn';
        exit('<title>百度收录查询</title>
<pre>
请求参数：
domain：域名

返回值：
code：200 成功
msg：收录条数

测试链接：
'.$test.'
</pre>
');
}
header('Content-type: application/json;charset=UTF-8');
$url = $_GET['domain'];
$baidu='http://www.baidu.com/s?wd=site:'.$url;
 
$curl=curl_init();
curl_setopt($curl,CURLOPT_URL,$baidu);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
$rs=curl_exec($curl);
curl_close($curl);
 
$str = preg_match_all('/<b>找到相关结果数约(.*?)个<\/b>/',$rs,$baidu);
 
if(!empty($str)){
	// 没有站点信息
	echo json_encode(array('code'=>'200','num'=>$baidu['1']['0']));
}else{
	// 有站点信息
	$str = preg_match_all('/<b style="color:#333">(.*?)<\/b>/',$rs,$baidu);
	if($str){
		echo json_encode(array('code'=>'200','num'=>$baidu['1']['0']));
	}else{
		echo json_encode(array('code'=>'202','msg'=>'Error'));
	}
}