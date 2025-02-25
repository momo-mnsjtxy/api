<?php
namespace app\index\controller;
use think\Request;
use think\Db;
use think\Session;
use think\Cookie;
use think\Controller;

class Index extends Controller
{
    public function LoginOut(){
        if(!Cookie::has('UID') || !Cookie::has('UserName') || !Session::has('UID') || !Session::has('Email') || !Session::has('UserName') || Cookie::get('UID') != Session::get('UID') || Cookie::get('UserName') != Session::get('UserName')){
            $this->redirect('login/index/index')->remember();
            exit();
        }
        //判断是否登录
        Cookie::delete('UID');
        Cookie::delete('UID');
        Cookie::delete('UserName');
        Session::delete('UID');
        Session::delete('Email');
        Session::delete('UserName');
        $this->success('退出登录成功', 'login/index/index');
    }
    
    public function ip(){
        if(!Cookie::has('UID') || !Cookie::has('UserName') || !Session::has('UID') || !Session::has('Email') || !Session::has('UserName') || Cookie::get('UID') != Session::get('UID') || Cookie::get('UserName') != Session::get('UserName')){
            $this->redirect('login/index/index')->remember();
            exit();
        }
        //判断是否登录
        $CheckIP = Request::instance()->param('address');
        for ($i = 0; $i < 10; $i++) {
		    //http://opendata.baidu.com/api.php?query=1.85.35.131&co=&resource_id=6006&t=1329357746681&ie=utf8&oe=gbk&format=json&tn=baidu
			$GET_JSON = GET_JSON('http://ip-api.com/json/'.$CheckIP.'?lang=zh-CN');
			if(is_array($GET_JSON)){
				return json($GET_JSON);
				break;
			}
		}
    }
    
