<?php
namespace app\api\controller;
use \think\Request;
use \think\Db;
use \think\Session;
use think\Cache;

class Page
{
    public function index(){
		return json(['code'=>400,'msg'=>'接口错误','api'=>""]);
	}
	
	//热评分页
	public function netease(){
	    $Log_Kw = "netease";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
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
		$table = 'hot';
		$page_num = 20;
		if(Request::instance()->param('page') == "love"){
			$id = Request::instance()->param('id');
			$ip = get_ip();
			$time = time();
			$IsID = Db::table($table)->where('id',$id)->find();
			if($IsID != null){
				if(Db::table('likes')->where('cid',$id)->where('ip',$ip)->where('keyword',$table)->find() == null){
					if(Db::table('likes')->data(['cid'=>$id,'keyword'=>$table,'time'=>$time,'ip'=>$ip])->insert() && Db::table($table)->where('id', $id)->setInc('love')){
						$data = [
							'code' => "200",
							'msg' => "点赞成功",
							'love' => Db::table($table)->where('id',$id)->find()['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}else{
						$data = [
							'code' => "400",
							'msg' => "点赞失败",
							'love' => $IsID['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}
				}else{
					$data = [
						'code' => "201",
						'msg' => "已点赞过了",
						'love' => $IsID['love'],
					];
					return INT(Request::instance()->param('type'),$data);
				}
			}else {
				$data = [
					'code' => "400",
					'msg'=>"ID不存在"
				];
				return INT(Request::instance()->param('type'),$data);
			}
			exit();
		}
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//签名分页
	public function autograph(){
	    $Log_Kw = "autograph";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
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
		$table = 'autograph';
		$page_num = 20;
		if(Request::instance()->param('page') == "love"){
			$id = Request::instance()->param('id');
			$ip = get_ip();
			$time = time();
			$IsID = Db::table($table)->where('id',$id)->find();
			if($IsID != null){
				if(Db::table('likes')->where('cid',$id)->where('ip',$ip)->where('keyword',$table)->find() == null){
					if(Db::table('likes')->data(['cid'=>$id,'keyword'=>$table,'time'=>$time,'ip'=>$ip])->insert() && Db::table($table)->where('id', $id)->setInc('love')){
						$data = [
							'code' => "200",
							'msg' => "点赞成功",
							'love' => Db::table($table)->where('id',$id)->find()['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}else{
						$data = [
							'code' => "400",
							'msg' => "点赞失败",
							'love' => $IsID['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}
				}else{
					$data = [
						'code' => "201",
						'msg' => "已点赞过了",
						'love' => $IsID['love'],
					];
					return INT(Request::instance()->param('type'),$data);
				}
			}else {
				$data = [
					'code' => "400",
					'msg'=>"ID不存在"
				];
				return INT(Request::instance()->param('type'),$data);
			}
			exit();
		}
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//暖心情话分页
	public function love(){
	    $Log_Kw = "love";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
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
		$table = 'love';
		$page_num = 20;
		if(Request::instance()->param('page') == 'index'){
			$count = Db::name($table)->count();
			$pageCount = ceil($count/10);
			$Db = Db::table($table)->order('id','desc')->limit(10)->page(rand(1,$pageCount))->select();
			$data = [
				'code' => 200,
				'Copyright' => Copyright(),
				"data" => $Db
			];
			return INT(Request::instance()->param('type'),$data);
			exit();
		}
		if(Request::instance()->param('page') == "love"){
			$id = Request::instance()->param('id');
			$ip = get_ip();
			$time = time();
			$IsID = Db::table($table)->where('id',$id)->find();
			if($IsID != null){
				if(Db::table('likes')->where('cid',$id)->where('ip',$ip)->where('keyword',$table)->find() == null){
					if(Db::table('likes')->data(['cid'=>$id,'keyword'=>$table,'time'=>$time,'ip'=>$ip])->insert() && Db::table($table)->where('id', $id)->setInc('love')){
						$data = [
							'code' => "200",
							'msg' => "点赞成功",
							'love' => Db::table($table)->where('id',$id)->find()['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}else{
						$data = [
							'code' => "400",
							'msg' => "点赞失败",
							'love' => $IsID['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}
				}else{
					$data = [
						'code' => "201",
						'msg' => "已点赞过了",
						'love' => $IsID['love'],
					];
					return INT(Request::instance()->param('type'),$data);
				}
			}else {
				$data = [
					'code' => "400",
					'msg'=>"ID不存在"
				];
				return INT(Request::instance()->param('type'),$data);
			}
			exit();
		}
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//网名分页
	public function name(){
	    $Log_Kw = "name";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
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
		$table = 'name';
		$page_num = 20;
		if(Request::instance()->param('page') == "love"){
			$id = Request::instance()->param('id');
			$ip = get_ip();
			$time = time();
			$IsID = Db::table($table)->where('id',$id)->find();
			if($IsID != null){
				if(Db::table('likes')->where('cid',$id)->where('ip',$ip)->where('keyword',$table)->find() == null){
					if(Db::table('likes')->data(['cid'=>$id,'keyword'=>$table,'time'=>$time,'ip'=>$ip])->insert() && Db::table($table)->where('id', $id)->setInc('love')){
						$data = [
							'code' => "200",
							'msg' => "点赞成功",
							'love' => Db::table($table)->where('id',$id)->find()['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}else{
						$data = [
							'code' => "400",
							'msg' => "点赞失败",
							'love' => $IsID['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}
				}else{
					$data = [
						'code' => "201",
						'msg' => "已点赞过了",
						'love' => $IsID['love'],
					];
					return INT(Request::instance()->param('type'),$data);
				}
			}else {
				$data = [
					'code' => "400",
					'msg'=>"ID不存在"
				];
				return INT(Request::instance()->param('type'),$data);
			}
			exit();
		}
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//说说分页
	public function shuoshuo(){
	    $Log_Kw = "shuoshuo";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
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
		$table = 'shuoshuo';
		$page_num = 20;
		if(Request::instance()->param('page') == "love"){
			$id = Request::instance()->param('id');
			$ip = get_ip();
			$time = time();
			$IsID = Db::table($table)->where('id',$id)->find();
			if($IsID != null){
				if(Db::table('likes')->where('cid',$id)->where('ip',$ip)->where('keyword',$table)->find() == null){
					if(Db::table('likes')->data(['cid'=>$id,'keyword'=>$table,'time'=>$time,'ip'=>$ip])->insert() && Db::table($table)->where('id', $id)->setInc('love')){
						$data = [
							'code' => "200",
							'msg' => "点赞成功",
							'love' => Db::table($table)->where('id',$id)->find()['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}else{
						$data = [
							'code' => "400",
							'msg' => "点赞失败",
							'love' => $IsID['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}
				}else{
					$data = [
						'code' => "201",
						'msg' => "已点赞过了",
						'love' => $IsID['love'],
					];
					return INT(Request::instance()->param('type'),$data);
				}
			}else {
				$data = [
					'code' => "400",
					'msg'=>"ID不存在"
				];
				return INT(Request::instance()->param('type'),$data);
			}
			exit();
		}
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//一言分页
	public function word(){
	    $Log_Kw = "word";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
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
		$table = 'word';
		$page_num = 20;
		if(Request::instance()->param('page') == "love"){
			$id = Request::instance()->param('id');
			$ip = get_ip();
			$time = time();
			$IsID = Db::table($table)->where('id',$id)->find();
			if($IsID != null){
				if(Db::table('likes')->where('cid',$id)->where('ip',$ip)->where('keyword',$table)->find() == null){
					if(Db::table('likes')->data(['cid'=>$id,'keyword'=>$table,'time'=>$time,'ip'=>$ip])->insert() && Db::table($table)->where('id', $id)->setInc('love')){
						$data = [
							'code' => "200",
							'msg' => "点赞成功",
							'love' => Db::table($table)->where('id',$id)->find()['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}else{
						$data = [
							'code' => "400",
							'msg' => "点赞失败",
							'love' => $IsID['love'],
						];
						return INT(Request::instance()->param('type'),$data);
					}
				}else{
					$data = [
						'code' => "201",
						'msg' => "已点赞过了",
						'love' => $IsID['love'],
					];
					return INT(Request::instance()->param('type'),$data);
				}
			}else {
				$data = [
					'code' => "400",
					'msg'=>"ID不存在"
				];
				return INT(Request::instance()->param('type'),$data);
			}
			exit();
		}
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//调用统计分页
	public function log(){
	    if(Request::instance()->param('page') == 'day'){
	        $today = Db::table('log')->whereTime('time', 'today')->count();
            //今天数据
            
            $yesterday = Db::table('log')->whereTime('time', 'yesterday')->count();
            //昨天数据
            $day2 = Db::table('log')->whereTime('time','between',[date("Y-m-d",strtotime("-2 day")),date("Y-m-d",strtotime("-1 day"))])->count();
            //两天前数据
            
            $day3 = Db::table('log')->whereTime('time','between',[date("Y-m-d",strtotime("-3 day")),date("Y-m-d",strtotime("-2 day"))])->count();
            //三天前数据
            
            $day4 = Db::table('log')->whereTime('time','between',[date("Y-m-d",strtotime("-4 day")),date("Y-m-d",strtotime("-3 day"))])->count();
            //四天前数据
            
            $day5 = Db::table('log')->whereTime('time','between',[date("Y-m-d",strtotime("-5 day")),date("Y-m-d",strtotime("-4 day"))])->count();
            //五天前数据
            
            $day6 = Db::table('log')->whereTime('time','between',[date("Y-m-d",strtotime("-6 day")),date("Y-m-d",strtotime("-5 day"))])->count();
            //六天前数据
            
            $count = Db::table('log')->count();
            //总数据
            
            $week = Db::table('log')->whereTime('time', 'week')->count();
            //本周数据
            
            $month = Db::table('log')->whereTime('time', 'month')->count();
            //本月数据
            
            $data = [
                'count' => $count,
                'week' => $week,
                'month' => $month,
    			'today' => $today,
    			'yesterday' => $yesterday,
    			'day2' => $day2,
    			'day3' => $day3,
    			'day4' => $day4,
    			'day5' => $day5,
    			'day6' => $day6,
    		];
    		return INT(Request::instance()->param('type'),$data);
	    }
	    
		$table = 'log';
		$page_num = 20;
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$reg_Db = [];
		$reg = '/((?:\d+\.){3})\d+/';
		for ($i = 0; $i < count($Db); $i++) {
			 $reg_Db[] = [
			 	'id' => $Db[$i]['id'],
			 	'name' => $Db[$i]['name'],
			 	'ip' => preg_replace($reg, "\\1*", $Db[$i]['ip']),
			 	'time' => date("Y-m-d H:i:s",$Db[$i]['time']),
			 	'request' => $Db[$i]['request'],
			 ];
		}
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $reg_Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//点赞记录分页
	public function likes(){
		$table = 'likes';
		$page_num = 20;
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$reg_Db = [];
		$reg = '/((?:\d+\.){3})\d+/';
		for ($i = 0; $i < count($Db); $i++) {
			 $reg_Db[] = [
			 	'id' => $Db[$i]['id'],
			 	'cid' => $Db[$i]['cid'],
			 	'keyword' => $Db[$i]['keyword'],
			 	'time' => $Db[$i]['time'],
			 	'ip' => preg_replace($reg, "\\1*", $Db[$i]['ip']),
			 ];
		}
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $reg_Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
	
	//头像分页
	public function headimg(){
	    $Log_Kw = "headimg";
	    Db::table('log')->data([
            'name'=>$Log_Kw,
            'time'=>time(),
            'UserAgent'=> $_SERVER['HTTP_USER_AGENT'],
            'request' => Request::instance()->method(),
            'ip'=>get_ip(),
        ])->insert();
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
	    if(Request::instance()->param('kw') != ""){
	        $table = Request::instance()->param('kw');
	    }else{
	        $table = "女生头像";
	    }
		$page_num = 20;
		$page = Request::instance()->param('page');
		if(empty($page)){
			$page = 1;
		}
		$count = Db::name($table)->count();
		$pageCount = ceil($count/$page_num);
		$Db = Db::table($table)->order('id','desc')->limit($page_num)->page($page)->select();
		$reg_Db = [];
		for ($i = 0; $i < count($Db); $i++) {
			 $reg_Db[] = [
			 	'id' => $Db[$i]['id'],
			 	'url' => $Db[$i]['url'],
			 ];
		}
		$data = [
			'code' => 200,
			'Copyright' => Copyright(),
			'conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$page],
			"data" => $reg_Db
		];
		return INT(Request::instance()->param('type'),$data);
	}
}
