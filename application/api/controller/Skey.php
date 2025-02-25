<?php
namespace app\api\controller;
require 'extend/qqskey/login.class.php';
use \think\Request;

class Skey
{
    public function index(){
		$login=new \qq_login();
		$do = Request::instance()->param('do');
		if($do=='checkvc'){
			$array=$login->checkvc(Request::instance()->param('uin'),Request::instance()->param('tokenid'));
		}elseif($do=='dovc'){
			$array=$login->dovc(Request::instance()->param('uin'),Request::instance()->param('sig'),Request::instance()->param('ans'),Request::instance()->param('cap_cd'),Request::instance()->param('sess'),Request::instance()->param('collectname'),Request::instance()->param('websig'),Request::instance()->param('cdata'),Request::instance()->param('sid'));
		}elseif($do=='getvc'){
			$array=$login->getvc(Request::instance()->param('uin'),Request::instance()->param('sig'),Request::instance()->param('sess'),Request::instance()->param('sid'),Request::instance()->param('websig'));
		}elseif($do=='qqlogin'){
			$array=$login->qqlogin(Request::instance()->param('uin'),Request::instance()->param('pwd'),Request::instance()->param('p'),Request::instance()->param('vcode'),Request::instance()->param('pt_verifysession'),Request::instance()->param('cookie'));
		}elseif($do=='getqrpic'){
			$array=$login->getqrpic();
		}elseif($do == 'qrlogin'){
			if(Request::instance()->param('findpwd')){
				session_start();
			};
			$array=$login->qrlogin(Request::instance()->param('qrsig'));
		}elseif($do == 'list'){
			$array=$login->list(Request::instance()->param('uin'),Request::instance()->param('skey'),Request::instance()->param('p_skey'));
		}elseif($do == 'danxiang'){
			$array=$login->danxiang(Request::instance()->param('uin'),Request::instance()->param('qq'),Request::instance()->param('skey'));
		}elseif($do == 'del'){
			$array=$login->del(Request::instance()->param('uin'),Request::instance()->param('qq'),Request::instance()->param('skey'),Request::instance()->param('p_skey'));
		}elseif($do == 'authf'){
			$array=$login->authf(Request::instance()->param('uin'),Request::instance()->param('skey'),Request::instance()->param('p_skey'),Request::instance()->param('ptcz'),Request::instance()->param('RK'));
		}elseif($do=='getqrpic3rd'){
			$array=$login->getqrpic3rd(Request::instance()->param('daid'),Request::instance()->param('appid'));
		}elseif($do=='qrlogin3rd'){
			$array=$login->qrlogin3rd(Request::instance()->param('daid'),Request::instance()->param('appid'),Request::instance()->param('qrsig'));
		}elseif($do=='idpic'){
			$array=$login->idpic();
		}elseif($do == 'idlogin'){
			if(Request::instance()->param('findpwd')){
				session_start();
			};
			$array=$login->idlogin(Request::instance()->param('qrsig'));
		}elseif($do == 'InfoNull'){
			$array=$login->InfoNull(Request::instance()->param('uin'),Request::instance()->param('skey'),Request::instance()->param('p_skey'),Request::instance()->param('RK'));
		}elseif($do == 'NickNull'){
			$array=$login->InfoNull(Request::instance()->param('uin'),Request::instance()->param('skey'),Request::instance()->param('p_skey'),Request::instance()->param('RK'));
		}
		if(is_array(@$array)){
			return INT(Request::instance()->param('type'),$array);
		}
	}
}
