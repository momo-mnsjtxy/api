<?php
include_once('../conf/config.php');
$info = [];
$AGENT = $_SERVER['HTTP_USER_AGENT'];
$method = $_SERVER['REQUEST_METHOD'];
$info['USER_INFO'] = ['HTTP_USER_AGENT' => $AGENT, 'USER_IP' => $ip, 'Request_Method' => $method, 'Request_Time' => date('Y-m-s h:i:s',$time)];
$info['Request_Parameters'] = $_REQUEST;
$info['Databases']['tables'] = $_REQUEST['tables'];
if($info['Databases']['tables'] == NULL){
	$info['code'] = ['state' => 'ERROR' , 'msg' => '查询表名空'];
}elseif(@count($conn->query("SHOW TABLES LIKE '". $info['Databases']['tables']."'")->fetch_assoc()) == 1){
	switch ($_REQUEST['class']) {
		case 'list':
			$currentpage = $_REQUEST['page'];
			if(!isset($currentpage)){
				$currentpage = 1;
			}
			$checksql = "SELECT count(*) FROM ". $info['Databases']['tables']."";
			$result = $conn->query($checksql);
			$count = $row = $result->fetch_assoc()['count(*)'];
			if($currentpage>0){
			    $pagesize = 15;
			    $pages = ceil($count/$pagesize);//共多少页
			    $pageCount = $pages;
			    $prepage = $currentpage -1;
			    if($prepage<=0)
			      $prepage=1;
			    $nextpage = $currentpage+1;
			    if($nextpage >= $pages){
			     $nextpage = $pages;
			    }
			    $start =($currentpage-1) * $pagesize;//起始位置
			    $sql = "SELECT * FROM ". $info['Databases']['tables']." ORDER BY id DESC limit $start,$pagesize";
			    $result = $conn->query($sql);
			}else {
			    $sql = "SELECT * FROM ". $info['Databases']['tables']." ORDER BY id DESC limit 0,10";
			    $result = $conn->query($sql);
			}
			
			$listData = ['conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$currentpage]];
			while($row = $result->fetch_assoc()) {
			    $listData['data'][] = $row;
			}
			$info['Data'] = $listData;
			break;
		
		default:
			$sql = "INSERT INTO log (name, ip, time) VALUES ('Love','$ip','$time')";
			$conn->query($sql);
			$checksql = "SELECT count(*) FROM ". $info['Databases']['tables']."";
			$result = $conn->query($checksql);
			$rnum = $row = $result->fetch_assoc()['count(*)'];
			$num  = rand(1,$rnum-1);
			$datasql = "select * from ". $info['Databases']['tables']." where id='$num'";
			$result = $conn->query($datasql);
			$row = $result->fetch_assoc();
			$info['Data'] = $row;
			break;
	}
}else{
	$info['code'] = ['state' => 'ERROR' , 'msg' => '数据库不存在此表'];
}
switch ($_REQUEST['type']) {
	case 'xml':
		print_r(xml($info));
		break;
	
	default:
		print_r(json($info));
		break;
}
?>