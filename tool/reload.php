<?php
/**
 * 宝塔API接口示例Demo
 * 仅供参考，请根据实际项目需求开发，并做好安全处理
 * date 2018/12/12
 * author 阿良
 */
class bt_api {
	private $BT_KEY = "3sCcGTdkK526aS5jvcO4rkJYwbmIki6Z";  //接口密钥
  	private $BT_PANEL = "http://47.103.87.205:13257";	   //面板地址
	
  	//如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
	public function __construct($bt_panel = null,$bt_key = null){
		if($bt_panel) $this->BT_PANEL = $bt_panel;
		if($bt_key) $this->BT_KEY = $bt_key;
	}
	
  	//示例取面板日志	
	public function Ok(){
		//拼接URL地址
		$url = $this->BT_PANEL.'/system?action=ReMemory';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['action'] = 'ReMemory';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
		
      	return $data;
	}
	
	
  	/**
     * 构造带有签名的关联数组
     */
  	private function GetKeyData(){
  		$now_time = time();
    	$p_data = array(
			'request_token'	=>	md5($now_time.''.md5($this->BT_KEY)),
			'request_time'	=>	$now_time
		);
    	return $p_data;    
    }
  	
  
  	/**
     * 发起POST请求
     * @param String $url 目标网填，带http://
     * @param Array|String $data 欲提交的数据
     * @return string
     */
    private function HttpPostCookie($url, $data,$timeout = 60)
    {
    	//定义cookie保存位置
        $cookie_file='./'.md5($this->BT_PANEL).'.cookie';
        if(!file_exists($cookie_file)){
            $fp = fopen($cookie_file,'w+');
            fclose($fp);
        }
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
$api = new bt_api();
$r_data = $api->Ok();
echo json_encode($r_data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
?>
