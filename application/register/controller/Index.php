<?php
namespace app\register\controller;
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
	    header('Content-type:application/json; charset=utf-8');
	    $GtSdk = new \GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
        $data = array(
    		"class" => "register", # 网站用户id
    		"client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
    		"ip_address" => $_SERVER['REMOTE_ADDR'] # 请在此处传输用户请求验证时所携带的IP
    	);
        $status = $GtSdk->pre_process($data, 1);
        Session::set('gtserver',$status);
        Session::set('class',$data['class']);
        echo $GtSdk->get_response_str();
        exit();
	}
	public function Email(){
	    if(Cookie::has('UID') && Cookie::has('UID') && Cookie::has('UserName') && Session::has('UID') && Session::has('Email') && Session::has('UserName') && Cookie::get('UID') == Session::get('UID') && Cookie::get('UserName') == Session::get('UserName')){
            $this->success('您已登陆', 'index/index/index');
        }
	    $GtSdk = new \GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
        $data = array(
    		"class" => "register", # 网站用户id
    		"client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
    		"ip_address" => $_SERVER['REMOTE_ADDR'] # 请在此处传输用户请求验证时所携带的IP
    	);
        if (Session::get('gtserver') == 1) {   //服务器正常
            $result = $GtSdk->success_validate(Request::instance()->post('geetest_challenge'), Request::instance()->post('geetest_validate'), Request::instance()->post('geetest_seccode'), $data);
            $Email = trim(Request::instance()->post('Email'));
            if ($result) {
                if(!Request::instance()->has('Email','post')){
        	        return json(['code'=>400,'msg'=>'请输入电子邮箱账号']);
        	    }elseif (!preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/',$Email)) {
                    return json(['code'=>400,'msg'=>'您输入的电子邮箱邮箱格式不正确']);
                }elseif(Db::table('user')->where('Email',$Email)->find()){
                    return json(['code'=>400,'msg'=>'该邮箱号已注册或已绑定其它账号']);
                }else{
                    $RegisterCode = rand(100000,999999);
                    Session::set('RegisterCode',$RegisterCode);
                    $result = email('',$Email,'','梦城API注册验证','您的验证码是 '.$RegisterCode,'','');
                    if(is_array($result) && $result['code'] == 1){
                        return json(['code'=>200,'msg'=>'验证码已经发送到您的邮箱']);
        			}else{
        			    return json(['code'=>400,'msg'=>'抱歉，邮件发送失败，请联系管理员']);
        			}
                }
            } else{
                return json(['code'=>400,'msg'=>'抱歉，验证码验证未通过']);
            }
        }else{  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate(Request::instance()->post('geetest_challenge'), Request::instance()->post('geetest_validate'), Request::instance()->post('geetest_seccode'))) {
                if(!Request::instance()->has('Email','post')){
        	        return json(['code'=>400,'msg'=>'请输入电子邮箱账号']);
        	    }elseif (!preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/',$Email)) {
                    return json(['code'=>400,'msg'=>'您输入的电子邮箱邮箱格式不正确']);
                }elseif(Db::table('user')->where('Email',$Email)->find()){
                    return json(['code'=>400,'msg'=>'该邮箱号已注册或已绑定其它账号']);
                }else{
                    $RegisterCode = rand(100000,999999);
                    Session::set('RegisterCode',$RegisterCode);
                    $result = email('',$Email,'','梦城API注册验证','您的验证码是 '.$RegisterCode,'','');
                    if(is_array($result) && $result['code'] == 1){
                        return json(['code'=>200,'msg'=>'验证码已经发送到您的邮箱']);
        			}else{
        			    return json(['code'=>400,'msg'=>'抱歉，邮件发送失败，请联系管理员']);
        			}
                }
            }else{
                return json(['code'=>400,'msg'=>'抱歉，验证码验证未通过']);
            }
        }
	}
	public function callback(){
	    if(Cookie::has('UID') && Cookie::has('UID') && Cookie::has('UserName') && Session::has('UID') && Session::has('Email') && Session::has('UserName') && Cookie::get('UID') == Session::get('UID') && Cookie::get('UserName') == Session::get('UserName')){
            $this->success('您已登陆', 'index/index/index');
        }
	    $UserName = trim(Request::instance()->post('UserName'));
        $Email = trim(Request::instance()->post('Email'));
        $Password = trim(Request::instance()->post('Password'));
        $EmailCode = trim(Request::instance()->post('Email_Code'));
	    if(!Request::instance()->has('UserName','post')){
	        return json(['code'=>400,'msg'=>'请输入设置的用户名']);
	    }elseif(!Request::instance()->has('Email','post')){
	        return json(['code'=>400,'msg'=>'请输入电子邮箱账号']);
	    }elseif(!Request::instance()->has('Password','post')){
	        return json(['code'=>400,'msg'=>'您还未输入设置的密码']);
	    }elseif (!preg_match('/^[a-zA-Z0-9_-]{3,16}$/',$UserName)) {
        	return json(['code'=>400,'msg'=>'用户名由4-16位数字字母汉字和下划线组成']);
        }elseif (!preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/',$Email)) {
            return json(['code'=>400,'msg'=>'您输入的电子邮箱邮箱格式不正确']);
        }elseif(!Request::instance()->has('Email_Code','post')){
	        return json(['code'=>400,'msg'=>'请输入6位数邮箱验证码']);
	    }elseif (!preg_match('/^.*(?=.{6,18}).*$/',$Password)) {
            return json(['code'=>400,'msg'=>'为了您的安全请输入6~18位密码']);
        }elseif((int)$EmailCode != Session::get('RegisterCode')){
            return json(['code'=>400,'msg'=>$EmailCode.'抱歉！邮箱验证码错误'.Session::get('RegisterCode')]);
        }elseif(Db::table('user')->where('UserName',$UserName)->find()){
            return json(['code'=>400,'msg'=>'此用户名已经被使用']);
        }elseif(Db::table('user')->where('Email',$Email)->find()){
            return json(['code'=>400,'msg'=>'该邮箱号已注册或已绑定其它账号']);
        }else{
            Session::delete('Email_Code');
            //echo password_verify('123456', password_hash('123456', PASSWORD_BCRYPT));
            $APPKEY = md5(rand(1000000000,9999999999).time());
            $result = Db::table('user')
            ->data([
                'UserName' => $UserName,
                'Password' => md5($Password),
                'Email' => $Email,
                'APPKEY' => $APPKEY,
                'AddTime' => time(),
            ])->insert();
            if($result){
                return json(['code'=>200,'msg'=>'恭喜您，注册成功']);
            }else{
                return json(['code'=>400,'msg'=>'抱歉内部错误，请联系管理员']);
            }
	    }
	}
}
