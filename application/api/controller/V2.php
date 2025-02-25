<?php
namespace app\api\controller;
use \think\Request;
use \think\Db;
use think\Cache;
use GuzzleHttp\Client;
use QL\QueryList;
		/*$APPID = Request::instance()->param('appid');
	    $APPKEY = Request::instance()->param('appkey');
	    if(!$APPID || !$APPKEY){
        	$data = [
				'code' => "400",
				'msg'=>"缺少APPID或APPKEY参数。没有APPKEY 前往 https://api.gqink.cn/ 免费申请"
			];
			return INT(Request::instance()->param('type'),$data);
	    }elseif(!Db::table('user')->where('UID',$APPID)->where('APPKEY',$APPKEY)->find()){
	        $data = [
				'code' => "400",
				'msg'=>"APPID或者APPKEY不一致。前往 https://api.gqink.cn/ 免费申请"
			];
	        return INT(Request::instance()->param('type'),$data);
	    }else{
	        Db::table('request')->data([
	            'UID'=>$APPID,
	            'Keyword'=>$Log_Kw,
	            'TIME'=>time(),
	            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
	            'Request' => Request::instance()->method(),
	            'IP'=>get_ip(),
	            'Code'=>0,
	        ])->insert();
	    }*/
	    
class V2
{
	public function index(){
		return json(['code'=>400,'msg'=>'接口错误','api'=>""]);
	}
	
	//UserInfo接口
	public function UserInfo(){
	    $Log_Kw = "UserInfo";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$bro = get_bro();
		$ua = $_SERVER['HTTP_USER_AGENT'];
		$os=get_os_info($ua)[0];
		$ip = get_ip();
		for ($i = 0; $i < 10; $i++) {
		    $res = iconv("gbk", "utf-8",file_get_contents('http://opendata.baidu.com/api.php?query='.$ip.'&co=&resource_id=6006&t=1329357746681&ie=utf8&oe=gbk&format=json&tn=baidu'));
            $GET_JSON = json_decode(trim($res,chr(239).chr(187).chr(191)),true);
			if(is_array($GET_JSON)){
				$data = [
					'code' => 200,
					'Copyright' => Copyright(),
					"data" => [
						'ip'=>$ip,
						'location' => $GET_JSON['data'][0]['location'],
						'os' => $os,
						'browser' => $bro,
					],
				];
				return INT(Request::instance()->param('type'),$data);
				break;
			}
		}
	}
	
	//url.cn接口
	public function Url(){
	    $Log_Kw = "Url";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$url = Request::instance()->param('url');
		switch (Request::instance()->param('site')) {
			case 'url.cn':
				if(!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
					$data = [
						'code' => "400"
						,'msg'=>"参数错误"
					];
					return INT(Request::instance()->param('type'),$data);
				}else {
					for ($i = 0; $i < 10; $i++) {
						$GET_JSON = GET_JSON('http://shorturl.8446666.sojson.com/qq/shorturl?url='.$url);
						if(@is_array($GET_JSON)){
							$data = [
								'code' => 200,
								'Copyright' => Copyright(),
								"data" => [
									'a'=>$GET_JSON['longurl'],
									'b'=>$GET_JSON['shorturl'],
									'msg'=>$GET_JSON['message']
								],
							];
							return INT(Request::instance()->param('type'),$data);
							break;
						}
					}
				}
				break;
			case 't.cn':
				if(!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
					$data = [
						'code' => "400"
						,'msg'=>"参数错误"
					];
					return INT(Request::instance()->param('type'),$data);
				}else {
					for ($i = 0; $i < 10; $i++) {
						$GET_JSON = GET_JSON('http://shorturl.8446666.sojson.com/sina/shorturl?url='.$url);
						if(@is_array($GET_JSON)){
							$data = [
								'code' => 200,
								'Copyright' => Copyright(),
								"data" => [
									'a'=>$GET_JSON['longurl'],
									'b'=>$GET_JSON['shorturl'],
									'msg'=>$GET_JSON['message']
								],
							];
							return INT(Request::instance()->param('type'),$data);
							break;
						}
					}
				}
				break;
			default:
				$data = [
					'code' => "400"
					,'msg'=>"参数错误"
				];
				return INT(Request::instance()->param('type'),$data);
				break;
		}
	}
	
	public function qlogo(){
	    $Log_Kw = "qlogo";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$qq = Request::instance()->param('qq');
		if(!empty($qq) && isset($qq)){
			$imgurl='https://q1.qlogo.cn/g?b=qq&nk='.$qq.'&s=100'; 
			//https://q.qlogo.cn/g?b=qq&nk=1825642827&s=100 
			//http://q.qlogo.cn/headimg_dl?bs=qq&dst_uin=1825642827&src_uin=www.feifeiboke.com&fid=blog&spec=100
			//http://q.qlogo.cn/headimg_dl?dst_uin=1825642827&spec=640
			//https://q1.qlogo.cn/g?b=qq&nk=1825642827&s=100
			header('Content-Type: image/png');
			@ob_end_clean();
			@readfile($imgurl);
			@flush();
			@ob_flush();
		}else{
			return json(['code'=>400,'msg'=>'参数错误']);
		}
	}
	
	//Gravatar头像解析
	public function Gravatar(){
	    $Log_Kw = "Gravatar";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$email = Request::instance()->param('email');
		if(!empty($email) && isset($email)){
			$imgurl='https://gravatar.loli.net/avatar/'.md5($email).'?s=65&r=G&d='; 
			//https://secure.gravatar.com/avatar/99816921fffed9a501768ff49daf6901?s=65&r=G&d=
			//https://cdn.v2ex.com/gravatar/99816921fffed9a501768ff49daf6901?s=65&r=G&d=
			//https://gravatar.loli.net/avatar/99816921fffed9a501768ff49daf6901?s=65&r=G&d=
			header('Content-Type: image/png');
			@ob_end_clean();
			@readfile($imgurl);
			@flush();
			@ob_flush();
		}else{
			return json(['code'=>400,'msg'=>'参数错误']);
		}
	}
	
	//BING每日图片
	public function Bing_img(){
	    $Log_Kw = "Bing_img";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		for ($i = 0; $i < 10; $i++) {
			$GET_JSON = GET_JSON('https://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=8');
			if(@is_array($GET_JSON)){
				$num = rand(0,count($GET_JSON['images'])-1);
	    		$imgurl='https://www.bing.com/'.$GET_JSON['images'][$num]['url'];
	    		if(Request::instance()->param('type') == 'url'){
	    			header("Location: $imgurl");
	    			exit();
				}elseif (Request::instance()->param('type') == 'image') {
					header('Content-Type: image/png');
					@ob_end_clean();
					@readfile($imgurl);
					@flush();
					@ob_flush();
				}else{
					$data = [
							'code' => 200,
							'Copyright' => Copyright(),
							"data" => [
							'url'=>$imgurl,
							'title'=>$GET_JSON['images'][$num]['copyright']
						],
					];
					return INT(Request::instance()->param('type'),$data);
				}
				break;
			}
		}
	}
	
