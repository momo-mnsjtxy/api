<?php
function formatsize($size) 
{
	$danwei=array('B ','K ','M ','G ','T ');
	$allsize=array();
	$i=0;
	for($i = 0; $i <5; $i++) 
	{
		if(floor($size/pow(1024,$i))==0){break;}
	}
	for($l = $i-1; $l >=0; $l--) 
	{
		$allsize1[$l]=floor($size/pow(1024,$l));
		$allsize[$l]=$allsize1[$l]-$allsize1[$l+1]*1024;
	}
	$len=count($allsize);
	for($j = $len-1; $j >=0; $j--) 
	{
		$fsize=$fsize.$allsize[$j].$danwei[$j];
	}	
	return $fsize;
}
$strs = @file("/proc/net/dev"); 
for ($i = 2; $i < count($strs); $i++ )
{
	preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
	$NetOutSpeed[$i] = $info[10][0];
	$NetInputSpeed[$i] = $info[2][0];
	$NetInput[$i] = formatsize($info[2][0]);
	$NetOut[$i]  = formatsize($info[10][0]);
}
//ajax调用实时刷新
$data = [
    'NetOut2'=>"$NetOut[2]",
    'NetOut3'=>"$NetOut[3]",
    'NetOut4'=>"$NetOut[4]",
    'NetOut5'=>"$NetOut[5]",
    'NetOut6'=>"$NetOut[6]",
    'NetOut7'=>"$NetOut[7]",
    'NetOut8'=>"$NetOut[8]",
    'NetOut9'=>"$NetOut[9]",
    'NetOut10'=>"$NetOut[10]",
    'NetInput2'=>"$NetInput[2]",
    'NetInput3'=>"$NetInput[3]",
    'NetInput4'=>"$NetInput[4]",
    'NetInput5'=>"$NetInput[5]",
    'NetInput6'=>"$NetInput[6]",
    'NetInput7'=>"$NetInput[7]",
    'NetInput8'=>"$NetInput[8]",
    'NetInput9'=>"$NetInput[9]",
    'NetInput10'=>"$NetInput[10]",
    'NetOutSpeed2'=>"$NetOutSpeed[2]",
    'NetOutSpeed3'=>"$NetOutSpeed[3]",
    'NetOutSpeed4'=>"$NetOutSpeed[4]",
    'NetOutSpeed5'=>"$NetOutSpeed[5]",
    'NetInputSpeed2'=>"$NetInputSpeed[2]",
    'NetInputSpeed3'=>"$NetInputSpeed[3]",
    'NetInputSpeed4'=>"$NetInputSpeed[4]",
    'NetInputSpeed5'=>"$NetInputSpeed[5]",
];
header("content-type:application/json;charset=utf-8");
$jarr=json_encode($data); 
echo $jarr;
?>