    public function index(){
        if(!Cookie::has('UID') || !Cookie::has('UserName') || !Session::has('UID') || !Session::has('Email') || !Session::has('UserName') || Cookie::get('UID') != Session::get('UID') || Cookie::get('UserName') != Session::get('UserName')){
            $this->redirect('login/index/index')->remember();
            exit();
        }
        //判断是否登录
        
        $api = Db::table('api')
        ->where('Code',1)
        ->select(); 
        $this->assign('api',$api);
        //获取导航栏API列表
        
        $user = Db::table('user')
        ->where('UID',Session::get('UID'))
        ->where('UserName',Session::get('UserName'))
        ->find(); 
        $this->assign('user',$user);
        //获取用户信息
        
        if(strpos($user['Email'], "@qq.com")){
            $headimg = 'https://q.qlogo.cn/g?b=qq&nk='.substr($user['Email'],0,strrpos($user['Email'],"@")).'&s=100';
        }else{
            $headimg = 'https://cdn.v2ex.com/gravatar/'.md5($user['Email']).'?s=65&r=G&d';
        }
        $this->assign('headimg',$headimg);
        //获取用户头像
        
        $today = Db::table('request')->where("UID",$user['UID'])->whereTime('TIME', 'today')->count();
        $this->assign('today',$today);
        //今天数据
        
        $yesterday = Db::table('request')->where("UID",$user['UID'])->whereTime('TIME', 'yesterday')->count();
        $this->assign('yesterday',$yesterday);
        //昨天数据
        
        $this->assign('day2',Db::table('request')->where("UID",$user['UID'])->where('TIME','between time',[date("m-d",strtotime("-2 day")),date("m-d",strtotime("-2 day"))])->count());
        //两天前数据
        
        $this->assign('day3',Db::table('request')->where("UID",$user['UID'])->where('TIME','between time',[date("m-d",strtotime("-3 day")),date("m-d",strtotime("-3 day"))])->count());
        //三天前数据
        
        $this->assign('day4',Db::table('request')->where("UID",$user['UID'])->where('TIME','between time',[date("m-d",strtotime("-4 day")),date("m-d",strtotime("-4 day"))])->count());
        //四天前数据
        
        $this->assign('day5',Db::table('request')->where("UID",$user['UID'])->where('TIME','between time',[date("m-d",strtotime("-5 day")),date("m-d",strtotime("-5 day"))])->count());
        //五天前数据
        
        $this->assign('day6',Db::table('request')->where("UID",$user['UID'])->where('TIME','between time',[date("m-d",strtotime("-6 day")),date("m-d",strtotime("-6 day"))])->count());
        //六天前数据
        
        $this->assign('num30',Db::table('request')->where("UID",$user['UID'])->order('id','desc')->limit(30)->field('TIME,UserAgent,IP,Request')->select());
        //最近调用数据
        
        if($today && $yesterday){
            if($today<$yesterday){
                $this->assign('todayTo',round($today/$yesterday*100,2));
            }else{
                $this->assign('todayTo',round($yesterday/$today*100,2));
            }
        }else{
            $this->assign('todayTo',0);
        }
        //对比昨天数据
        
        $week = Db::table('request')->where("UID",$user['UID'])->whereTime('TIME', 'week')->count();
        $this->assign('week',$week);
        //本周数据
        
        $lastweek = Db::table('request')->where("UID",$user['UID'])->whereTime('TIME', 'last week')->count();
        $this->assign('lastweek',$lastweek);
        //上周数据
        
        if($week && $lastweek){
            if($week<$lastweek){
                $this->assign('weekTo',round($week/$lastweek*100,2));
            }else{
                $this->assign('weekTo',round($lastweek/$week*100,2));
            }
        }else{
            $this->assign('weekTo',0);
        }
        //本周上周对比
        
        $month = Db::table('request')->where("UID",$user['UID'])->whereTime('TIME', 'month')->count();
        $this->assign('month',$month);
        //本月数据
        
        $lastmonth = Db::table('request')->where("UID",$user['UID'])->whereTime('TIME', 'last month')->count();
        $this->assign('lastmonth',$lastmonth);
        //上月数据
        
        if($month && $lastmonth){
            if($month<$lastmonth){
                $this->assign('monthTo',round($month/$lastmonth*100,2));
            }else{
                $this->assign('monthTo',round($lastmonth/$month*100,2));
            }
        }else{
            $this->assign('monthTo',0);
        }
        //本月跟上月对比
        
        $count = Db::table('request')->where("UID",$user['UID'])->count();
        $this->assign('count',$count);
        //总调用数据
        
        $securityCount = Db::table('request')->where("UID",$user['UID'])->where("Code",1)->count();
        $this->assign('securityCount',$securityCount);
        //总拦截数量
        
        if($count && $securityCount){
            if($count<$securityCount){
                $this->assign('securityTo',round($count/$securityCount*100,2));
            }else{
                $this->assign('securityTo',round($securityCount/$count*100,2));
            }
        }else{
            $this->assign('securityTo',0);
        }
        //拦截数量占比
        
		return view();
		//渲染模板
	}
	public function page(){
        if(!Cookie::has('UID') || !Cookie::has('UserName') || !Session::has('UID') || !Session::has('Email') || !Session::has('UserName') || Cookie::get('UID') != Session::get('UID') || Cookie::get('UserName') != Session::get('UserName')){
            $this->redirect('login/index/index')->remember();
            exit();
        }
        //判断是否登录
        
        $api = Db::table('api')
        ->where('Code',1)
        ->select(); 
        $this->assign('api',$api);
        //获取导航栏API列表
        
        $user = Db::table('user')
        ->where('UID',Session::get('UID'))
        ->where('UserName',Session::get('UserName'))
        ->find(); 
        $this->assign('user',$user);
        //获取用户信息
        
        if(strpos($user['Email'], "@qq.com")){
            $headimg = 'https://q.qlogo.cn/g?b=qq&nk='.substr($user['Email'],0,strrpos($user['Email'],"@")).'&s=100';
        }else{
            $headimg = 'https://cdn.v2ex.com/gravatar/'.md5($user['Email']).'?s=65&r=G&d';
        }
        $this->assign('headimg',$headimg);
        //获取用户头像
        $Keyword = Request::instance()->param('api');
        $IsAID = Db::table('api')
        ->where('Keyword',$Keyword)
        ->find(); 
        if($IsAID == null){
            $this->error('接口不存在');
        }
        $page = Db::table('api')
        ->where('Keyword',$Keyword)
        ->find(); 
        if($page['Code'] == 0){
            $this->error('接口异常已经被关闭');
        }
        if(Request::instance()->param('type') == 'list'){
            if(!Request::instance()->param('p')){
                $p = 1;
            }else {
                $p = Request::instance()->param('p');
            }
            $count = Db::name('request')->count();
    		$pageCount = ceil($count/15);
    		$Db = Db::table('request')->where("UID",$user['UID'])->where("Keyword",$page['Keyword'])->order('id','desc')->limit(15)->page($p)->field('TIME,UserAgent,IP,Request')->select();
    		return json(['conf'=>['count'=>$count,'PageCount'=>$pageCount,'Page'=>$p],'data'=>$Db]);
        }
        $action = json_decode(trim($page['action'],chr(239).chr(187).chr(191)),true);
        $parameter = '';
        for ($i = 0; $i < count($action); $i++) {
            $parameter .= '<tr>';
            for ($a = 0; $a < count($action[$i]); $a++) {
                $parameter .='<td>'.$action[$i][$a].'</td>';
            }
            $parameter .= '</tr>';
        }
        $this->assign('parameter',$parameter);
        $this->assign('page',$page);
        $today = Db::table('request')->where("UID",$user['UID'])->where("Keyword",$page['Keyword'])->whereTime('TIME', 'today')->count();
        $this->assign('today',$today);
        //今天数据
        
        $yesterday = Db::table('request')->where("UID",$user['UID'])->where("Keyword",$page['Keyword'])->whereTime('TIME', 'yesterday')->count();
        $this->assign('yesterday',$yesterday);
        //昨天数据
        
        $count = Db::table('request')->where("UID",$user['UID'])->where("Keyword",$page['Keyword'])->count();
        $this->assign('count',$count);
        //总调用数据
        
        $securityCount = Db::table('request')->where("UID",$user['UID'])->where("Keyword",$page['Keyword'])->where("Code",1)->count();
        $this->assign('securityCount',$securityCount);
        //总拦截数量
        
        if($today && $yesterday){
            if($today<$yesterday){
                $this->assign('todayTo',round($today/$yesterday*100,2));
            }else{
                $this->assign('todayTo',round($yesterday/$today*100,2));
            }
        }else{
            $this->assign('todayTo',0);
        }
        //对比昨天数据
        
        return view();
	}
	