	//防盗链解析
	public function image(){
	    $Log_Kw = "image";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$url = Request::instance()->param('url');
		if(!empty($url) && isset($url) && filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
			header('Content-type: image/png');
			$data = @file_get_contents(isset($url)?$url:'https://cdn.gqink.cn/blog/logo.png');
			if(isset($data)){
				echo $data;
			}else{
				$data = [
					'code' => "400"
					,'msg'=>"请求失败"
				];
				return INT(Request::instance()->param('type'),$data);
			}
			exit();
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//UserInfo接口
	public function IP(){
	    $Log_Kw = "IP";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    if(Request::instance()->param('type') == "map"){
	        header('Content-Type: image/png');
			@ob_end_clean();
			@readfile('https://cache.ip-api.com/'.Request::instance()->param('lon').','.Request::instance()->param('lat').',10');
			@flush();
			@ob_flush();
			exit();
	    }
		$ip = get_ip();
		$QIP = Request::instance()->param('ip');
		if(!empty($QIP) && isset($QIP)){
			$CheckIP = $QIP;
		}else{
			$CheckIP = $ip;
		}
		if(!empty($CheckIP) && isset($CheckIP)){
			for ($i = 0; $i < 10; $i++) {
			    //http://opendata.baidu.com/api.php?query=1.85.35.131&co=&resource_id=6006&t=1329357746681&ie=utf8&oe=gbk&format=json&tn=baidu
				$GET_JSON = GET_JSON('http://ip-api.com/json/'.$CheckIP.'?lang=zh-CN');
				if(is_array($GET_JSON)){
					$data = [
						'code' => 200,
						'Copyright' => Copyright(),
						"data" => [
						    "ip" => @$GET_JSON['query'],
                            "country" => @$GET_JSON['country'],
                            "countryCode" => @$GET_JSON['countryCode'],
                            "region" => @$GET_JSON['region'],
                            "regionName" => @$GET_JSON['regionName'],
                            "city" => @$GET_JSON['city'],
                            "zip" => @$GET_JSON['zip'],
                            "lat" => @$GET_JSON['lat'],
                            "lon" => @$GET_JSON['lon'],
                            "timezone" => @$GET_JSON['timezone'],
                            "isp" => @$GET_JSON['isp'],
                            "org" => @$GET_JSON['org'],
                            "as" => @$GET_JSON['as']
						],
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}
	}
	
	//IP签名图
	public function IP_IMG(){
	    $Log_Kw = "IP_IMG";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$ip = get_ip();
		if(!empty($ip) && isset($ip)){
			$bro = get_bro();
			$ua = $_SERVER['HTTP_USER_AGENT'];
			$os=get_os_info($ua)[0];
			$ip = get_ip();
			for ($i = 0; $i < 10; $i++) {
			    $res = iconv("gbk", "utf-8",file_get_contents('http://opendata.baidu.com/api.php?query='.$ip.'&co=&resource_id=6006&t=1329357746681&ie=utf8&oe=gbk&format=json&tn=baidu'));
                $GET_JSON = json_decode(trim($res,chr(239).chr(187).chr(191)),true);
				if(is_array($GET_JSON)){
					header("Content-type: image/JPEG");
					$im = imagecreatefromjpeg("public/xhxh.jpg"); 
					$ip = $_SERVER["REMOTE_ADDR"];
					$weekarray=array("日","一","二","三","四","五","六"); //先定义一个数组
					$get=Request::instance()->param('s');
					$get=base64_decode(str_replace(" ","+",$get));
					//定义颜色
					$black = ImageColorAllocate($im, 0,0,0);//定义黑色的值
					$red = ImageColorAllocate($im, 255,0,0);//红色
					$font = 'public/msyh.ttf';//加载字体
					//输出
					imagettftext($im, 16, 0, 10, 40, $red, $font,'欢迎您来自'.$GET_JSON['data'][0]['location'].'的朋友');
					imagettftext($im, 16, 0, 10, 72, $red, $font, '今天是'.date('Y年n月j日').' 星期'.$weekarray[date("w")]);//当前时间添加到图片
					imagettftext($im, 16, 0, 10, 104, $red, $font,'您的IP是:'.$ip);//ip
					imagettftext($im, 16, 0, 10, 140, $red, $font,'您使用的是'.$os.'操作系统');
					imagettftext($im, 16, 0, 10, 175, $red, $font,'您使用的是'.$bro.'浏览器');
					imagettftext($im, 14, 0, 10, 200, $black, $font,$get); 
					ImageGif($im);
					ImageDestroy($im);
					exit();
					break;
				}
			}
		}
	}
	
	//邮件发送接口
	public function Email(){
	    $Log_Kw = "Email";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$email = Request::instance()->param('email');
		$title = Request::instance()->param('title');
		$body = Request::instance()->param('body');
		if(empty($email) || empty($body) || empty($title)){
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}else{
			$code = email('',$email,'',$title,$body,'','');
			if(is_array($code) && $code['code'] == 1){
				$data = [
					'code' => "200",
					'msg'=> $code['msg']
				];
				return INT(Request::instance()->param('type'),$data);
			}else{
				$data = [
					'code' => "400",
					'msg'=> $code['msg']
				];
				return INT(Request::instance()->param('type'),$data);
			}
		}
	}
	
	//
	//ICP备案查询
	public function ICP(){
	    $Log_Kw = "ICP";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$url = Request::instance()->param('domain');
		if(!empty($url) && isset($url)){
			for ($i = 0; $i < 10; $i++) {
				//$GET_JSON = GET_JSON('https://api.ooopn.com/icp/api.php?url='.$url);
				$GET_JSON = json_decode(file_get_contents('https://api.ooopn.com/icp/api.php?url='.$url),chr(239).chr(187).chr(191));
				if(is_array($GET_JSON)){
					$data = [
						'code' => 200,
						'Copyright' => Copyright(),
						"data" => [
							'code' => "200",
					        'domain'=>$GET_JSON['domain'],
					        'icp'=>$GET_JSON['icp'],
					        'sitename'=>$GET_JSON['sitename'],
					        'name'=>$GET_JSON['name'],
					        'nature'=>$GET_JSON['nature'],
					        'time'=>$GET_JSON['time'],
						],
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400",
				'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//随机动漫图片
	public function DM_IMG(){
	    $Log_Kw = "DM_IMG";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$file_path = "public/img.data";
		for ($i = 0; $i < 10; $i++) {
			if(file_exists($file_path)){
			    $str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
			    $GET_JSON = explode("\r\n", $str);
			}
			if(@is_array($GET_JSON)){
				$num = rand(0,count($GET_JSON)-1);
	    		$imgurl=$GET_JSON[$num];
	    		if(Request::instance()->param('type') == 'url'){
	    			header("Location: $imgurl");
	    			exit();
				}elseif (Request::instance()->param('type') == 'image') {
					header('Content-Type: image/png');
					@ob_end_clean();
					@readfile($imgurl);
					@flush();
					@ob_flush();
				}else{
					$data = [
							'code' => 200,
							'Copyright' => Copyright(),
							"data" => [
							'url'=>$imgurl,
						],
					];
					return INT(Request::instance()->param('type'),$data);
				}
				break;
			}
		}
	}
	
	//葫芦侠接口
	public function C_IMG(){
	    $Log_Kw = "C_IMG";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		for ($i = 0; $i < 10; $i++) {
			$GET_JSON = GET_JSON('http://floor.huluxia.com/post/list/ANDROID/2.1?platform=2&gkey=000000&app_version=3.5.0.86.1&versioncode=20141391&market_id=floor_baidu&_key=D9417A952C422C763CB47A07D0AEE76E5BDF7BB8DA6C7C6120DFFE260FD2B8C95E8EA7EE26C85FD2151293C3FF1CB9F967533BEF008BC875&device_code=%5Bw%5Dd4%3A61%3A2e%3Aa0%3Ac7%3Ade-%5Bi%5D861918033679755-%5Bs%5D89860619100072454095&start=0&count=200&cat_id=56&tag_id=5601&sort_by=1');
			if(@is_array($GET_JSON)){
				$datalist = [];
				for ($i = 0; $i < count($GET_JSON['posts']); $i++) {
					 for ($a = 0; $a < count($GET_JSON['posts'][$i]['images']); $a++) {
					 	 $datalist[] = "https://api.gqink.cn/api/v2/image?url=".$GET_JSON['posts'][$i]['images'][$a];
					 }
				}
				$rand = rand(1,count($datalist));
	    		$imgurl = $datalist[$rand];
	    		if(Request::instance()->param('type') == 'url'){
	    			header("Location: $imgurl");
	    			exit();
				}elseif (Request::instance()->param('type') == 'image') {
					$datalist = [];
					for ($i = 0; $i < count($GET_JSON['posts']); $i++) {
						 for ($a = 0; $a < count($GET_JSON['posts'][$i]['images']); $a++) {
						 	 $datalist[] = $GET_JSON['posts'][$i]['images'][$a];
						 }
					}
					$rand = rand(1,count($datalist));
		    		$imgurl = $datalist[$rand];
					header('Content-Type: image/png');
					@ob_end_clean();
					@readfile($imgurl);
					@flush();
					@ob_flush();
				}else{
					$data = [
							'code' => 200,
							'Copyright' => Copyright(),
							"data" => [
							'url'=>$imgurl,
						],
					];
					return INT(Request::instance()->param('type'),$data);
				}
				break;
			}
		}
	}
	
	//热评接口
	public function netease(){
	    $Log_Kw = "netease";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $num = 1;    //需要抽取的默认条数
	    $table = 'hot';    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
		if(is_array($Db)){
			$song_url = $Db[0]['mp3_url'];
			$song_url = str_replace('.mp3',"",$song_url);//祛除后缀名
			$song_id = substr($song_url,strripos($song_url,"=")+1);
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
						'id' => $Db[0]['id'],
						'song_id' => $song_id,
						'name' => $Db[0]['name'],
						'images' => $Db[0]['images'],
						'author' => $Db[0]['author'],
						'mp3_url' => $song_url,
						'comment_nickname' => $Db[0]['comment_nickname'],
						'comment_content' => $Db[0]['comment_content'],
						'love' => $Db[0]['love'],
					],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//随机暖心情话接口
	public function love(){
	    $Log_Kw = "love";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $num = 1;    //需要抽取的默认条数
	    $table = 'love';    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
		if(is_array($Db)){
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
						'id' => $Db[0]['id'],
						'text' => $Db[0]['word'],
						'keyword' => $Db[0]['keyword'],
						'love' => $Db[0]['love'],
					],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//随机网名接口
	public function Name(){
	    $Log_Kw = "Name";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $num = 1;    //需要抽取的默认条数
	    $table = 'name';    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
		if(is_array($Db)){
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
						'id' => $Db[0]['id'],
						'text' => $Db[0]['word'],
						'keyword' => $Db[0]['keyword'],
						'love' => $Db[0]['love'],
					],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//随机签名接口
	public function autograph(){
	    $Log_Kw = "autograph";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $num = 1;    //需要抽取的默认条数
	    $table = 'autograph';    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
		if(is_array($Db)){
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
						'id' => $Db[0]['id'],
						'text' => $Db[0]['word'],
						'keyword' => $Db[0]['keyword'],
						'love' => $Db[0]['love'],
					],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//随机说说接口
	public function shuoshuo(){
	    $Log_Kw = "shuoshuo";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $num = 1;    //需要抽取的默认条数
	    $table = 'shuoshuo';    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
		if(is_array($Db)){
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
						'id' => $Db[0]['id'],
						'text' => $Db[0]['word'],
						'keyword' => $Db[0]['keyword'],
						'love' => $Db[0]['love'],
					],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//随机一言接口
	public function word(){
	    $Log_Kw = "word";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $num = 1;    //需要抽取的默认条数
	    $table = 'word';    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
		if(is_array($Db)){
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
						'id' => $Db[0]['id'],
						'text' => $Db[0]['text'],
						'love' => $Db[0]['love'],
					],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//网易云音乐解析
	public function Music_163(){
	    $Log_Kw = "Music_163";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$id = Request::instance()->param('id');
		if(!empty($id) && isset($id)){
			for ($i = 0; $i < 10; $i++) {
				$GET_JSON = m_163($id);
				if(is_array($GET_JSON)){
					if(Request::instance()->param('type') == 'lrc'){
						header("content-type:text/html;charset=utf-8");
        				echo $GET_JSON['lrc'];
        				exit();
					}
					$data = [
						'code' => 200,
						'Copyright' => Copyright(),
						"data" => $GET_JSON,
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400",
				'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//音乐搜索接口
	public function Music(){
	    $Log_Kw = "music";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $class = Request::instance()->param('class');
	    if(!empty($class) && isset($class)){
	        $music_filter = Request::instance()->param('class');
	    }else{
	        $music_filter = 'name';
	    }
		$music_input          = trim(Request::instance()->param('input'));
	    $music_type           = Request::instance()->param('site');
	    $music_page           = (int) Request::instance()->param('page');
        $music = new \Musics();
		for ($i = 0; $i < 10; $i++) {
		    $GET_JSON = $music->data($music_input,$music_filter,$music_type,$music_page);
			if(is_array($GET_JSON)){
			    if(is_array($GET_JSON['data'])){
			        $data = [
    					'code' => 200,
    					'Copyright' => Copyright(),
    					"data" => ['code'=>$GET_JSON['code'],'msg'=>'搜索成功','data'=>$GET_JSON['data']],
    				];
			    }else{
			        $data = [
    					'code' => 200,
    					'Copyright' => Copyright(),
    					"data" => ['code'=>$GET_JSON['code'],'msg'=>$GET_JSON['error']],
    				];
			    }
				return INT(Request::instance()->param('type'),$data);
				break;
			}
		}
	}
	
	//热歌榜
	public function Music_hot(){
	    $Log_Kw = "Music_hot";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$data = @file_get_contents("public/hot.json");
		$data = json_decode(trim($data,chr(239).chr(187).chr(191)),true);
		if(is_array($data)){
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => $data,
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//MD5加密
	public function MD5(){
	    $Log_Kw = "MD5";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$text = Request::instance()->param('text');
		if(!empty($text) && isset($text)){
			$data = [
					'code' => 200,
					'Copyright' => Copyright(),
					"data" => [
					'text'=> $text,
					'MD5' => md5($text),
				],
			];
			return INT(Request::instance()->param('type'),$data);
		}else{
			$data = [
				'code' => "400",
				'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//二维码生成
	public function QrCode(){
	    $Log_Kw = "QrCode";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$text = Request::instance()->param('text');
		$size = Request::instance()->param('site');
		if(empty($size) && !isset($size)){
			$size = 300;
		}
		if(!empty($text) && isset($text)){
			header("content-type:image/png;charset=utf-8");
        	QrCode($text,$size);
        	exit();
		}else{
			$data = [
				'code' => "400",
				'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//二维码解析
	public function QrReader(){
	    $Log_Kw = "QrReader";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		error_reporting(0);
		$url = Request::instance()->param('url');
		if(empty($url) && !isset($url)){
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}else{
			for ($i = 0; $i < 10; $i++) {
				$text = QrReader($url);
				if(!empty($text) && isset($text)){
					$data = [
							'code' => 200,
							'Copyright' => Copyright(),
							"data" => [
							'url' => $url,
							'text'=> @QrReader($url),
						],
					];
					return INT(Request::instance()->param('type'),$data);	
					break;
				}
			}
		}
	}
	
	//手机号查询接口
	public function Mobile(){
	    $Log_Kw = "Mobile";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$mobile = Request::instance()->param('mobile');
		if(!empty($mobile) && isset($mobile)){
            $pl = new \PhoneLocation();
            $info = $pl->find($mobile);
            if(@$info['province']){
				$data = [
					'code' => 200,
					'Copyright' => Copyright(),
					"data" => [
						'mobile' => $mobile,
						'province' => $info['province'],
						'city' => $info['city'],
						'operator' => $info['sp'],
						'prefix' => $info['tel_prefix'],
						'location' => $info['province'].$info['city'].$info['sp'],
					],
				];
				return INT(Request::instance()->param('type'),$data);
			}else{
			    $data = [
    				'code' => "400"
    				,'msg'=>"未查询到该号码信息"
    			];
    			return INT(Request::instance()->param('type'),$data);
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//whois查询接口
	public function whois(){
	    $Log_Kw = "whois";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$domain = Request::instance()->param('domain');
		if(!empty($domain) && isset($domain)){
			for ($i = 0; $i < 10; $i++) {
				$url = 'https://api.devopsclub.cn/api/whoisquery?domain='.$domain.'&type=json';
				$GET_JSON = GET_JSON($url);
				if(is_array($GET_JSON) && @$GET_JSON['data']['data']['domainName']){
					if(@$GET_JSON['data']['data']['expirationTime']){
						$expirationTime = @$GET_JSON['data']['data']['expirationTime'];
					}else{
						$expirationTime = @$GET_JSON['data']['data']['registryExpiryDate'];
					}
					
					if(@$GET_JSON['data']['data']['registrant']){
						$registrant = @$GET_JSON['data']['data']['registrant'];
					}else{
						$registrant = @$GET_JSON['data']['data']['registrar'];
					}
					
					if(@$GET_JSON['data']['data']['registrantContactEmail']){
						$registrantContactEmail = @$GET_JSON['data']['data']['registrantContactEmail'];
					}else{
						$registrantContactEmail = @$GET_JSON['data']['data']['registrarAbuseContactEmail'];
					}
					
					if(@$GET_JSON['data']['data']['expirationTime']){
						$registrationTime = @$GET_JSON['data']['data']['registrationTime'];
					}else{
						$registrationTime = @$GET_JSON['data']['data']['creationDate'];
					}
					
					if(@$GET_JSON['data']['data']['sponsoringRegistrar']){
						$sponsoringRegistrar = @$GET_JSON['data']['data']['sponsoringRegistrar'];
					}else{
						$sponsoringRegistrar = @$GET_JSON['data']['data']['registrarURL'];
					}
					
					$data = [
						'code' => 200,
						'Copyright' => Copyright(),
						"data" => [
							'domainName' => @$GET_JSON['data']['data']['domainName'],
							'registrationTime' => $registrationTime,
							'expirationTime' => $expirationTime,
							'nameServer' => @$GET_JSON['data']['data']['nameServer'],
							'registrant' => $registrant,
							'registrantContactEmail' => $registrantContactEmail,
							'sponsoringRegistrar' => $sponsoringRegistrar,
						],
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}

	//汉字转拼音
	public function pinyin(){
	    $Log_Kw = "pinyin";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$text = Request::instance()->param('text');
		if(!empty($text) && isset($text)){
			for ($i = 0; $i < 10; $i++) {
				preg_match_all("/./u",$text,$arr);
				$result = '';
				for ($i = 0; $i < count($arr[0]); $i++) {
					if (preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$arr[0][$i])) {
						$pin = pinyin($arr[0][$i]);
						if(!empty($pin) && isset($pin)){
							$result = $result.$pin." ";
						}else{
							$result = $result.$arr[0][$i];
						}
					} else {
						$result = $result.$arr[0][$i];
					}
				}
				if(!empty($result) && isset($result)){
					$data = [
						'code' => 200,
						'Copyright' => Copyright(),
						"data" => [
							'text' => $text,
							'pinyin' => $result,
						],
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//在线翻译接口
	public function fanyi(){
	    $Log_Kw = "fanyi";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$text = Request::instance()->param('text');
		$lang = Request::instance()->param('lang');
		if(!empty($lang) && isset($lang)){
			$langArr = [
				'ZH_CN2EN' => '中文»英语',
				'ZH_CN2JA' => '中文»日语',
				'ZH_CN2KR' => '中文»韩语',
				'ZH_CN2FR' => '中文»法语',
				'ZH_CN2RU' => '中文»俄语',
				'ZH_CN2SP' => '中文»西语',
				'EN2ZH_CN' => '英语»中文',
				'JA2ZH_CN' => '日语»中文',
				'KR2ZH_CN' => '韩语»中文',
				'FR2ZH_CN' => '法语»中文',
				'RU2ZH_CN' => '俄语»中文',
				'SP2ZH_CN' => '西语»中文',
			];
			if(in_array($lang, array_keys($langArr), true)){
				$lang = 'http://fanyi.youdao.com/translate?&doctype=json&type='.$lang.'&i='.$text;
			}else{
				$lang = 'http://fanyi.youdao.com/translate?&doctype=json&type=AUTO&i='.$text;
			}
		}else{
			$lang = 'http://fanyi.youdao.com/translate?&doctype=json&type=AUTO&i='.$text;
		}
		if(!empty($text) && isset($text)){
			for ($i = 0; $i < 10; $i++) {
				$res = @file_get_contents($lang);
				$GET_JSON = json_decode(trim($res,chr(239).chr(187).chr(191)),true);
				if(is_array($GET_JSON)){
					$data = [
						'code' => 200,
						'Copyright' => Copyright(),
						"data" => [
							'lang' => $GET_JSON['type'],
							'text' => $GET_JSON['translateResult'][0][0]['src'],
							'tgt' => $GET_JSON['translateResult'][0][0]['tgt'],
						],
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//PING接口
	public function ping(){
	    $Log_Kw = "ping";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$ip = Request::instance()->param('ip');
		if(!empty($ip) && isset($ip)){
			function ping_time($ip) {
				$ping_cmd = "ping -c 1 -w 5 " . $ip;
				exec($ping_cmd, $info);
				if($info == null){
					return ['code'=>404,'msg'=>"Ping请求找不到主机".$ip];die;
				}
				//判断是否丢包
				$str1 = $info['4'];
				$str2 = "1 packets transmitted, 1 received, 0% packet loss";
				if( strpos( $str1 , $str2 ) === false){
					return ['code'=>403,'msg'=>"请求目标超时"];die;
				}
				$ping_time_line = end($info);    
				$ping_time = explode("=", $ping_time_line)[1];
				$ping_time_min = explode("/", $ping_time)[0];
				$ping_time_avg = explode("/", $ping_time)[1];
				$ping_time_max = explode("/", $ping_time)[2];
				$result = array();
				$result['domain_ip'] = $info['0'];
				$ip = substr($result['domain_ip'], 0, strpos($result['domain_ip'], ')'));
				$ip = substr($ip,strripos($ip,"(")+1);
				$result['ip'] = $ip;
				$result['ping_min'] = $ping_time_min;
				$result['ping_avg'] = $ping_time_avg;
				$result['ping_max'] = $ping_time_max;
				return ['code'=>200,'msg'=>"请求目标成功",'data'=>$result];die;
			}
			$ping = ping_time($ip);
			if($ping['code'] != 200){
				$data = [
					'code' => 200,
					'Copyright' => Copyright(),
					"data" => [
						'code' => $ping['code'],
						'code' => $ping['code'],
						'msg' => $ping['msg'],
					],
				];
				return INT(Request::instance()->param('type'),$data);
				exit();
			}
			for ($i = 0; $i < 10; $i++) {
			    $GET_JSON = GET_JSON('http://ip-api.com/json/'.$ping['data']['ip'].'?lang=zh-CN');
				//$GET_JSON = GET_JSON('http://ip.taobao.com/service/getIpInfo.php?ip='.$ping['data']['ip']);
				if(is_array($GET_JSON)){
					$data = [
						'code' => 200,
						'Copyright' => Copyright(),
						"data" => [
							'code' => $ping['code'],
							'msg' => $ping['msg'],
							'ip' => $ping['data']['ip'],
							'domain_ip' => $ping['data']['domain_ip'],
							'ping_min' => $ping['data']['ping_min'],
							'ping_avg' => $ping['data']['ping_avg'],
							'ping_max' => $ping['data']['ping_max'],
							'country' => @$GET_JSON['country'],
							'region' => @$GET_JSON['regionName'],
							'city' => @$GET_JSON['city'],
							'isp' => @$GET_JSON['isp'],	
							"org" => @$GET_JSON['org'].@$GET_JSON['as'],
						],
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//机器人
	public function robot(){
	    $Log_Kw = "robot";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$text = Request::instance()->param('text');
		if(!empty($text) && isset($text)){
			for ($i = 0; $i < 10; $i++) {
				$url = 'http://i.itpk.cn/api.php?question='.$text.'&api_key=c7c0fe42b152684bc6971e881dbba254&api_secret=2q9k54fd2ufs';
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				$post_data = array(
				   
				);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
				$GET_TEXT = curl_exec($curl);
				curl_close($curl);
				$GET_JSON = json_decode(trim($GET_TEXT,chr(239).chr(187).chr(191)),true);
				if(is_array(@$GET_JSON)){
					if(@$GET_JSON['content']){
						$data = [
							'code' => 200,
							'Copyright' => Copyright(),
							"data" => [
									'text' => $text,
									'msg' => "【".$GET_JSON['title']."】".$GET_JSON['content'],
							],
						];
					}
					if(@$GET_JSON['type'] == "观音灵签"){
						$data = [
							'code' => 200,
							'Copyright' => Copyright(),
							"data" => [
									'text' => $text,
									'msg' => "您抽取的是第".$GET_JSON['number2']."【".$GET_JSON['number1']."】签<br/>签位：".$GET_JSON['haohua']."<br/>签语：".$GET_JSON['qianyu']."<br/>诗意：".$GET_JSON['shiyi']."<br/>解签：".$GET_JSON['jieqian']."",
							],
						];
					}
					if(@$GET_JSON['type'] == "月老灵签"){
						$data = [
							'code' => 200,
							'Copyright' => Copyright(),
							"data" => [
									'text' => $text,
									'msg' => "您抽取的是第".$GET_JSON['number2']."签<br/>签位：".$GET_JSON['haohua']."<br/>签语：".$GET_JSON['shiyi']."<br/>注释：".$GET_JSON['zhushi']."<br/>解签：".$GET_JSON['jieqian']."<br/>白话释义：".$GET_JSON['baihua']."",
							],
						];
					}
					if(@$GET_JSON['type'] == "财神爷灵签"){
						$data = [
							'code' => 200,
							'Copyright' => Copyright(),
							"data" => [
									'text' => $text,
									'msg' => "您抽取的是第".$GET_JSON['number2']."签<br/>签语：".$GET_JSON['qianyu']."<br/>注释：".$GET_JSON['zhushi']."<br/>解签：".$GET_JSON['jieqian']."<br/>解说：".$GET_JSON['jieshuo']."<br/>结果：".$GET_JSON['jieguo']."<br/>婚姻：".$GET_JSON['hunyin']."<br/>事业：".$GET_JSON['shiye']."<br/>功名：".$GET_JSON['gongming']."<br/>失物：".$GET_JSON['shiwu']."<br/>出外移居：".$GET_JSON['cwyj']."<br/>六甲：".$GET_JSON['liujia']."<br/>求财：".$GET_JSON['qiucai']."<br/>交易：".$GET_JSON['jiaoyi']."<br/>疾病：".$GET_JSON['jibin']."<br/>诉讼：".$GET_JSON['susong']."<br/>运途：".$GET_JSON['yuntu']."<br/>某事：".$GET_JSON['moushi']."<br/>合作人：".$GET_JSON['hhzsy'],
							],
						];
					}
					return INT(Request::instance()->param('type'),$data);
					break;
					return;
				}
				if(isset($GET_TEXT)){
					$data = [
						'code' => 200,
						'Copyright' => Copyright(),
						"data" => [
								'text' => $text,
								//'msg' => str_replace('[name]','你',str_replace('[cqname]','梦城',$GET_TEXT)),
								'msg' => $GET_TEXT
							],
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//天气
	public function weather(){
	    $Log_Kw = "weather";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$location = Request::instance()->param('location');
		if(!empty($location) && isset($location)){
			if($location == "AUTO"){
				$ip = get_ip();
				for ($i = 0; $i < 10; $i++) {
					$GET_JSON = GET_JSON('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
					if(@$GET_JSON['data']['city']){
						$location = @$GET_JSON['data']['city'];
						break;
					}
				}
			}
			// 心知天气接口调用凭据
			$key = 't6lanuj8zky47a6v'; // 测试用 key，请更换成您自己的 Key
			$uid = 'UC8676B7FF'; // 测试用 用户 ID，请更换成您自己的用户 ID
			// 参数
			$api = 'https://api.seniverse.com/v3/weather/daily.json'; // 接口地址
			$location = $location; // 城市名称。除拼音外，还可以使用 v3 id、汉语等形式
			
			// 生成签名。文档：https://www.seniverse.com/doc#sign
			$param = [
			    'ts' => time(),
			    'ttl' => 300,
			    'uid' => $uid,
			];
			$sig_data = http_build_query($param); // http_build_query 会自动进行 url 编码
			// 使用 HMAC-SHA1 方式，以 API 密钥（key）对上一步生成的参数字符串（raw）进行加密，然后 base64 编码
			$sig = base64_encode(hash_hmac('sha1', $sig_data, $key, TRUE));
			
			// 拼接 url 中的 get 参数。文档：https://www.seniverse.com/doc#daily
			$param['sig'] = $sig; // 签名
			$param['unit'] = 'c'; //单位
			$param['location'] = $location;
			$param['start'] = 0; // 开始日期。0 = 今天天气
			$param['days'] = 3; // 查询天数，1 = 只查一天
			
			// 构造url
			$url = $api . '?' . http_build_query($param);
			function httpGet($url)
			{
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, $url);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			    // 终止从服务端进行验证
			    // 如需设置为 TRUE，建议参考如下解决方案：
			    // https://stackoverflow.com/questions/18971983/curl-requires-curlopt-ssl-verifypeer-false
			    // https://stackoverflow.com/questions/6324391/php-curl-setoptch-curlopt-ssl-verifypeer-false-too-slow
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			    $output=curl_exec($ch);
			
			    curl_close($ch);
			    return $output;
			}
			$GET_JSON = json_decode(trim(httpGet($url),chr(239).chr(187).chr(191)),true);
			if(@is_array($GET_JSON['results'][0]['location'])){
				$data = [
					'code' => 200,
					'Copyright' => Copyright(),
					"data" => [
						'location' => $GET_JSON['results'][0]['location'],
						'daily' => $GET_JSON['results'][0]['daily'],
					],
				];
				return INT(Request::instance()->param('type'),$data);
			}else{
				$data = [
					'code' => 200,
					'Copyright' => Copyright(),
					"data" => [
						'code' => 400,
						'msg' => '地区错误'
					],
				];
				return INT(Request::instance()->param('type'),$data);
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//垃圾分类接口
	public function laji(){
	    $Log_Kw = "laji";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		header('Content-type: application/json');
		$name = Request::instance()->param('kw');
		$data =  json_decode(file_get_contents("https://ai.sm.cn/quark/1/ai?&format=json&dn=40438278951-bd4e2adb&nt=6&nw=4G&ve=3.5.1.118&pf=3300&fr=android&bi=35832&pr=ucpro&sv=release&ds=AAP9OPV5lylEX/vR9n966ClmybM oDldtdmYqVdDAvRk6A==&di=c76098ffdb5fc7ff&ei=bTkwBAg94Sr6l362zPhgZwIkWNqPbHUJIw==&ni=bTkwBARrcHH77Ozv2cLRrhxBTU2NjFQwuX5BL0YWgg6bx/8=&ut=AAP9OPV5lylEX/vR9n966ClmybM oDldtdmYqVdDAvRk6A==&mi=ONEPLUS A6000&session_id=20774fae-2bea-69ad-fee6-377ef1f950ad&q={$name}是什么垃圾&query_source=rec&activity_id=undefined&scene_name="));
	    if ($data->data[0]->guide==null || $data->data[0]->tts==null){
	        //不是垃圾
	        if (empty(@$data->data[0]->value->answer)) {
	            $err="未找到相关结果";
	        }else {
	            $err=str_replace("夸克宝宝","",$data->data[0]->value->answer);
	        }
	        $dataarr = [
	            "code"=>400,
	            "msg"=>$err
	        ];
	    }else{
	        //是垃圾
	        $dataarr = [
	            "code"=>200,
	            "msg"=>$data->data[0]->guide,
	            'Copyright' => Copyright(),
	            "data"=>[
	                "title"=>$data->data[0]->value->title,
	                "desc"=>$data->data[0]->value->desc,
	                "pic"=>$data->data[0]->value->pic
	            ]
	        ];
	    }
	    return INT(Request::instance()->param('type'),$dataarr);
		function error($str){
			$data = [
				'code' => "400"
				,'msg'=>$str
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//中国城市列表
	public function address(){
	    $Log_Kw = "address";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		header("content-type:application/json;charset=utf-8");
		echo @file_get_contents("public/address.json");
		exit();
	}
	
	//收录查询
	public function check(){
	    $Log_Kw = "check";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$domain = Request::instance()->param('domain');
		if(!empty($domain) && isset($domain)){
			for ($i = 0; $i < 10; $i++) {
				$url = 'https://api.oioweb.cn/api/baidu.php?url='.$domain;
				$res = @file_get_contents($url);
				$GET_JSON = json_decode(trim($res,chr(239).chr(187).chr(191)),true);
				if(@$GET_JSON['code']==1){
					$data = [
						'code' => 200,
						'msg' => '查询成功',
						'Copyright' => Copyright(),
						"data" => [
							'domain' => $domain,
							'baidu' => $GET_JSON['data']['baidu'],
							'sougou' => $GET_JSON['data']['sougou'],
							'360' => $GET_JSON['data']['360'],
						]
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//查询域名是否被注册
	public function check_domain(){
	    $Log_Kw = "check_domain";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		function whois($domain) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://panda.www.net.cn/cgi-bin/check.cgi');
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'area_domain=' . trim($domain) );
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}
		function is_register($res) {
			$code = substr($res, 0, 3);
			if ($code == '210') {
				return 0;
			} else if ($code == '211') {
				return 1;
			} else {
				return 2;
			}
		}
		$result = whois(Request::instance()->param('domain'));
		$xml = simplexml_load_string($result);
		$code = is_register($xml->original);
		if ($code == 0) {
			$data = [
				'code' => 200,
				'msg' => '查询成功',
				'Copyright' => Copyright(),
				"data" => [
					'code' => 0,
					'domain' => Request::instance()->param('domain'), 
					'msg' => '域名可以注册' 
				]
			];
			return INT(Request::instance()->param('type'),$data);
		} else if ($code == 1) {
			$data = [
				'code' => 200,
				'msg' => '查询成功',
				'Copyright' => Copyright(),
				"data" => [
					'code' => 1,
					'domain' => Request::instance()->param('domain'), 
					'msg' => '域名已经被注册' 
				]
			];
			return INT(Request::instance()->param('type'),$data);
		} else {
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//日历毒汤
	public function du_word(){
	    $Log_Kw = "du_word";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$time = Request::instance()->param('time');
		if(!empty($time) && isset($time)){
			for ($i = 0; $i < 10; $i++) {
				$url = 'http://www.dutangapp.cn/u/toxic?date='.$time;
				function httpGet($url) {
				    $curl = curl_init();
				    $httpheader[] = "Accept:*/*";
				    $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
				    $httpheader[] = "Connection:close";
				    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
				    curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
				    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				    curl_setopt($curl, CURLOPT_TIMEOUT, 3);
				    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				    curl_setopt($curl, CURLOPT_URL, $url);
				    $res = curl_exec($curl);
				    curl_close($curl);
				    return $res;
				}
				$GET_JSON = json_decode(trim(httpGet($url),chr(239).chr(187).chr(191)),true);
				if(is_array($GET_JSON)){
					$data = [
						'code' => 200,
						'msg' => '查询成功',
						'Copyright' => Copyright(),
						"data" => $GET_JSON
					];
					return INT(Request::instance()->param('type'),$data);
					break;
				}
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	
	//域名报红检测
	public function Url_Sec(){
	    $Log_Kw = "Url_Sec";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		/**
		 * @return string
		 * Curl GET
		 */
		function Curl_GET($url){
		    $ch = curl_init();     // Curl 初始化
		    $header = [
		        'X-FORWARDED-FOR:218.91.92.84',
		        'CLIENT-IP:218.91.92.84',
		        'Cookie: pgv_pvi=9897416704; RK=WI7w5+CMZn; ptcz=e383433090496e1f60381fd68733196426868ba1876249a6736bcc4a3eb8ec72; pgv_pvid=455855220; cid=89410138-a33a-4ea9-98f2-4436da89d67d; _tfpdata=yBRknXvS8CfrED0zD85NZfxCPzT5SW8KEY03rIziZmu9ogk9y%2B5%2FU4QrJBbfqfuVqr%2F6vw8nSWfqHR3fu2Jc0TPvszwmrMwXEdN%2B8bKKfHwNCcL%2F2%2Fbhmiu%2B%2F4IgK1DX'
		    ];
		    curl_setopt($ch, CURLOPT_URL, $url);              // 设置 Curl 目标
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);      // Curl 请求有返回的值
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);     // 设置抓取超时时间
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);        // 跟踪重定向
		    curl_setopt($ch, CURLOPT_ENCODING, "");    // 设置编码
		    curl_setopt($ch, CURLOPT_REFERER, $url);   // 伪造来源网址
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  //伪造IP
		    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");   // 伪造ua
		    curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); // 取消gzip压缩
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		    $content = curl_exec($ch);
		    curl_close($ch);    // 结束 Curl
		    return $content;    // 函数返回内容
		}
		
		/**
		 * 返回当前毫秒
		 */
		function msectime() {
		    list($msec, $sec) = explode(' ', microtime());
		    return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
		}
		
		/**
		 * @param $jsonp
		 * @param bool $assoc
		 * @return mixed
		 * jsonp转对象
		 */
		function json_d($jsonp, $assoc = false)
		{
		    $jsonp = trim($jsonp);
		    if(isset($jsonp[0]) && $jsonp[0] !== '[' && $jsonp[0] !== '{') {
		        $begin = strpos($jsonp, '(');
		        if(false !== $begin)
		        {
		            $end = strrpos($jsonp, ')');
		            if(false !== $end)
		            {
		                $jsonp = substr($jsonp, $begin + 1, $end - $begin - 1);
		            }
		        }
		    }
		    return json_decode($jsonp, $assoc);
		}
		$url = Request::instance()->param('url');
		if(empty($url) && !isset($url)){
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
			exit();
		}
		$json = json_d(Curl_GET("https://cgi.urlsec.qq.com/index.php?m=check&a=check&callback=jQuery111306943167371763181_1567183944271&url={$url}&_=".msectime()));
		if ($json->reCode!==0){
		    $data = [
		        "code"=>400,
		        "msg"=>$json->data,
		    ];
		}else{
		    $type = $json->data->results->whitetype;
		    $urls = $json->data->results->url;
		    if ($type==1 || $type==3){
		    	$data = [
					'code' => 200,
					'msg' => '查询成功',
					'Copyright' => Copyright(),
					"data" => [
			            "code"=>1,
			            "msg"=>"检测成功",
			            "url"=>$urls,
			            "type"=>"正常"
		        	]
				];
		    }else{
		    	$data = [
					'code' => 200,
					'msg' => '查询成功',
					'Copyright' => Copyright(),
					"data" => [
			            "code"=>0,
			            "msg"=>"检测成功",
			            "url"=>$urls,
			            "type"=>"拦截"
		        	]
				];
		    }
		}
		return INT(Request::instance()->param('type'),$data);
	}
	
	//百度图床上传
	public function Baidu_Upload(){
	    $Log_Kw = "Baidu_Upload";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		error_reporting(0);
		function randIp()
		{
		    return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
		}
		function Curl_POST($url,$post_data){
		    $header=[
		        'X-FORWARDED-FOR:'.randIp(),
		        'CLIENT-IP:'.randIp()
		    ];
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_NOBODY, false);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, false);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");   // 伪造ua
		    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		    curl_setopt($ch, CURLOPT_ENCODING, '');
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		    $data = curl_exec($ch);
		    curl_close($ch);
		    return $data;
		}
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		if ((($_FILES["file"]["type"] == "image/gif")
		        || ($_FILES["file"]["type"] == "image/jpeg")
		        || ($_FILES["file"]["type"] == "image/jpg")
		        || ($_FILES["file"]["type"] == "image/pjpeg")
		        || ($_FILES["file"]["type"] == "image/x-png")
		        || ($_FILES["file"]["type"] == "image/png"))
		    && ($_FILES["file"]["size"] < 10*1024*1024)
		    && in_array($extension, $allowedExts)) {
		    if ($_FILES["file"]["error"] > 0) {
		        error("文件错误");
		    } else {
		        $post_data = [
		            "image"=>new \CURLFile(realpath($_FILES['file']['tmp_name'])),
		        ];
		        $data = Curl_POST("https://graph.baidu.com/upload",$post_data);
		        if ($data==""){
		        	$result = [
						'code' => 200,
						'msg' => '上传失败',
						'Copyright' => Copyright(),
						"data" => [
				            "code"=>0,
				            "msg"=>'上传失败',
			        	]
					];
				    return INT(Request::instance()->param('type'),$result);
		        }elseif (json_decode($data)->msg!=="Success"){
		            $result = [
						'code' => 400,
						'msg' => '上传失败',
						'Copyright' => Copyright(),
						"data" => [
				            "code"=>0,
				            "msg"=>'上传失败',
			        	]
					];
				    return INT(Request::instance()->param('type'),$result);
		        }else{
		            $pic = "https://graph.baidu.com/resource/".json_decode($data)->data->sign.".jpg";
		            $result = [
						'code' => 200,
						'msg' => '上传成功',
						'Copyright' => Copyright(),
						"data" => [
				        	"code"=>1,
		                	"imgurl"=>$pic
			        	]
					];
					return INT(Request::instance()->param('type'),$result);
		        }
		    }
		}else {
		    $result = [
				'code' => 400,
				'msg' => '非法的文件格式',
				'Copyright' => Copyright(),
				"data" => [
		            "code"=>0,
		            "msg"=>'非法的文件格式',
	        	]
			];
		    return INT(Request::instance()->param('type'),$result);
		}
	}
	
	//搜狗图床上传
	public function Sogou_Upload(){
	    $Log_Kw = "Sogou_Upload";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		error_reporting(0);
		function randIp()
		{
		    return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
		}
		function Curl_POST($url,$post_data){
		    $header=[
		        'X-FORWARDED-FOR:'.randIp(),
		        'CLIENT-IP:'.randIp()
		    ];
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_NOBODY, false);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, false);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");   // 伪造ua
		    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		    curl_setopt($ch, CURLOPT_ENCODING, '');
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		    curl_exec($ch);
		    $data = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
		    return $data;
		}
		function GetBetween($content, $start, $end)
		{
		    $r = explode($start, $content);
		    if (isset($r[1])) {
		        $r = explode($end, $r[1]);
		        return $r[0];
		    }
		    return '';
		}
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		//文件格式及大小做一下限制限制
		if ((($_FILES["file"]["type"] == "image/gif")
		        || ($_FILES["file"]["type"] == "image/jpeg")
		        || ($_FILES["file"]["type"] == "image/jpg")
		        || ($_FILES["file"]["type"] == "image/pjpeg")
		        || ($_FILES["file"]["type"] == "image/x-png")
		        || ($_FILES["file"]["type"] == "image/png"))
		    && ($_FILES["file"]["size"] < 10*1024*1024)
		    && in_array($extension, $allowedExts)) {
		    $ImageCachePath='public/images/sougou/';//临时缓存路径
		    //判断目录存在否，存在给出提示，不存在则创建目录
		    if (!is_dir($ImageCachePath)){
		        $res = mkdir($ImageCachePath, 0777, true);
		    }
		    //因为不支持直接使用临时文件上传 所以要先保存一下
		    move_uploaded_file($_FILES["file"]["tmp_name"], $ImageCachePath . $_FILES["file"]["name"]);
		    //文件储存位置
		    $files = $ImageCachePath . $_FILES["file"]["name"];
		    $post_data = [
		        "pic_path"=>new \CURLFile(realpath($files))
		    ];
		    $str=urldecode(Curl_POST("http://pic.sogou.com/ris_upload",$post_data));
		    unlink($files); //使用完销毁一下文件
		    $imgurl =  str_replace("http","https",GetBetween($str,".com/ris?query=","&oname="));
		    if ($imgurl==1 || $imgurl==""){
		        $result = [
					'code' => 200,
					'msg' => '上传失败',
					'Copyright' => Copyright(),
					"data" => [
			            "code"=>1,
			            "msg"=>'上传失败',
		        	]
				];
			    return INT(Request::instance()->param('type'),$result);
		    }else{
		    	$result = [
					'code' => 200,
					'msg' => '上传成功',
					'Copyright' => Copyright(),
					"data" => [
			            "code"=>1,
			            "msg"=>'上传成功',
			            "imgurl"=>$imgurl
		        	]
				];
			    return INT(Request::instance()->param('type'),$result);
		    }
		}else {
		    $result = [
				'code' => 200,
				'msg' => '非法的文件格式',
				'Copyright' => Copyright(),
				"data" => [
		            "code"=>0,
		            "msg"=>'非法的文件格式',
	        	]
			];
		    return INT(Request::instance()->param('type'),$result);
		}
	}
	
	public function upload(){
	    $Log_Kw = "upload";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    // 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('file');
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    $info = $file->validate(['size'=>5242880,'ext'=>'jpg,png,jpeg,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
	    if($info){
	        $result = [
				'code' => 200,
				'msg' => "上传成功",
				'Copyright' => Copyright(),
				"data" => [
		            "code"=>1,
		            'msg' => "上传成功",
		            "url"=>"https://api.gqink.cn/public/uploads/".$info->getSaveName(),
	        	]
			];
		    return INT(Request::instance()->param('type'),$result);
	    }else{
	        $result = [
				'code' => 200,
				'msg' => $file->getError(),
				'Copyright' => Copyright(),
				"data" => [
		            "code"=>0,
		            "msg"=>$file->getError()
	        	]
			];
			return INT(Request::instance()->param('type'),$result);
	    }
	}
	
	public function proxy(){
	    $Log_Kw = "proxy";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    if(Request::instance()->param('update')){
	        $res = @file_get_contents('https://www.freeip.top/api/proxy_ips?page='.Request::instance()->param('page'));
    		$GET_JSON = json_decode(trim($res,chr(239).chr(187).chr(191)),true);
    		if(is_array(@$GET_JSON['data']['data'])){
    			for ($i = 0; $i < count(@$GET_JSON['data']['data']); $i++) {
    			    $result = Db::table('freeip')->where('ip',@$GET_JSON['data']['data'][$i]['ip'])->find();
        			if($result == null){
        				$result = Db::table('freeip')->data(['ip'=>@$GET_JSON['data']['data'][$i]['ip'],'address'=>@$GET_JSON['data']['data'][$i]['ip_address'],'port'=>@$GET_JSON['data']['data'][$i]['port'],'class'=>@$GET_JSON['data']['data'][$i]['protocol']])->insert();
        				if($result){
        					echo "更新成功";
        				}else{
        					echo "更新失败";
        				}
        			}else{
        				echo "数据重复";
        			}
    			}
    		}else{
    			echo "内部错误";
    		}
    		exit();
	    }
	    $num = 1;    //需要抽取的默认条数
	    $table = 'freeip';    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
		if(is_array($Db)){
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
						'id' => $Db[0]['id'],
						'ip' => $Db[0]['ip'],
						'port' => $Db[0]['port'],
						'class' => $Db[0]['class'],
						'address' => $Db[0]['address'],
					],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//头像接口
	public function HeadImg(){
	    $Log_Kw = "HeadImg";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $keyword = Request::instance()->param('kw');
	    if($keyword == ""){
	        $keyword = "女生头像";
	    }else{
	        $keyword = Request::instance()->param('kw');
	    }
	    $num = 1;    //需要抽取的默认条数
	    $table = $keyword;    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
	    $imgurl = @$Db[0]['url'];
		if(is_array($Db)){
		    if(@is_array($Db)){
	    		header('Content-Type: image/png');
				@ob_end_clean();
				@readfile($imgurl);
				@flush();
				@ob_flush();
			}
		}
	}
	
	//历史的今天
	public function today(){
	    $Log_Kw = "today";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
        if(Cache::store('redis')->get('lishishangdejintian'.date("Y-m-d")) == null){
            $url = 'https://lishishangdejintian.51240.com/';
            $rules = [
                'today' => ['h2','text'],
                'result' => ['#main_content > ul > li','html'],
            ];
            $rt = QueryList::get($url)->rules($rules)->query()->getData();
            $list = $rt->all();
            $result = explode("</a>", $list['result']);
            $results = [];
            for ($i = 0; $i < count($result)-1; $i++) {
                preg_match('/<a href="(.*?)" target=/', $result[$i], $res);
                $url = 'https://lishishangdejintian.51240.com/'.$res[1];
                $rules = [
                    'body' => ['#main_content > div.neirong','text'],
                ];
                $rt = QueryList::get($url)->rules($rules)->query()->getData();
                $body = $rt->all();
                $results[] = [
                    'today' => substr($result[$i],0,strrpos($result[$i],"<")),
                    'title' => substr($result[$i],strripos($result[$i],">")+1),
                    'body' => str_replace('更多：https://www.51240.com/','',$body['body'])
                ];
            }
			if(is_array($results)){
			    Cache::store('redis')->set('lishishangdejintian'.date("Y-m-d"),json_encode($results));
			    $data = [
    				'code' => 200,
    				'Copyright' => Copyright(),
    				"data" => $results,
    			];
    			return INT(Request::instance()->param('type'),$data);
			}
        }else{
            $result = json_decode(trim(Cache::store('redis')->get('lishishangdejintian'.date("Y-m-d")),chr(239).chr(187).chr(191)),true);
		    $results = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => $result,
			];
			return INT(Request::instance()->param('type'),$results);
        }
	}
	
	//文字转语音
	public function audio(){
	    $Log_Kw = "audio";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $text = Request::instance()->param('text');
	    if(empty($text) || !isset($text)){
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
			exit();
	    }
	    $lang = Request::instance()->param('lang');
	    if(isset($lang)){
	        $lang = Request::instance()->param('lang');
	    }else{
	        $lang = 'zh';
	    }
	    header('Content-Type: audio/mpeg');
	    $url = 'http://tts.baidu.com/text2audio?lan='.$lang.'&ie=UTF-8&spd=3&text='.$text;
        exit(file_get_contents($url));
	}
	
	//随机颜色
	public function colors(){
	    $Log_Kw = "colors";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
        function hex2rgb($hexColor) {
            $color = str_replace('#', '', $hexColor);
            if (strlen($color) > 3) {
                $rgb = array(
                    'r' => hexdec(substr($color, 0, 2)),
                    'g' => hexdec(substr($color, 2, 2)),
                    'b' => hexdec(substr($color, 4, 2))
                );
            } else {
                $color = $hexColor;
                $r = substr($color, 0, 1) . substr($color, 0, 1);
                $g = substr($color, 1, 1) . substr($color, 1, 1);
                $b = substr($color, 2, 1) . substr($color, 2, 1);
                $rgb = array(
                    'r' => hexdec($r),
                    'g' => hexdec($g),
                    'b' => hexdec($b)
                );
            }
            return $rgb;
        }
	    $Log_Kw = "colors";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
    	mt_srand((double)microtime()*1000000);
        $color = '';
        while(strlen($color)<6){
            $color .= sprintf("%02X", mt_rand(0, 255));
        }
        $data = [
			'code' => 200,
			'Copyright' => Copyright(),
			"data" => ['color' => $color,'RGB'=>hex2rgb($color)],
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//qq群头像
	public function qunlogo(){
	    $Log_Kw = "qunlogo";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
		$qun = Request::instance()->param('qun');
		if(!empty($qun) && isset($qun)){
			$imgurl='http://p.qlogo.cn/gh/'.$qun.'/'.$qun.'/640/'; 
			header('Content-Type: image/png');
			@ob_end_clean();
			@readfile($imgurl);
			@flush();
			@ob_flush();
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//成语接龙
	public function chengyu(){
		$Log_Kw = "chengyu";
		Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		
		$text = Request::instance()->param('text');
		if(!empty($text) && isset($text)){
			for ($i = 0; $i < 10; $i++) {
    			$url = 'http://i.itpk.cn/api.php?question=@cy'.$text.'&api_key=c7c0fe42b152684bc6971e881dbba254&api_secret=2q9k54fd2ufs';
    			$curl = curl_init();
    			curl_setopt($curl, CURLOPT_URL, $url);
    			curl_setopt($curl, CURLOPT_HEADER, 0);
    			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    			curl_setopt($curl, CURLOPT_POST, 1);
    			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    			curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    			$post_data = array(
    			   
    			);
    			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    			$GET_TEXT = curl_exec($curl);
    			curl_close($curl);
    			if($GET_TEXT){
    			    $data = [
            			'code' => 200,
            			'Copyright' => Copyright(),
            			"data" => ['msg' => $GET_TEXT],
            		];
            		return INT(Request::instance()->param('type'),$data);
    			    break;
    			}
			}
		}else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//笑话接口
	public function xiaohua(){
		$Log_Kw = "xiaohua";
		Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		
		for ($i = 0; $i < 10; $i++) {
			$url = 'http://i.itpk.cn/api.php?question=笑话&api_key=c7c0fe42b152684bc6971e881dbba254&api_secret=2q9k54fd2ufs';
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$post_data = array(
			   
			);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
			$GET_TEXT = curl_exec($curl);
			curl_close($curl);
			if($GET_TEXT){
			    $data = [
        			'code' => 200,
        			'Copyright' => Copyright(),
        			"data" => json_decode(trim($GET_TEXT,chr(239).chr(187).chr(191)),true),
        		];
        		return INT(Request::instance()->param('type'),$data);
			    break;
			}
		}
	}
	
	
	//笑话接口
	public function HttpCode(){
		$Log_Kw = "HttpCode";
		Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		
		$url = Request::instance()->param('url');
		if(!empty($url) && isset($url)){
    		for ($i = 0; $i < 10; $i++) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, TRUE);
                curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $head = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
    			if($httpCode){
    			    $data = [
            			'code' => 200,
            			'Copyright' => Copyright(),
            			"data" => ["HttpCode" => $httpCode , 'url'=> $url],
            		];
            		return INT(Request::instance()->param('type'),$data);
    			    break;
    			}
    		}
		}else{
		    $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//随机密码
	public function RandPass(){
	    $Log_Kw = "RandPass";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $num = Request::instance()->param('num');
	    if(empty($num) || !isset($num)){
	        $num = 16;
	    }
        function make_password( $length = 16 ){
            $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 
            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's', 
            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D', 
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z', 
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', 
            '@','#', '$', '%', '^', '&', '*', '(', ')', '-', '_', 
            '[', ']', '{', '}', '<', '>', '~', '`', '+', '=', ',', 
            '.', ';', ':', '/', '?', '|');
            $keys = array_rand($chars, $length); 
            $password = '';
            for($i = 0; $i < $length; $i++)
            {
                $password .= $chars[$keys[$i]];
            }
            return $password;
        }
        function GetRandStr( $length = 16 ) {
            $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $len = strlen($str) - 1;
            $randstr = '';
            for ($i = 0; $i < $length; $i++) {
                $num = mt_rand(0, $len);
                $randstr .= $str[$num];
            }
            return $randstr;
        }
        function get_password( $length = 16 ) {
            $str = substr(md5(time()), 0, $length);//md5加密，time()当前时间戳
            return $str;
        }
        $data = [
			'code' => 200,
			'Copyright' => Copyright(),
			"data" => ['a' => make_password($num),'b'=>GetRandStr($num),'c'=>get_password($num)],
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//笑话接口
	public function Go(){
		$Log_Kw = "Go";
		Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		
		$url = Request::instance()->param('url');
		if(!empty($url) && isset($url)){
		    echo '
<!DOCTYPE html>
<html>
  <head>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="dns-prefetch" href="'.$url.'" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>加载中，请您稍候…</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <style type="text/css">@charset "UTF-8";html,body{margin:0;padding:0;width:100%;height:100%;background-color:#DB4D6D;display:flex;justify-content:center;align-items:center;font-family:"微軟正黑體";}.monster{width:110px;height:110px;background-color:#E55A54;border-radius:20px;position:relative;display:flex;justify-content:center;align-items:center;flex-direction:column;cursor:pointer;margin:10px;box-shadow:0px 10px 20px rgba(0,0,0,0.2);position:relative;animation:jumping 0.8s infinite alternate;}.monster .eye{width:40%;height:40%;border-radius:50%;background-color:#fff;display:flex;justify-content:center;align-items:center;}.monster .eyeball{width:50%;height:50%;border-radius:50%;background-color:#0C4475;}.monster .mouth{width:32%;height:12px;border-radius:12px;background-color:white;margin-top:15%;}.monster:before,.monster:after{content:"";display:block;width:20%;height:10px;position:absolute;left:50%;top:-10px;background-color:#fff;border-radius:10px;}.monster:before{transform:translateX(-70%) rotate(45deg);}.monster:after{transform:translateX(-30%) rotate(-45deg);}.monster,.monster *{transition:0.5s;}@keyframes jumping{50%{top:0;box-shadow:0px 10px 20px rgba(0,0,0,0.2);}100%{top:-50px;box-shadow:0px 120px 50px rgba(0,0,0,0.2);}}@keyframes eyemove{0%,10%{transform:translate(50%);}90%,100%{transform:translate(-50%);}}.monster .eyeball{animation:eyemove 1.6s infinite alternate;}h2{color:white;font-size:20px;margin:20px 0;}.pageLoading{position:fixed;width:100%;height:100%;left:0;top:0;display:flex;justify-content:center;align-items:center;background-color:#0C4475;flex-direction:column;transition:opacity 0.5s 0.5s;}.loading{width:200px;height:8px;margin-top:0px;border-radius:5px;background-color:#fff;overflow:hidden;transition:0.5s;}.loading .bar{background-color:#E55A54;width:0%;height:100%;}</style>
    <script type="text/javascript">document.oncontextmenu=function(t){window.event&&(t=window.event);try{var e=t.srcElement;return"INPUT"==e.tagName&&"text"==e.type.toLowerCase()||"TEXTAREA"==e.tagName?!0:!1}catch(n){return!1}},document.onpaste=function(t){window.event&&(t=window.event);try{var e=t.srcElement;return"INPUT"==e.tagName&&"text"==e.type.toLowerCase()||"TEXTAREA"==e.tagName?!0:!1}catch(n){return!1}},document.oncopy=function(t){window.event&&(t=window.event);try{var e=t.srcElement;return"INPUT"==e.tagName&&"text"==e.type.toLowerCase()||"TEXTAREA"==e.tagName?!0:!1}catch(n){return!1}},document.oncut=function(t){window.event&&(t=window.event);try{var e=t.srcElement;return"INPUT"==e.tagName&&"text"==e.type.toLowerCase()||"TEXTAREA"==e.tagName?!0:!1}catch(n){return!1}},document.onselectstart=function(t){window.event&&(t=window.event);try{var e=t.srcElement;return"INPUT"==e.tagName&&"text"==e.type.toLowerCase()||"TEXTAREA"==e.tagName?!0:!1}catch(n){return!1}};</script>
  </head>
  <body>
    <div class="pageLoading">
      <div class="monster">
        <div class="eye">
          <div class="eyeball"></div>
        </div>
        <div class="mouth"></div>
      </div><h2>加载中，请您稍候…</h2>
      <div class="loading">
        <div class="bar"></div>
      </div>
    </div>
    <script>  
        function link_jump(){  
            location.href="'.$url.'";
        }
        setTimeout(link_jump, 1000);
        setTimeout(function(){window.opener=null;window.close();}, 50000);
    </script> 
    <script>
      var percent = 0
      var timer = setInterval(function(){
        document.querySelector(".bar").style.setProperty("width",percent+"%")
        percent+=1
      },10)
    </script>
  </body>
</html>';
		}else{
		    $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//网易音乐榜单解析
	public function Music_List_163(){
	    $Log_Kw = "Music_List";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $id = Request::instance()->param('id');
	    if(!empty($id) && isset($id)){
	        if(Cache::store('redis')->get('Music_List_ID_163'.$id) == null){
	            require 'extend/netease/GetWangYiYunInfo.php';
	            $res = new \GetWangYiYunInfo();
                $data = $res->list('http://music.163.com/discover/toplist?id='.$id);
                Cache::store('redis')->set('Music_List_ID_163'.$id,json_encode($data,JSON_UNESCAPED_UNICODE));
                $data = [
        			'code' => 200,
        			'Copyright' => Copyright(),
        			"data" => $data,
        		];
	        }else{
	            $data = [
        			'code' => 200,
        			'Copyright' => Copyright(),
        			"data" => $result = json_decode(trim(Cache::store('redis')->get('Music_List_ID_163'.$id),chr(239).chr(187).chr(191)),true),
        		];
	        }
    		return INT(Request::instance()->param('type'),$data);
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//行政区域查询
	public function district(){
	    $Log_Kw = "district";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $keyword = Request::instance()->param('keyword');
	    if(!empty($keyword) && isset($keyword)){
	        if(Cache::store('redis')->get('city_'.$keyword) == null){
	            $url = 'https://restapi.amap.com/v3/config/district?keywords='.$keyword.'&subdistrict=3&key=fcf6233aaffbec6673b803191db3d00c&offset=5000';
                $data = file_get_contents($url);
                $data = json_decode($data,chr(239).chr(187).chr(191));
                Cache::store('redis')->set('city_'.$keyword,json_encode($data['districts'],JSON_UNESCAPED_UNICODE));
                $data = [
        			'code' => 200,
        			'Copyright' => Copyright(),
        			"data" => $data['districts'],
        		];
	        }else{
	            $data = [
        			'code' => 200,
        			'Copyright' => Copyright(),
        			"data" => json_decode(trim(Cache::store('redis')->get('city_'.$keyword),chr(239).chr(187).chr(191)),true),
        		];
	        }
    		return INT(Request::instance()->param('type'),$data);
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//实时天气
	public function WeatherInfo(){
	    $Log_Kw = "WeatherInfo";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $keyword = Request::instance()->param('keyword');
	    if(!empty($keyword) && isset($keyword)){
            $Weather = file_get_contents('https://restapi.amap.com/v3/weather/weatherInfo?city='.$keyword.'&key=fcf6233aaffbec6673b803191db3d00c');
            $Weather = json_decode($Weather,chr(239).chr(187).chr(191));
	        if(Cache::store('redis')->get('WeatherInfo_'.$keyword) == null){
                $WeatherInfo = file_get_contents('https://restapi.amap.com/v3/weather/weatherInfo?city='.$keyword.'&key=fcf6233aaffbec6673b803191db3d00c&extensions=all');
                $WeatherInfo = json_decode($WeatherInfo,chr(239).chr(187).chr(191));
                Cache::store('redis')->set('WeatherInfo_'.$keyword,json_encode($WeatherInfo['forecasts'],JSON_UNESCAPED_UNICODE));
                $data = [
        			'code' => 200,
        			'Copyright' => Copyright(),
        			"data" => ['time'=>$Weather['lives'],'weather' =>$WeatherInfo['forecasts']],
        		];
	        }else{
	            $data = [
        			'code' => 200,
        			'Copyright' => Copyright(),
        			"data" => ['time'=>$Weather['lives'],'weather' => json_decode(trim(Cache::store('redis')->get('WeatherInfo_'.$keyword),chr(239).chr(187).chr(191)),true)],
        		];
	        }
    		return INT(Request::instance()->param('type'),$data);
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//ip获取定位
	public function IpSadd(){
	    $Log_Kw = "IpSadd";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $ip = Request::instance()->param('ip');
	    if(!empty($ip) && isset($ip)){
	        
	    }else{
	        $ip = get_ip();
		}
		if(Cache::store('redis')->get('IpSadd_'.$ip) == null){
            $result = file_get_contents('https://restapi.amap.com/v3/ip?ip='.$ip.'&output=json&key=fcf6233aaffbec6673b803191db3d00c');
            $result = json_decode($result,chr(239).chr(187).chr(191));
            Cache::store('redis')->set('IpSadd_'.$ip,json_encode($result));
            $data = [
    			'code' => 200,
    			'Copyright' => Copyright(),
    			"data" => $result,
    		];
        }else{
            $data = [
    			'code' => 200,
    			'Copyright' => Copyright(),
    			"data" => json_decode(trim(Cache::store('redis')->get('IpSadd_'.$ip),chr(239).chr(187).chr(191)),true),
    		];
        }
    	return INT(Request::instance()->param('type'),$data);
	}
	
	//酷狗音乐解析
	public function Kugou(){
	    $Log_Kw = "Kugou";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $id = Request::instance()->param('id');
	    if(!empty($id) && isset($id)){
	        for ($i = 0; $i < 10; $i++) {
	            $music_input          = trim($id);
                $music_filter         = 'id';
                $music_type           = 'kugou';
                $music_page           = (int) 1;
                $music = new \Musics();
	            $GET_JSON = $music->data($music_input,$music_filter,$music_type,$music_page);
    			if(is_array($GET_JSON)){
    			    if($GET_JSON['code'] == 'success'){
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => $GET_JSON['data'][0],
        				];
    			    }else{
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => [
                				'code' => "400",
                				'msg'=>"找不到可用的播放地址"
                    		],
        				];
    			    }
    			    return INT(Request::instance()->param('type'),$data);
    				break;
    			}
    		}
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//QQ音乐解析
	public function qq(){
	    $Log_Kw = "qq";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $id = Request::instance()->param('id');
	    if(!empty($id) && isset($id)){
	        for ($i = 0; $i < 10; $i++) {
	            $music_input          = trim($id);
                $music_filter         = 'id';
                $music_type           = 'qq';
                $music_page           = (int) 1;
                $music = new \Musics();
	            $GET_JSON = $music->data($music_input,$music_filter,$music_type,$music_page);
    			if(is_array($GET_JSON)){
    			    if($GET_JSON['code'] == 'success'){
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => $GET_JSON['data'][0],
        				];
    			    }else{
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => [
                				'code' => "400",
                				'msg'=>"找不到可用的播放地址"
                    		],
        				];
    			    }
    			    return INT(Request::instance()->param('type'),$data);
    				break;
    			}
    		}
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//酷我音乐解析
	public function kuwo(){
	    $Log_Kw = "kuwo";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $id = Request::instance()->param('id');
	    if(!empty($id) && isset($id)){
	        for ($i = 0; $i < 10; $i++) {
	            $music_input          = trim($id);
                $music_filter         = 'id';
                $music_type           = 'kuwo';
                $music_page           = (int) 1;
                $music = new \Musics();
	            $GET_JSON = $music->data($music_input,$music_filter,$music_type,$music_page);
    			if(is_array($GET_JSON)){
    			    if($GET_JSON['code'] == 'success'){
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => $GET_JSON['data'][0],
        				];
    			    }else{
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => [
                				'code' => "400",
                				'msg'=>"找不到可用的播放地址"
                    		],
        				];
    			    }
    			    return INT(Request::instance()->param('type'),$data);
    				break;
    			}
    		}
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//虾米音乐解析
	public function xiami(){
	    $Log_Kw = "xiami";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $id = Request::instance()->param('id');
	    if(!empty($id) && isset($id)){
	        for ($i = 0; $i < 10; $i++) {
	            $music_input          = trim($id);
                $music_filter         = 'id';
                $music_type           = 'xiami';
                $music_page           = (int) 1;
                $music = new \Musics();
	            $GET_JSON = $music->data($music_input,$music_filter,$music_type,$music_page);
    			if(is_array($GET_JSON)){
    			    if($GET_JSON['code'] == 'success'){
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => $GET_JSON['data'][0],
        				];
    			    }else{
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => [
                				'code' => "400",
                				'msg'=>"找不到可用的播放地址"
                    		],
        				];
    			    }
    			    return INT(Request::instance()->param('type'),$data);
    				break;
    			}
    		}
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//咪咕音乐解析
	public function migu(){
	    $Log_Kw = "migu";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $id = Request::instance()->param('id');
	    if(!empty($id) && isset($id)){
	        for ($i = 0; $i < 10; $i++) {
	            $music_input          = trim($id);
                $music_filter         = 'id';
                $music_type           = 'migu';
                $music_page           = (int) 1;
                $music = new \Musics();
	            $GET_JSON = $music->data($music_input,$music_filter,$music_type,$music_page);
    			if(is_array($GET_JSON)){
    			    if($GET_JSON['code'] == 'success'){
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => $GET_JSON['data'][0],
        				];
    			    }else{
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => [
                				'code' => "400",
                				'msg'=>"找不到可用的播放地址"
                    		],
        				];
    			    }
    			    return INT(Request::instance()->param('type'),$data);
    				break;
    			}
    		}
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//全民k歌音乐解析
	public function kg(){
	    $Log_Kw = "kg";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $id = Request::instance()->param('id');
	    if(!empty($id) && isset($id)){
	        for ($i = 0; $i < 10; $i++) {
	            $music_input          = trim($id);
                $music_filter         = 'id';
                $music_type           = 'kg';
                $music_page           = (int) 1;
                $music = new \Musics();
	            $GET_JSON = $music->data($music_input,$music_filter,$music_type,$music_page);
    			if(is_array($GET_JSON)){
    			    if($GET_JSON['code'] == 'success'){
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => $GET_JSON['data'][0],
        				];
    			    }else{
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => [
                				'code' => "400",
                				'msg'=>"找不到可用的播放地址"
                    		],
        				];
    			    }
    			    return INT(Request::instance()->param('type'),$data);
    				break;
    			}
    		}
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//音乐链接解析
	public function MusicUrl(){
	    $Log_Kw = "MusicUrl";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $url = Request::instance()->param('url');
	    if(!empty($url) && isset($url)){
	        for ($i = 0; $i < 10; $i++) {
	            $music_input          = trim($url);
                $music_filter         = 'url';
                $music_type           = '_';
                $music_page           = (int) 1;
                $music = new \Musics();
	            $GET_JSON = $music->data($music_input,$music_filter,$music_type,$music_page);
    			if(is_array($GET_JSON)){
    			    if($GET_JSON['code'] == 'success'){
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => $GET_JSON['data'][0],
        				];
    			    }else{
    			        $data = [
        					'code' => 200,
        					'Copyright' => Copyright(),
        					"data" => [
                				'code' => "400",
                				'msg'=>"找不到可用的播放地址(推荐POST请求)"
                    		],
        				];
    			    }
    			    return INT(Request::instance()->param('type'),$data);
    				break;
    			}
    		}
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//qq强制聊天
	public function QQChat(){
	    $Log_Kw = "QQChat";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $qq = Request::instance()->param('qq');
	    if(!empty($qq) && isset($qq)){
	        header("Location: tencent://message/?uin=$qq");
			exit();
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//qq信息查询
	public function QQInfo(){
	    $Log_Kw = "QQInfo";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $QQ = Request::instance()->param('qq');
	    if(!empty($QQ) && isset($QQ)){
	        $urlPre='http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?g_tk=1518561325&uins=';
            $data=file_get_contents($urlPre.$QQ);
            $data=iconv("GB2312","UTF-8",$data);
            $pattern = '/portraitCallBack\((.*)\)/is';
            preg_match($pattern,$data,$result);
            $result=$result[1];
            $nickname = json_decode($result, true)["$QQ"][6];
            $headimg = "http://q1.qlogo.cn/g?b=qq&nk=$QQ&s=100&t=1547904810";
            $email = $QQ."@qq.com";
            $data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
    				'qq' => $QQ,
    				'nickname'=> $nickname,
    				'headimg'=> $headimg,
    				'email' => $email,
        		],
			];
		    return INT(Request::instance()->param('type'),$data);
	    }else{
	        $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
	    }
	}
	
	//抖音视频解析
	public function douyin(){
	    $Log_Kw = "douyin";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    function GetVideos($url) {
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_HEADER, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, ["user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25"]);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
		    $output = curl_exec($ch);
		    curl_close($ch);
		    return $output;
		}
		
		function GetUrl($url)
		{
		    $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
		    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    $data = curl_exec($curl);
		    curl_close($curl);
		    return $data;
		}
		//URL
		$url = Request::instance()->param('url');
		
		if (empty($url)) {
		    $data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
			return INT(Request::instance()->param('type'),$data);
		}else{
		    $data = GetUrl($url);
		    preg_match('/playAddr: "(?<url>[^"]+)"/i', $data, $url);
		    preg_match('/<p class="desc">(?<desc>[^<>]*)<\/p>/i', $data, $name);
		    preg_match('/<p class="name nowrap">(?<auth>[^<>]*)<\/p>/i', $data, $auth);
		    $name = $name['desc'];
		    $url = $url['url'];
		    if(empty($url))
		    {
		    	$data = [
					'code' => "400"
					,'msg'=>"解析错误"
				];
				return INT(Request::instance()->param('type'),$data);
		    }
		    
		    preg_match('/s_vid=(.*?)&/', $url, $id);
		    $url = 'https://aweme.snssdk.com/aweme/v1/play/?s_vid=' . $id[1] . '&line=0';
		    $data_new = GetVideos($url);
		    preg_match('/<a href=\"http:\/\/(.*?)\">/', $data_new, $link);
		    
		    if (empty($link[1])) {
		    	$data = [
					'code' => "400"
					,'msg'=>"解析错误"
				];
				return INT(Request::instance()->param('type'),$data);
		    }
		    $data = [
				'code' => "200"	,
				'Copyright' => Copyright(),
				'data' => [
					'msg'=>"解析成功",
					'name' => $name, 
					'auth' => $auth['auth'],
					'url' => 'http://' . $link[1]
				],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//qq凶吉接口
	public function qqxj(){
	    $Log_Kw = "qqxj";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		
		function randIp()
		{
		    return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
		}
		function GetUrl($url)
		{
			$header=[
		        'X-FORWARDED-FOR:'.randIp(),
		        'CLIENT-IP:'.randIp()
		    ];
		    $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
		    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    $data = curl_exec($curl);
		    curl_close($curl);
		    $res = mb_convert_encoding($data, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
		    return $res;
		}
		$QQ = Request::instance()->param('qq');
	    if(!empty($QQ) && isset($QQ)){
	    	if(Cache::store('redis')->get('qqxj_'.$QQ) == null){
	    		function compress_html($string){
					$string=str_replace("\r\n",'',$string);//清除换行符
					$string=str_replace("\n",'',$string);//清除换行符
					$string=str_replace("\t",'',$string);//清除制表符
					$pattern=array(
						"/> *([^ ]*) *</",//去掉注释标记
						"/[\s]+/",
						"/<!--[^!]*-->/",
						"/\" /",
						"/ \"/",
						"'/\*[^*]*\*/'"
						);
						$replace=array (
						">\\1<",
						" ",
						"",
						"\"",
						"\"",
						""
					);
					return preg_replace($pattern, $replace, $string);
				}
	    		$data = compress_html(GetUrl('http://qq.link114.cn/'.$QQ));
				preg_match('/<dd>(.*?)<\/dd>/i',$data,$a);
				preg_match('/<dd><font color="red">(.*?)<\/font>/i',$data,$b);
				preg_match('/<\/font>(.*?)<\/dd>/i',$data,$bs);
				preg_match('/<dl><dt>签文：<\/dt><dd>(.*?)<\/dd><\/dl>/i',$data,$c);
				preg_match('/<dl><dt>解签：<\/dt><dd>(.*?)<\/dd><\/dl>/i',$data,$d);
			    $data = [
					'xg' => @$a[1],
					'xyx' => @$b[1].@$bs[1],
					'qw' => @$c[1],
					'jq' => @$d[1]
				];
				Cache::store('redis')->set('qqxj_'.$QQ,json_encode($data));
	    	}else{
	    		$data = json_decode(trim(Cache::store('redis')->get('qqxj_'.$QQ),chr(239).chr(187).chr(191)),true);
	    	}
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				'data' => $data,
			];
	    }else{
	    	$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
	    }
	    return INT(Request::instance()->param('type'),$data);
	}
	
	//手机号凶吉接口
	public function telxj(){
	    $Log_Kw = "telxj";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		
		function randIp()
		{
		    return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
		}
		function GetUrl($url)
		{
			$header=[
		        'X-FORWARDED-FOR:'.randIp(),
		        'CLIENT-IP:'.randIp()
		    ];
		    $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
		    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    $data = curl_exec($curl);
		    curl_close($curl);
		    $res = mb_convert_encoding($data, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
		    return $res;
		}
		$tel = Request::instance()->param('tel');
	    if(!empty($tel) && isset($tel)){
	    	if(!preg_match("/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/
",$tel)){
				$data = [
					'code' => "400"
					,'msg'=>"手机号格式错误"
				];
		    	return INT(Request::instance()->param('type'),$data);
	        }
	    	if(Cache::store('redis')->get('telxj_'.$tel) == null){
	    		function compress_html($string){
					$string=str_replace("\r\n",'',$string);//清除换行符
					$string=str_replace("\n",'',$string);//清除换行符
					$string=str_replace("\t",'',$string);//清除制表符
					$pattern=array(
						"/> *([^ ]*) *</",//去掉注释标记
						"/[\s]+/",
						"/<!--[^!]*-->/",
						"/\" /",
						"/ \"/",
						"'/\*[^*]*\*/'"
						);
						$replace=array (
						">\\1<",
						" ",
						"",
						"\"",
						"\"",
						""
					);
					return preg_replace($pattern, $replace, $string);
				}
	    		$data = compress_html(GetUrl('https://jx.ip138.com/'.$tel.'/'));
				preg_match('/<td class="th"><p>凶吉推理<\/p><\/td><td colspan="3"><p>(.*?)<\/p><\/td><\/tr>/i',$data,$b);
				preg_match('/<td class="th"><p>暗示的信息：<\/p><\/td><td colspan="3">(.*?)<\/td><\/tr>/i',$data,$c);
				preg_match('/<td class="th"><p>诗云：<\/p><\/td><td colspan="3">(.*?)<\/td><\/tr>/i',$data,$d);
				preg_match('/<td class="th"><b>性格类型：<\/b><\/td><td><p>(.*?)<\/p><\/td><\/tr>/i',$data,$e);
				preg_match('/<td class="th"><p>具体表现：<\/b><\/td><td colspan="3">(.*?)<\/td><\/tr>/i',$data,$f);
				preg_match('/<td class="th"><p>谚语：<\/b><\/td><td colspan="3">(.*?)<\/td><\/tr>/i',$data,$g);
				$data = [
					'xiongji' => @$b[1],
					'anshi' => @$c[1],
					'shiyun' => @$d[1],
					'leixing' => @$e[1],
					'biaoxian' => @$f[1],
					'yanyu' => @$g[1],
				];
				Cache::store('redis')->set('telxj_'.$tel,json_encode($data));
	    	}else{
	    		$data = json_decode(trim(Cache::store('redis')->get('telxj_'.$tel),chr(239).chr(187).chr(191)),true);
	    	}
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				'data' => $data,
			];
	    }else{
	    	$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
	    }
	    return INT(Request::instance()->param('type'),$data);
	}
	
	//身份证查询
	public function idcard(){
	    $Log_Kw = "idcard";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		
		function randIp()
		{
		    return mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
		}
		function GetUrl($url)
		{
			$header=[
		        'X-FORWARDED-FOR:'.randIp(),
		        'CLIENT-IP:'.randIp()
		    ];
		    $UserAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36';
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
		    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    $data = curl_exec($curl);
		    curl_close($curl);
		    $res = mb_convert_encoding($data, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
		    return $res;
		}
		$idcard = Request::instance()->param('idcard');
	    if(!empty($idcard) && isset($idcard)){
	    	if(!preg_match('/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/',$idcard)){
				$data = [
					'code' => "400"
					,'msg'=>"身份证格式错误"
				];
		    	return INT(Request::instance()->param('type'),$data);
	        }
	    	if(Cache::store('redis')->get('idcard_s'.$idcard) == null){
	    		function compress_html($string){
					$string=str_replace("\r\n",'',$string);//清除换行符
					$string=str_replace("\n",'',$string);//清除换行符
					$string=str_replace("\t",'',$string);//清除制表符
					$pattern=array(
						"/> *([^ ]*) *</",//去掉注释标记
						"/[\s]+/",
						"/<!--[^!]*-->/",
						"/\" /",
						"/ \"/",
						"'/\*[^*]*\*/'"
						);
						$replace=array (
						">\\1<",
						" ",
						"",
						"\"",
						"\"",
						""
					);
					return preg_replace($pattern, $replace, $string);
				}
	    		$data = compress_html(GetUrl('https://shenfenzheng.51240.com/'.$idcard.'__shenfenzheng/'));
				preg_match('/<td bgcolor="#F5F5F5"align="center">证件号码<\/td><td bgcolor="#FFFFFF"align="center">(.*?)<\/td><\/tr>/i',$data,$a);
				preg_match('/<td bgcolor="#F5F5F5"align="center">发 证 地<\/td><td bgcolor="#FFFFFF"align="center">(.*?)<\/td><\/tr>/i',$data,$b);
				preg_match('/<td bgcolor="#F5F5F5"align="center">出生日期<\/td><td bgcolor="#FFFFFF"align="center">(.*?)<\/td><\/tr>/i',$data,$c);
				preg_match('/<td bgcolor="#F5F5F5"align="center">性别年龄<\/td><td bgcolor="#FFFFFF"align="center">(.*?)<\/td><\/tr>/i',$data,$d);
				$data = [
					'idcard' => @$a[1],
					'address' => @$b[1],
					'sr' => @$c[1],
					'age' => @$d[1],
				];
				Cache::store('redis')->set('idcard_s'.$idcard,json_encode($data));
	    	}else{
	    		$data = json_decode(trim(Cache::store('redis')->get('idcard_s'.$idcard),chr(239).chr(187).chr(191)),true);
	    	}
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				'data' => $data,
			];
	    }else{
	    	$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
	    }
	    return INT(Request::instance()->param('type'),$data);
	}
	
	//舔狗日记
	public function dog(){
	    $Log_Kw = "dog";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    
	    $num = 1;    //需要抽取的默认条数
	    $table = 'dog';    //需要抽取的数据表
	    $countcus = Db::name($table)->count();    //获取总记录数
	    $min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    $Db = Db::name($table)->where('id','in',$ary,'or')->select();
		if(is_array($Db)){
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => [
						'text' => $Db[0]['text'],
					],
			];
			return INT(Request::instance()->param('type'),$data);
		}
	}
	
	//快手视频解析
	public function kuaishou(){
	    $Log_Kw = "kuaishou";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    function kuaiShou($url){
		    $headers = [
	            'Connection' => 'keep-alive',
	            'User-Agent'=>'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36'
	        ];
	        $client = new Client(['headers'=>$headers]);
	
	        //允许重定向获取html
	        $res = $client->request('GET', $url,['allow_redirects' => true]);
	        $html = (string)$res->getBody();
	
	
	        $Query = QueryList::getInstance();
	        $json = $Query->html($html)->find('div[id=hide-pagedata]')->attr('data-pagedata');
	        $video_data = json_decode($json,true);
	        
	        //获取图片id
	        $photoId = $video_data['photoId'];
	        $param = "client_key=56c3713c&photoIds=".$photoId;
	        
	        //计算sign
	        $replace = str_replace("&", "",$param).'23caab00356c';
	        $sig =md5($replace);
			
	        $queryUrl = 'http://api.gifshow.com/rest/n/photo/info?'.$param."&sig=".$sig;
	        //获取url地址之后不能让他重定向
	        $res = $client->request('GET', $queryUrl,['allow_redirects' => false]);
	        $body = (string)$res->getBody();
	        $result = json_decode($body,true);
			var_dump($result);
	        //获取到视频相关数据
	        $video_data = $result['photos'][0];
	        $data['url']= $video_data['main_mv_url'];
	        $data['cover'] = $video_data['thumbnail_url'];
	
	        //获取文案标题
	        $video_title = $video_data['caption'];
	        $title =  explode('@',$video_title);
	        for ($i=0;$i<count($title);$i++){
	            $video_title = str_replace('@'.$title[$i],"",$video_title);
	        }
	
	        $title =  explode('#',$video_title);
	        for ($i=0;$i<count($title);$i++){
	            $video_title = str_replace('#'.$title[$i],"",$video_title);
	        }
	
	        $data['title'] = $video_title;
	        return $data;
		}
		kuaiShou('https://v.kuaishou.com/s/rbwIuaPK');
	}
	
	public function weishi(){
		$Log_Kw = "weishi";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		function weishi($url){
			preg_match('/feed\/(.*?)\/wsfeed/i',$url,$a);
	        $feedid = $a[1];
	        
	        $headers = [
	            'Connection' => 'keep-alive',
	            'User-Agent'=>'Mozilla/5.0 (iPhone; CPU iPhone OS 12_1_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/16D57 Version/12.0 Safari/604.1'
	        ];
	        $client = new Client(['headers'=>$headers]);
	
	        //允许重定向获取html
	        $baseUrl = 'https://h5.qzone.qq.com/webapp/json/weishi/WSH5GetPlayPage?t=0.4185745904612037&g_tk=';
	        $res = $client->request('POST', $baseUrl,['form_params' => ['feedid' => $feedid,]]);
	        $json = $res->getBody();
	        $result = json_decode($json,true);
	        if ($result['ret']==0){
	            $video = $result['data']['feeds'][0];
	            $data['url']= $video['video_url'];
	            $data['cover'] = $video['images'][0]['url'];
	
	            $video_title =  $video['feed_desc'];
	            $title =  explode('@',$video_title);
	            for ($i=0;$i<count($title);$i++){
	                $video_title = str_replace('@'.$title[$i],"",$video_title);
	            }
	            $title =  explode('#',$video_title);
	            for ($i=0;$i<count($title);$i++){
	                $video_title = str_replace('#'.$title[$i],"",$video_title);
	            }
	
	            $data['title'] = $video_title;
	            $data['width'] = $video['images'][0]['width'];
	            $data['height'] = $video['images'][0]['height'];
	            return $data;
	        }
	
	    }
	    $url = Request::instance()->param('url');
	    if(!empty($url) && isset($url)){
	    	$data = weishi($url);
	    	$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				'data' => $data,
			];
	    }else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
	    }
	    return INT(Request::instance()->param('type'),$data);
	}
	
	//皮皮虾解析
	public function pipixia(){
	    $Log_Kw = "pipixia";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
	    function pipixia($url){
	    	$header = get_headers($url, 1);
			if (strpos($header[0], '301') !== false || strpos($header[0], '302') !== false) {
				if(is_array($header['Location'])) {
					return $header['Location'][count($header['Location'])-1];
				}else{
					return $header['Location'];
				}
			}else {
				return $url;
			}
		}
		$url = Request::instance()->param('url');
	    if(!empty($url) && isset($url)){
	    	$data = pipixia($url);
			preg_match('/item\/(.*?)\?/i',$data,$a);
			$url = 'https://h5.pipix.com/bds/webapi/item/detail/?item_id='.$a[1];
		    $client = new Client();
			$res = $client->request('GET', $url, [
			    'headers' => [
			        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',
			    ]
			]);
			$html = (string)$res->getBody();
			$data = json_decode($html);
			$url = $data->data->item->origin_video_download->url_list[0]->url;
			$auth = $data->data->item->author->name;
			$title = $data->data->item->share->title;
	    	$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				'data' => [
					'title' => $title,
					'author' => $auth,
					'url' => $url,
				],
			];
	    }else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
	    }
	    return INT(Request::instance()->param('type'),$data);
	}
	
	//秒拍视频解析
	public function miaopai(){
	    $Log_Kw = "miaopai";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
		$url = Request::instance()->param('url');
	    if(!empty($url) && isset($url)){
			preg_match('/\/show\/(.*?).htm/i',$url,$a);
			$url = 'http://n.miaopai.com/api/aj_media/info.json?smid='.$a[1].'&appid=530&_cb=_jsonp';
		    $client = new Client();
			$res = $client->request('GET', $url, [
			    'headers' => [
			    	'Referer' => 'http://n.miaopai.com/media/W7cS7ai4VdsYjzO7D0Cc7sV9sZCSf2t9.htm',
			        'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36',
			    ]
			]);
			$html = (string)$res->getBody();
			preg_match('/json":"(.*?)"/', $html, $res);
			$data = json_decode('{"url":"'.$res[1].'"}');
			$url = $data->url;
		    $client = new Client();
			$res = $client->request('GET', $url, [
			    'headers' => [
			    	'Referer' => 'http://n.miaopai.com/media/W7cS7ai4VdsYjzO7D0Cc7sV9sZCSf2t9.htm',
			        'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36',
			    ]
			]);
			$html = (string)$res->getBody();
			$data = json_decode($html);
	    	$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				'data' => [
					'url' => $data->result[0]->scheme.$data->result[0]->host.$data->result[0]->path,
				],
			];
	    }else{
			$data = [
				'code' => "400"
				,'msg'=>"参数错误"
			];
	    }
	    return INT(Request::instance()->param('type'),$data);
	}
}
