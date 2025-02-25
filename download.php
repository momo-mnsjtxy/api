<?
if(filter_var($_REQUEST['url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
	$file_name = $_REQUEST['url']; 
	$mime = 'application/force-download'; 
	header('Pragma: public');     // required 
	header('Expires: 0');        // no cache 
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
	header('Cache-Control: private',false); 
	header('Content-Type: '.$mime); 
	header('Content-Disposition: attachment; filename="'.basename($_REQUEST['name']).'"'); 
	header('Content-Transfer-Encoding: binary'); 
	header('Connection: close'); 
	readfile($file_name);        // push it out 
	exit(); 
}
?>