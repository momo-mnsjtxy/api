<?php
namespace app\login\controller;
use think\Request;
use think\Db;
use think\Session;
use think\Cookie;
use think\Controller;

class Index extends Controller
{
    public function index(){
        if(Cookie::has('UID') && Cookie::has('UserName') && Session::has('UID') && Session::has('Email') && Session::has('UserName') && Cookie::get('UID') == Session::get('UID') && Cookie::get('UserName') == Session::get('UserName')){
            $this->success('您已登陆', 'index/index/index');
        }
		return view();
	}
	public function GtCode(){
	    if(Cookie::has('UID') && Cookie::has('UID') && Cookie::has('UserName') && Session::has('UID') && Session::has('Email') && Session::has('UserName') && Cookie::get('UID') == Session::get('UID') && Cookie::get('UserName') == Session::get('UserName')){
            $this->success('您已登陆', 'index/index/index');
        }
	    header('Content-type:application/json; charset=utf-8');
	    $GtSdk = new \GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
        $data = array(
    		"class" => "Login", # 网站用户id
    		"client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
    		"ip_address" => $_SERVER['REMOTE_ADDR'] # 请在此处传输用户请求验证时所携带的IP
    	);
        $status = $GtSdk->pre_process($data, 1);
        Session::set('gtserver',$status);
        Session::set('class',$data['class']);
        echo $GtSdk->get_response_str();
        exit();
	}
	public function callback(){
	    if(Cookie::has('UID') && Cookie::has('UID') && Cookie::has('UserName') && Session::has('UID') && Session::has('Email') && Session::has('UserName') && Cookie::get('UID') == Session::get('UID') && Cookie::get('UserName') == Session::get('UserName')){
            $this->success('您已登陆', 'index/index/index');
        }
	    $GtSdk = new \GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
        $data = array(
    		"class" => "Login", # 网站用户id
    		"client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
    		"ip_address" => $_SERVER['REMOTE_ADDR'] # 请在此处传输用户请求验证时所携带的IP
    	);
        if (Session::get('gtserver') == 1) {   //服务器正常
            $result = $GtSdk->success_validate(Request::instance()->post('geetest_challenge'), Request::instance()->post('geetest_validate'), Request::instance()->post('geetest_seccode'), $data);
            if ($result) {
                $UserName = trim(Request::instance()->post('UserName'));
                $Password = trim(Request::instance()->post('Password'));
                if(!Request::instance()->has('UserName','post')){
        	        return json(['code'=>400,'msg'=>'请输入邮箱或用户名']);
        	    }elseif (!Request::instance()->has('Password','post')) {
                    return json(['code'=>400,'msg'=>'请输入您的登陆密码']);
                }else{
                    if(strpos($UserName, "@")){
                        $UserInfo = Db::table('user')->where('Email',$UserName)->where('Password',md5($Password))->find();
                        if($UserInfo){
                            Session::set('UID',$UserInfo['UID']);
                            Session::set('Email',$UserInfo['Email']);
                            Session::set('UserName',$UserInfo['UserName']);
                            cookie('UID', $UserInfo['UID'], 86400);
                            cookie('UserName', $UserInfo['UserName'], 86400);
                            return json(['code'=>200,'msg'=>'登陆成功']);
                        }else{
                            return json(['code'=>400,'msg'=>'您输入账号或者密码错误']);
                        }
                    }else{
                        $UserInfo = Db::table('user')->where('UserName',$UserName)->where('Password',md5($Password))->find();
                        if($UserInfo){
                            Session::set('UID',$UserInfo['UID']);
                            Session::set('Email',$UserInfo['Email']);
                            Session::set('UserName',$UserInfo['UserName']);
                            cookie('UID', $UserInfo['UID'], 86400);
                            cookie('UserName', $UserInfo['UserName'], 86400);
                            return json(['code'=>200,'msg'=>'登陆成功']);
                        }else{
                            return json(['code'=>400,'msg'=>'您输入账号或者密码错误']);
                        }
                    }
                }
            } else{
                return json(['code'=>400,'msg'=>'抱歉，验证码验证未通过']);
            }
        }else{  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate(Request::instance()->post('geetest_challenge'), Request::instance()->post('geetest_validate'), Request::instance()->post('geetest_seccode'))) {
                $UserName = trim(Request::instance()->post('UserName'));
                $Password = trim(Request::instance()->post('Password'));
                if(!Request::instance()->has('UserName','post')){
        	        return json(['code'=>400,'msg'=>'请输入邮箱或用户名']);
        	    }elseif (!Request::instance()->has('Password','post')) {
                    return json(['code'=>400,'msg'=>'请输入您的登陆密码']);
                }else{
                    if(strpos($UserName, "@")){
                        $UserInfo = Db::table('user')->where('Email',$UserName)->where('Password',md5($Password))->find();
                        if($UserInfo){
                            Session::set('UID',$UserInfo['UID']);
                            Session::set('Email',$UserInfo['Email']);
                            Session::set('UserName',$UserInfo['UserName']);
                            cookie('UID', $UserInfo['UID'], 86400);
                            cookie('UserName', $UserInfo['UserName'], 86400);
                            return json(['code'=>200,'msg'=>'登陆成功']);
                        }else{
                            return json(['code'=>400,'msg'=>'您输入账号或者密码错误']);
                        }
                    }else{
                        $UserInfo = Db::table('user')->where('UserName',$UserName)->where('Password',md5($Password))->find();
                        if($UserInfo){
                            Session::set('UID',$UserInfo['UID']);
                            Session::set('Email',$UserInfo['Email']);
                            Session::set('UserName',$UserInfo['UserName']);
                            cookie('UID', $UserInfo['UID'], 86400);
                            cookie('UserName', $UserInfo['UserName'], 86400);
                            return json(['code'=>200,'msg'=>'登陆成功']);
                        }else{
                            return json(['code'=>400,'msg'=>'您输入账号或者密码错误']);
                        }
                    }
                }
            }else{
                return json(['code'=>400,'msg'=>'抱歉，验证码验证未通过']);
            }
        }
	}
}