	public function setting(){
        if(!Cookie::has('UID') || !Cookie::has('UserName') || !Session::has('UID') || !Session::has('Email') || !Session::has('UserName') || Cookie::get('UID') != Session::get('UID') || Cookie::get('UserName') != Session::get('UserName')){
            $this->redirect('login/index/index')->remember();
            exit();
        }
        //判断是否登录
        
        $api = Db::table('api')
        ->where('Code',1)
        ->select(); 
        $this->assign('api',$api);
        //获取导航栏API列表
        
        $user = Db::table('user')
        ->where('UID',Session::get('UID'))
        ->where('UserName',Session::get('UserName'))
        ->find(); 
        $this->assign('user',$user);
        //获取用户信息
        
        if(Request::instance()->param('type') == 'doEmail'){
            $RegisterCode = rand(100000,999999);
            Session::set('RePass',$RegisterCode);
            $result = email('',$user['Email'],'','梦城API修改密码验证','您的验证码是 '.$RegisterCode,'','');
            if(is_array($result) && $result['code'] == 1){
                return json(['code'=>200,'msg'=>'验证码已经发送到您的邮箱']);
			}else{
			    return json(['code'=>400,'msg'=>'抱歉，邮件发送失败，请联系管理员']);
			}
        }
        
        if(Request::instance()->param('type') == 'RePass'){
            $Password = trim(Request::instance()->post('Password'));
            $Email_Code = trim(Request::instance()->post('Email_Code'));
            if(!Request::instance()->has('Password','post')){
    	        return json(['code'=>400,'msg'=>'请输入您要设置的新的密码']);
    	    }elseif(!Request::instance()->has('Email_Code','post')){
    	        return json(['code'=>400,'msg'=>'请输入6位数邮箱验证码']);
    	    }elseif((int)$Email_Code != Session::get('RePass')){
                return json(['code'=>400,'msg'=>'抱歉！邮箱验证码错误']);
            }elseif(Db::table('user')->where('UID',$user['UID'])->where('Password',md5($Password))->find()){
                return json(['code'=>400,'msg'=>'新密码不能跟旧密码相同']);
            }else if(Db::table('user')->where('UID', $user['UID'])->update(['Password' => md5($Password)])){
                Session::delete('RePass');
    	        return json(['code'=>200,'msg'=>'恭喜你！密码修改成功']);
    	    }else{
    	        return json(['code'=>400,'msg'=>'抱歉！修改失败。请重试']);
    	    }
        }
        
        if(Request::instance()->param('type') == 'ReEmailCode'){
            $Email = trim(Request::instance()->post('Email'));
            if(Db::table('user')->where('UID',$user['UID'])->where('Email',$Email)->find()){
                return json(['code'=>400,'msg'=>'新邮箱不能跟旧邮箱相同']);
            }
            $RegisterCode = rand(100000,999999);
            Session::set('ReEmail',$RegisterCode);
            $result = email('',$Email,'','梦城API修改绑定验证','您的验证码是 '.$RegisterCode,'','');
            if(is_array($result) && $result['code'] == 1){
                return json(['code'=>200,'msg'=>'验证码已经发送到您的邮箱']);
			}else{
			    return json(['code'=>400,'msg'=>'抱歉，邮件发送失败，请联系管理员']);
			}
        }
        
        if(Request::instance()->param('type') == 'ReEmail'){
            $Email = trim(Request::instance()->post('Email'));
            $EmailCode = trim(Request::instance()->post('EmailCode'));
            if(!Request::instance()->has('Email','post')){
    	        return json(['code'=>400,'msg'=>'请输入您要设置的新的密码']);
    	    }elseif(!Request::instance()->has('EmailCode','post')){
    	        return json(['code'=>400,'msg'=>'请输入6位数邮箱验证码']);
    	    }elseif((int)$EmailCode != Session::get('ReEmail')){
                return json(['code'=>400,'msg'=>'抱歉！邮箱验证码错误']);
            }elseif(Db::table('user')->where('UID',$user['UID'])->where('Email',$Email)->find()){
                return json(['code'=>400,'msg'=>'新邮箱不能跟旧邮箱相同']);
            }else if(Db::table('user')->where('UID', $user['UID'])->update(['Email' => $Email])){
                Session::delete('ReEmail');
    	        return json(['code'=>200,'msg'=>'恭喜你！邮箱绑定修改成功']);
    	    }else{
    	        return json(['code'=>400,'msg'=>'抱歉！修改失败。请重试']);
    	    }
        }
        
        if(strpos($user['Email'], "@qq.com")){
            $headimg = 'https://q.qlogo.cn/g?b=qq&nk='.substr($user['Email'],0,strrpos($user['Email'],"@")).'&s=100';
        }else{
            $headimg = 'https://cdn.v2ex.com/gravatar/'.md5($user['Email']).'?s=65&r=G&d';
        }
        $this->assign('headimg',$headimg);
        //获取用户头像
        return view();
    }
    
