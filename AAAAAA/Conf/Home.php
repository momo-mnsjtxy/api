<?php
include_once('./Config.php');
if(isset($_SESSION['Login']) != 1 || isset($_SESSION['LoginTime']) != 1){
	exit();
};
$LoginSession = $_SESSION['Login'];
if(strpos($_SESSION['Login'], "@")){
	$sql = "SELECT * FROM UserName WHERE Email='$LoginSession'";
	$result = $conn->query($sql);
	 
	if ($result->num_rows > 0) {
	    $row = $result->fetch_assoc();
	}
}else {
	$sql = "SELECT * FROM UserName WHERE UserName='$LoginSession'";
	$result = $conn->query($sql);
	 
	if ($result->num_rows > 0) {
	    $row = $result->fetch_assoc();
	}
}

$action = $_POST['action'];
if($action == 'API'){
	$sql = "SELECT * FROM API WHERE Code='1'";
	$result = $conn->query($sql);
	$data = [];
	while($row = $result->fetch_assoc()){
		$data[] = $row;
	}
	echo json($data);
}


$action = $_POST['action'];
if($action == 'UserApi'){
	$UID = $row['ID'];
	$sql = "select a.*, b.* from UserApi a join API b on a.APIID = b.ID WHERE UID = '$UID'";
	$result = $conn->query($sql);
	$data = [];
	while($row = $result->fetch_assoc()){
		$data['UserApi'][] = $row;
	}
	$APINUMSQL = "select ID from API";
	$APINUMRES = $conn->query($APINUMSQL);
	$APINUMS = $APINUMRES->num_rows;
	$data['APINUM'] = $APINUMS;
	echo json($data);
}
?>