    public function appkey(){
        if(!Cookie::has('UID') || !Cookie::has('UserName') || !Session::has('UID') || !Session::has('Email') || !Session::has('UserName') || Cookie::get('UID') != Session::get('UID') || Cookie::get('UserName') != Session::get('UserName')){
            $this->redirect('login/index/index')->remember();
            exit();
        }
        //判断是否登录
        
        $api = Db::table('api')
        ->where('Code',1)
        ->select(); 
        $this->assign('api',$api);
        //获取导航栏API列表
        
        $user = Db::table('user')
        ->where('UID',Session::get('UID'))
        ->where('UserName',Session::get('UserName'))
        ->find(); 
        $this->assign('user',$user);
        //获取用户信息
        
        if(Request::instance()->param('type') == 'APPKEY'){
            $APPKEY = md5(rand(1000000000,9999999999).time());
            if(Db::table('user')->where('UID', $user['UID'])->update(['APPKEY' => $APPKEY])){
                return json(['code'=>200,'msg'=>'新的APPKEY是：'.$APPKEY,'New'=>$APPKEY] );
			}else{
			    return json(['code'=>400,'msg'=>'抱歉，重置成功，请联系管理员']);
			}
        }
        
        if(strpos($user['Email'], "@qq.com")){
            $headimg = 'https://q.qlogo.cn/g?b=qq&nk='.substr($user['Email'],0,strrpos($user['Email'],"@")).'&s=100';
        }else{
            $headimg = 'https://cdn.v2ex.com/gravatar/'.md5($user['Email']).'?s=65&r=G&d';
        }
        $this->assign('headimg',$headimg);
        //获取用户头像
        return view();
    }
    
    public function log(){
        echo '开发中';
    }
}
