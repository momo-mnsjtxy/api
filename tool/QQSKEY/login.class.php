<?php
class qq_login{
	private $loginapi = ''; //可填写登录API
	private $ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36';
	private $trace_x;
	private $trace_y;
	private $trace_time;
	private $tokenid;
	private $referrer;
	public function setLoginApi($url){
		$this->loginapi = $url;
	}
	public function dovc($uin,$sig,$ans,$cap_cd,$sess,$collectname,$websig,$cdata,$sid){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'QQ不能为空');
		if(empty($sig))return array('saveOK'=>-1,'msg'=>'sig不能为空');
		if(empty($ans))return array('saveOK'=>-1,'msg'=>'验证码不能为空');
		if(empty($cap_cd))return array('saveOK'=>-1,'msg'=>'cap_cd不能为空');
		if(empty($sess))return array('saveOK'=>-1,'msg'=>'sess不能为空');
		if(empty($sid))return array('saveOK'=>-1,'msg'=>'sid不能为空');
		$collectname=!empty($collectname)?$collectname:'collect';
		$width = explode(',',$ans);
		$width = $width[0];
		$collect=$this->getcollect($width);

		$url = 'https://ssl.captcha.qq.com/dfpReg?0=Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20WOW64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F69.0.3497.100%20Safari%2F537.36&1=zh-CN&2=1.5&3=1.6&4=24&5=8&6=-480&7=1&8=1&9=1&10=u&11=function&12=u&13=Win32&14=0&15=f14d5531d44759dfdac2c422c0276dde&16=408d1e375fc96dcedebc2b02d580bac6&17=a1f937b6ee969f22e6122bdb5cb48bde&18=10x2x102x69&19=702efbf2d84a0bfb7224d4a1bfe36e0a&20=872136824912136824&21=1%3B&22=1%3B1%3B1%3B1%3B1%3B1%3B1%3B0%3B1%3Bobject27UTF-8&23=0&24=10%3B1&25=126a2202136b27316760a4f9c2c2e1a9&26=48000_2_1_0_2_explicit_speakers&27=d7959e801195e05311be04517d04a522&28=ANGLE(Intel(R)UHDGraphics620Direct3D11vs_5_0ps_5_0)&29=60f09e9c459c29f92ce6fc61751ea45b&30=9c04b80df743b5904a3835fbc06a476e&31=0&32=0&33=0&34=0&35=0&36=0&37=0&38=0&39=0&40=0&41=0&42=0&43=0&44=0&45=0&46=0&47=0&48=0&49=0&50=0&fesig=5744539509613183248&ut=391&appid=0&refer=https%3A%2F%2Fssl.captcha.qq.com%2Fcap_union_new_show&domain=ssl.captcha.qq.com&fph=&fpv=0.0.15&ptcz=';
		$data=$this->get_curl($url,0,$this->referrer);
		$arr=json_decode($data,true);
		$fpsig = $arr['fpsig'];

		$url='https://ssl.captcha.qq.com/cap_union_new_verify';
		$post='aid=549000912&captype=&protocol=https&clientype=2&disturblevel=&apptype=2&noheader=0&color=&showtype=&fb=1&theme=&lang=2052&ua='.urlencode(base64_encode($this->ua)).'&grayscale=1&subsid=2&sess='.$sess.'&fwidth=0&sid='.$sid.'&forcestyle=0&wxLang=&tcScale=1&uid='.$uin.'&cap_cd='.$cap_cd.'&rnd='.rand(100000,999999).'&TCapIframeLoadTime=99&prehandleLoadTime=48&createIframeStart='.time().'758&rand=0.330608'.time().'&subcapclass=13&vsig='.$sig.'&ans='.$ans.'&'.$collectname.'='.$collect['collectdata'].'&websig='.$websig.'&cdata='.$cdata.'&fpinfo=fpsig%3D'.$fpsig.'&eks='.$collect['eks'].'&tlg='.strlen(urldecode($collect['collectdata'])).'&vlg=0_0_0';
		$data=$this->get_curl($url,$post,$this->referrer);
		$arr=json_decode($data,true);
		if(array_key_exists('errorCode',$arr) && $arr['errorCode']==0){
			return array('rcode'=>0,'randstr'=>$arr['randstr'],'sig'=>$arr['ticket']);
		}elseif($arr['errorCode']==50){
			return array('rcode'=>50,'errmsg'=>'验证码输入错误！');
		}elseif($arr['errorCode']==12 && $subcapclass==9){
			return array('rcode'=>12,'errmsg'=>$arr['errMessage']);
		}else{
			return array('rcode'=>9,'errmsg'=>$arr['errMessage']);
		}
	}
	public function getvcpic($uin,$sig,$cap_cd,$sess,$sid){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'QQ不能为空');
		if(empty($sig))return array('saveOK'=>-1,'msg'=>'sig不能为空');
		$url='https://ssl.captcha.qq.com/cap_union_new_getcapbysig?aid=549000912&captype=&protocol=https&clientype=2&disturblevel=&apptype=2&noheader=0&uid='.$uin.'&color=&showtype=&fb=1&lang=2052&ua='.urlencode(base64_encode($this->ua)).'&grayscale=1&cap_cd='.$cap_cd.'&rnd='.rand(100000,999999).'&rand=0.02398118'.time().'&sess='.$sess.'&sid='.$sid.'&vsig='.$sig.'&ischartype=1';
		return $this->get_curl($url);
	}
	public function getvcpic2($uin,$sig,$cap_cd,$sess,$sid,$websig,$img_index=0){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'QQ不能为空');
		if(empty($sig))return array('saveOK'=>-1,'msg'=>'sig不能为空');
		$url='https://ssl.captcha.qq.com/cap_union_new_getcapbysig?aid=549000912&captype=&protocol=https&clientype=2&disturblevel=&apptype=2&noheader=0&color=&showtype=&fb=1&theme=&lang=2052&ua='.urlencode(base64_encode($this->ua)).'&grayscale=1&subsid=3&sess='.$sess.'&fwidth=0&sid='.$sid.'&forcestyle=0&tcScale=1&uid='.$uin.'&cap_cd='.$cap_cd.'&rnd='.rand(100000,999999).'&rand='.rand(10000000,99999999).'&websig='.$websig.'&vsig='.$sig.'&img_index='.$img_index;
		return $url;
	}
	public function qqlogin($uin,$pwd,$p,$vcode,$pt_verifysession,$cookie){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'QQ不能为空');
		if(empty($pwd))return array('saveOK'=>-1,'msg'=>'pwd不能为空');
		if(empty($p))return array('saveOK'=>-1,'msg'=>'密码不能为空');
		if(empty($vcode))return array('saveOK'=>-1,'msg'=>'验证码不能为空');
		if(empty($pt_verifysession))return array('saveOK'=>-1,'msg'=>'pt_verifysession不能为空');
		if(strpos('s'.$vcode,'!')){
			$v1=0;
		}else{
			$v1=1;
		}
		preg_match("/pt_login_sig=(.*?);/", $cookie, $match);
		$pt_login_sig = $match[1];
		preg_match("/ptdrvs=(.*?);/", $cookie, $match);
		$ptdrvs = $match[1];
		$url='https://ssl.ptlogin2.qq.com/login?u='.$uin.'&verifycode='.$vcode.'&pt_vcode_v1='.$v1.'&pt_verifysession_v1='.$pt_verifysession.'&p='.$p.'&pt_randsalt=2&u1=https%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=5-10-'.time().'487&js_ver=19092321&js_type=1&login_sig='.$pt_login_sig.'&pt_uistyle=40&aid=549000912&daid=5&ptdrvs='.$ptdrvs.'&';
		$referrer='https://xui.ptlogin2.qq.com/cgi-bin/xlogin?proxy_url=https%3A//qzs.qq.com/qzone/v6/portal/proxy.html&daid=5&&hide_title_bar=1&low_login=0&qlogin_auto_login=0&no_verifyimg=1&link_target=blank&appid=549000912&style=22&target=self&s_url=https%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone&pt_no_auth=0';
		$ret = $this->get_curl($url,0,$referrer,$cookie,1);
		if(preg_match("/ptuiCB\('(.*?)'\)/", $ret, $arr)){
			$r=explode("','",str_replace("', '","','",$arr[1]));
			if($r[0]==0){
				if(strpos($r[2],'mibao_vry'))
					return array('saveOK'=>-3,'msg'=>'请先到QQ安全中心关闭网页登录保护！');
				preg_match('/skey=@(.{9});/',$ret,$skey);
				preg_match('/superkey=(.*?);/',$ret,$superkey);
				$data=$this->get_curl($r[2],0,0,0,1);
				if($data) {
					preg_match("/p_skey=(.*?);/", $data, $matchs);
					$pskey = $matchs[1];
					preg_match("/Location: (.*?)\r\n/iU", $data, $matchs);
					$sid=explode('sid=',$matchs[1]);
					$sid=$sid[1];
				}
				if($skey[1] && $pskey){
					return array('saveOK'=>0,'uin'=>$uin,'sid'=>$sid,'skey'=>'@'.$skey[1],'pskey'=>$pskey,'superkey'=>$superkey[1],'nick'=>urlencode($r[5]),'loginurl'=>$r[2]);
				}else{
					if(!$pskey)
						return array('saveOK'=>-3,'msg'=>'登录成功，获取P_skey失败！'.$r[2]);
					elseif(!$sid)
						return array('saveOK'=>-3,'msg'=>'登录成功，获取SID失败！');
				}
			}elseif($r[0]==4){
				return array('saveOK'=>4,'msg'=>'验证码错误');
			}elseif($r[0]==3){
				return array('saveOK'=>3,'msg'=>'密码错误');
			}elseif($r[0]==19){
				return array('saveOK'=>19,'uin'=>$uin,'msg'=>'您的帐号暂时无法登录，请到 http://aq.qq.com/007 恢复正常使用');
			}else{
				return array('saveOK'=>-6,'msg'=>$r[4]);
			}
		}else{
			return array('saveOK'=>-2,'msg'=>$ret);
		}
	}
	public function getvc($uin,$sig,$sess,$sid,$websig){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'请先输入QQ号码');
		if(empty($sig))return array('saveOK'=>-1,'msg'=>'SIG不能为空');
		if(!preg_match("/^[1-9][0-9]{4,11}$/",$uin)) exit('{"saveOK":-2,"msg":"QQ号码不正确"}');
		if($sess=='0'){
			$url='https://ssl.captcha.qq.com/cap_union_prehandle?aid=549000912&captype=&protocol=https&clientype=2&disturblevel=&apptype=2&noheader=0&color=&showtype=&fb=1&theme=&lang=2052&ua='.urlencode(base64_encode($this->ua)).'&grayscale=1&cap_cd='.$sig.'&uid='.$uin.'&subsid=1&callback=&sess=';
			$data=$this->get_curl($url,0,'https://xui.ptlogin2.qq.com/cgi-bin/xlogin');
			$data=substr($data,1,-1);
			$arr=json_decode($data,true);
			$sess=$arr['sess'];
			$sid=$arr['sid'];
			if(!$sess)return array('saveOK'=>-3,'msg'=>'获取验证码参数失败');

			$url='https://ssl.captcha.qq.com/cap_union_new_show?aid=549000912&captype=&protocol=https&clientype=2&disturblevel=&apptype=2&noheader=0&color=&showtype=&fb=1&theme=&lang=2052&ua='.urlencode(base64_encode($this->ua)).'&grayscale=1&subsid=2&sess='.$sess.'&fwidth=0&sid='.$sid.'&forcestyle=0&wxLang=&tcScale=1&uid='.$uin.'&cap_cd='.$sig.'&rnd='.rand(100000,999999).'&TCapIframeLoadTime=48&prehandleLoadTime=46&createIframeStart='.time().'353';
			$this->referrer = $url;
			$data=$this->get_curl($url,0,'https://xui.ptlogin2.qq.com/cgi-bin/xlogin');
			if(preg_match("/=\"([0-9a-zA-Z\*\_\-]{187})\"/", $data, $match1)){
				preg_match('/\Number\(\"(\d+)\"\)/', $data, $Number);
				preg_match('/websig=([0-9a-f]{128})/', $data, $websig);
				preg_match('/ans=.*?&([a-z]{6})=/', $data, $collectname);
				preg_match('/{&quot;randstr&quot;:&quot;(.{4})&quot;,&quot;M&quot;:&quot;(\d+)&quot;,&quot;ans&quot;:&quot;([0-9a-f]{32})&quot;}/', $data, $cdata_arr);
				$cdata=$this->getcdata($cdata_arr[3],$cdata_arr[2],$cdata_arr[1]);
				$height = $Number[1];
				$imgA = $this->getvcpic2($uin,$match1[1],$sig,$sess,$sid,$websig[1],1);
				$imgB = $this->getvcpic2($uin,$match1[1],$sig,$sess,$sid,$websig[1],0);
				$width = $this->captcha($imgA, $imgB);
				$ans = $width.','.$height.';';
				return array('saveOK'=>2,'vc'=>$match1[1],'sess'=>$sess,'collectname'=>$collectname[1],'websig'=>$websig[1],'ans'=>$ans,'cdata'=>$cdata,'sid'=>$sid);
			}elseif(preg_match("/vsig:\"([0-9a-zA-Z\*\_\-]{187})\"/", $data, $match1)){
				preg_match('/spt:\"(\d+)\"/', $data, $Number);
				preg_match('/websig:\"([0-9a-f]{128})\"/', $data, $websig);
				preg_match('/collectdata:\"([a-z]{6})\"/', $data, $collectname);
				preg_match('/{&quot;randstr&quot;:&quot;(.{4})&quot;,&quot;M&quot;:&quot;(\d+)&quot;,&quot;ans&quot;:&quot;([0-9a-f]{32})&quot;}/', $data, $cdata_arr);
				$cdata=$this->getcdata($cdata_arr[3],$cdata_arr[2],$cdata_arr[1]);
				$height = $Number[1];
				$imgA = $this->getvcpic2($uin,$match1[1],$sig,$sess,$sid,$websig[1],1);
				$imgB = $this->getvcpic2($uin,$match1[1],$sig,$sess,$sid,$websig[1],0);
				$width = $this->captcha($imgA, $imgB);
				$ans = $width.','.$height.';';
				return array('saveOK'=>2,'vc'=>$match1[1],'sess'=>$sess,'collectname'=>$collectname[1],'websig'=>$websig[1],'ans'=>$ans,'cdata'=>$cdata,'sid'=>$sid);
			}else{
				return array('saveOK'=>-3,'msg'=>'获取验证码失败');
			}
		}else{
			$url='https://ssl.captcha.qq.com/cap_union_new_getsig';
			$post='aid=549000912&captype=&protocol=https&clientype=2&disturblevel=&apptype=2&noheader=0&color=&showtype=&fb=1&theme=&lang=2052&ua='.urlencode(base64_encode($this->ua)).'&grayscale=1&subsid=2&sess='.$sess.'&sid='.$sid.'&uid='.$uin.'&cap_cd='.$sig.'&rnd='.rand(100000,999999).'&TCapIframeLoadTime=99&prehandleLoadTime=48&createIframeStart='.time().'758&rand=0.3944965'.time();
			$referrer='https://ssl.captcha.qq.com/cap_union_new_show?aid=549000912&captype=&protocol=https&clientype=2&disturblevel=&apptype=2&noheader=0&color=&showtype=&fb=1&theme=&lang=2052&ua='.urlencode(base64_encode($this->ua)).'&grayscale=1&subsid=2&sess='.$sess.'&fwidth=0&sid='.$sid.'&forcestyle=0&wxLang=&tcScale=1&uid='.$uin.'&cap_cd='.$sig.'&rnd='.rand(100000,999999).'&TCapIframeLoadTime=48&prehandleLoadTime=46&createIframeStart='.time().'353';
			$this->referrer = $referrer;
			$data=$this->get_curl($url,$post,$referrer);
			$arr=json_decode($data,true);
			$cdata=$this->getcdata($arr['chlg']['ans'],$arr['chlg']['M'],$arr['chlg']['randstr']);
			if($arr['initx'] && $arr['inity']){
				$height = $arr['inity'];
				$imgA = $this->getvcpic2($uin,$arr['vsig'],$sig,$sess,$sid,$websig,1);
				$imgB = $this->getvcpic2($uin,$arr['vsig'],$sig,$sess,$sid,$websig,0);
				$width = $this->captcha($imgA, $imgB);
				$ans = $width.','.$height.';';
				return array('saveOK'=>2,'vc'=>$arr['vsig'],'sess'=>$sess,'ans'=>$ans,'cdata'=>$cdata);
			}elseif($arr['vsig']){
				$image = $this->getvcpic($uin,$arr['vsig'],$sig,$sess,$sid);
				return array('saveOK'=>0,'vc'=>$arr['vsig'],'sess'=>$sess,'cdata'=>$cdata,'image'=>base64_encode($image));
			}else{
				return array('saveOK'=>-3,'msg'=>'获取验证码失败');
			}
		}
	}
	public function checkvc($uin,$tokenid=null){
		if(empty($uin))return array('saveOK'=>-1,'msg'=>'请先输入QQ号码');
		if(empty($tokenid))$tokenid=(string)rand(2067831491,5632894513);
		if(!preg_match("/^[1-9][0-9]{4,13}$/",$uin)) exit('{"saveOK":-2,"msg":"QQ号码不正确"}');
		$url='https://xui.ptlogin2.qq.com/cgi-bin/xlogin?proxy_url=https%3A//qzs.qq.com/qzone/v6/portal/proxy.html&daid=5&&hide_title_bar=1&low_login=0&qlogin_auto_login=1&no_verifyimg=1&link_target=blank&appid=549000912&style=22&target=self&s_url=https%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone&pt_no_auth=0';
		$data=$this->get_curl($url,0,0,0,1);
		$cookie='';
		preg_match_all('/Set-Cookie: (.*?);/i',$data,$matchs);
		foreach ($matchs[1] as $val) {
			$cookie.=$val.'; ';
		}
		preg_match("/pt_login_sig=(.*?);/", $cookie, $match);
		$pt_login_sig = $match[1];
		preg_match("/ver\/(\d+)/", $data, $match);
		$js_ver = $match[1];
		$cookie = substr($cookie,0,-2);
		$url2='https://ssl.ptlogin2.qq.com/check?regmaster=&pt_tea=2&pt_vcode=1&uin='.$uin.'&appid=549000912&js_ver='.$js_ver.'&js_type=1&login_sig='.$pt_login_sig.'&u1=https%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone&r=0.'.time().'722706&pt_uistyle=25';
		$data=$this->get_curl($url2,0,$url,$cookie,1);
		if(preg_match("/ptui_checkVC\('(.*?)'\)/", $data, $arr)){
			preg_match_all('/Set-Cookie: (.*);/iU',$data,$matchs);
			foreach ($matchs[1] as $val) {
				$cookie.=$val.'; ';
			}
			$r=explode("','",$arr[1]);
			if($r[0]==0){
				return array('saveOK'=>0,'uin'=>$uin,'vcode'=>$r[1],'pt_verifysession'=>$r[3],'cookie'=>$cookie);
			}else{
				return array('saveOK'=>1,'uin'=>$uin,'sig'=>$r[1],'cookie'=>$cookie);
			}
		}else{
			return array('saveOK'=>-3,'msg'=>'获取验证码失败'.$data);
		}
	}
	public function getqrpic(){
		$url='https://ssl.ptlogin2.qq.com/ptqrshow?appid=549000912&e=2&l=M&s=4&d=72&v=4&t=0.5409099'.time().'&daid=5';
		$arr=$this->get_curl_split($url);
		preg_match('/qrsig=(.*?);/',$arr['header'],$match);
		if($qrsig=$match[1])
			return array('saveOK'=>0,'qrsig'=>$qrsig,'data'=>base64_encode($arr['body']));
		else
			return array('saveOK'=>1,'msg'=>'二维码获取失败');
	}
	public function qrlogin($qrsig){
		if(empty($qrsig))return array('saveOK'=>-1,'msg'=>'qrsig不能为空');
		$url='https://ssl.ptlogin2.qq.com/ptqrlogin?u1=https%3A%2F%2Fqzs.qq.com%2Fqzone%2Fv5%2Floginsucc.html%3Fpara%3Dizone&ptqrtoken='.$this->getqrtoken($qrsig).'&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-'.time().'0000&js_ver=10194&js_type=1&login_sig='.$sig.'&pt_uistyle=40&aid=549000912&daid=5&';
		$ret = $this->get_curl($url,0,$url,'qrsig='.$qrsig.'; ',1);
		if(preg_match("/ptuiCB\('(.*?)'\)/", $ret, $arr)){
			$r=explode("','",str_replace("', '","','",$arr[1]));
			if($r[0]==0){
				preg_match('/uin=(\d+)&/',$ret,$uin);
				$uin=$uin[1];
				preg_match('/skey=@(.{9});/',$ret,$skey);
				preg_match('/superkey=(.*?);/',$ret,$superkey);
				$data=$this->get_curl($r[2],0,0,0,1);
				if($data) {
					preg_match("/p_skey=(.*?);/", $data, $matchs);
					$pskey = $matchs[1];
				}
				if($pskey){
					if(isset($_GET['findpwd'])){
						$_SESSION['findpwd_qq']=$uin;
					}
					return array('saveOK'=>0,'uin'=>$uin,'skey'=>'@'.$skey[1],'pskey'=>$pskey,'superkey'=>$superkey[1],'nick'=>$r[5]);
				}else{
					return array('saveOK'=>6,'msg'=>'登录成功，获取相关信息失败！'.$r[2]);
				}
			}elseif($r[0]==65){
				return array('saveOK'=>1,'msg'=>'二维码已失效。');
			}elseif($r[0]==66){
				return array('saveOK'=>2,'msg'=>'二维码未失效。');
			}elseif($r[0]==67){
				return array('saveOK'=>3,'msg'=>'正在验证二维码。');
			}else{
				return array('saveOK'=>6,'msg'=>$r[4]);
			}
		}else{
			return array('saveOK'=>6,'msg'=>$ret);
		}
	}
	public function getqrpic3rd($daid,$appid){
		if(empty($daid)||empty($appid))return array('saveOK'=>-1,'msg'=>'daid和appid不能为空');
		$url='https://ssl.ptlogin2.qq.com/ptqrshow?appid='.$appid.'&e=2&l=M&s=4&d=72&v=4&t=0.5409099'.time().'&daid='.$daid;
		$arr=$this->get_curl_split($url);
		preg_match('/qrsig=(.*?);/',$arr['header'],$match);
		if($qrsig=$match[1])
			return array('saveOK'=>0,'qrsig'=>$qrsig,'data'=>base64_encode($arr['body']));
		else
			return array('saveOK'=>1,'msg'=>'二维码获取失败');
	}
	public function qrlogin3rd($daid,$appid,$qrsig){
		if(empty($daid)||empty($appid))return array('saveOK'=>-1,'msg'=>'daid和appid不能为空');
		if(empty($qrsig))return array('saveOK'=>-1,'msg'=>'qrsig不能为空');
		if($daid==73)$s_url = 'https://qun.qq.com/';
		else if($daid==1)$s_url = 'https://id.qq.com/index.html';
		else $s_url = 'https://qzs.qq.com/qzone/v5/loginsucc.html';
		$url='https://ssl.ptlogin2.qq.com/ptqrlogin?u1='.urlencode($s_url).'&ptqrtoken='.$this->getqrtoken($qrsig).'&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-'.time().'0000&js_ver=10194&js_type=1&login_sig='.$sig.'&pt_uistyle=40&aid='.$appid.'&daid='.$daid.'&';
		$ret = $this->get_curl($url,0,$url,'qrsig='.$qrsig.'; ',1);
		if(preg_match("/ptuiCB\('(.*?)'\)/", $ret, $arr)){
			$r=explode("','",str_replace("', '","','",$arr[1]));
			if($r[0]==0){
				preg_match('/uin=(\d+)&/',$ret,$uin);
				$uin=$uin[1];
				preg_match('/skey=@(.{9});/',$ret,$skey);
				preg_match('/superkey=(.*?);/',$ret,$superkey);
				$data=$this->get_curl($r[2],0,0,0,1);
				if($data) {
					preg_match_all('/Set-Cookie: (.*?);/iU',$data,$matchs);
					$cookie='';
					foreach ($matchs[1] as $val) {
						if(substr($val,-1)=='=')continue;
						$cookie.=$val.'; ';
					}
					$cookie = substr($cookie,0,-2);
				}
				if($cookie){
					return array('saveOK'=>0,'uin'=>$uin,'cookie'=>$cookie,'nickname'=>$r[5]);
				}else{
					return array('saveOK'=>6,'msg'=>'登录成功，获取相关信息失败！'.$r[2]);
				}
			}elseif($r[0]==65){
				return array('saveOK'=>1,'msg'=>'二维码已失效。');
			}elseif($r[0]==66){
				return array('saveOK'=>2,'msg'=>'二维码未失效。');
			}elseif($r[0]==67){
				return array('saveOK'=>3,'msg'=>'正在验证二维码。');
			}else{
				return array('saveOK'=>6,'msg'=>$r[4]);
			}
		}else{
			return array('saveOK'=>6,'msg'=>$ret);
		}
	}
	private function getqrtoken($qrsig){
        $len = strlen($qrsig);
        $hash = 0;
        for($i = 0; $i < $len; $i++){
            $hash += (($hash << 5) & 2147483647) + ord($qrsig[$i]) & 2147483647;
			$hash &= 2147483647;
        }
        return $hash & 2147483647;
	}
	private function captcha($imgAurl,$imgBurl){
		$imgA = imagecreatefromstring($this->get_curl($imgAurl,0,$this->referrer));
		$imgB = imagecreatefromstring($this->get_curl($imgBurl,0,$this->referrer));
		$imgWidth = imagesx($imgA);
		$imgHeight = imagesy($imgA);
		
		$t=0;$r=0;
		for ($y=20; $y<$imgHeight-20; $y++){
		   for ($x=20; $x<$imgWidth-20; $x++){
			   $rgbA = imagecolorat($imgA,$x,$y);
			   $rgbB = imagecolorat($imgB,$x,$y);
			   if(abs($rgbA-$rgbB)>1800000){
				   $t++;
				   $r+=$x;
			   }
		   }
		}
		return round($r/$t)-55;
	}
	private function getcdata($ans,$M,$randstr){
		for ($r = 0; $r < $M && $r < 1000; $r++) {
			$c = $randstr . $r;
			$d = md5 ($c);
			if ($ans == $d) {
				$a = $r;
				break;
			}
		}
		return $a;
	}
	private function generate_slideValue($width){
		$sx = rand(700,730);
		$sy = rand(295,300);
		$this->trace_x=$sx;
		$this->trace_y=$sy;
		$ex = $sx+intval(($width-55)/2);
		$stime = rand(100,300);
		$res = '['.$sx.','.$sy.','.$stime.'],';
		$randy = array(0,0,0,0,0,0,1,1,1,2,3,-1,-1,-1,-2);
		while($sx<$ex){
			$x=rand(3,9);
			$sx+=$x;
			$y=$randy[array_rand($randy)];
			$time=rand(9,18);
			$stime+=$time;
			$res .= '['.$x.','.$y.','.$time.'],';
		}
		$res .= '[0,0,'.rand(10,25).']';
		return $res;
	}
	private function generate_mousemove($width){
		$sx = rand(720,810);
		$sy = rand(270,290);
		$stime = rand(800,1000);
		$res = '['.$sx.','.$sy.','.$stime.'],';
		while($sx>$this->trace_x || $sy<$this->trace_y){
			if($sx>$this->trace_x)$x=rand(-5,-1);
			else $x=0;
			if($sy<$this->trace_y)$y=rand(1,2);
			else $y=0;
			$sx+=$x;
			$sy+=$y;
			$time=rand(9,16);
			$stime+=$time;
			$res .= '['.$x.','.$y.','.$time.'],';
		}
		$ex = $this->trace_x+intval(($width-55)/2);
		while($sx<$ex){
			$x=rand(1,6);
			$sx+=$x;
			$y=0;
			$time=rand(9,16);
			$stime+=$time;
			$res .= '['.$x.','.$y.','.$time.'],';
		}
		$res = substr($res,0,-1);
		$this->trace_time=ceil($stime/1000);
		$this->trace_x=$sx;
		$this->trace_y=$sy;
		return $res;
	}
	private function random($min = 0, $max = 1) {  
		return round($min + mt_rand() / mt_getrandmax() * ($max - $min));  
	}
	public function getcollect($width){
		$tokenid=(string)$this->random(2067831491,5632894513);
		$slideValue = $this->generate_slideValue($width);
		return $this->tdcData($tokenid, $slideValue);
	}
	private function tdcData($tokenid, $slideValue){
		$data = $this->get_curl('http://collect.qqzzz.net/',http_build_query(array('tokenid'=>$tokenid, 'slideValue'=>$slideValue)));
		return json_decode($data, true);
	}
	private function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0){
		if($this->loginapi)return $this->get_curl_proxy($url,$post,$referer,$cookie,$header,$ua,$nobaody);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$httpheader[] = "Accept: application/json";
		$httpheader[] = "Accept-Encoding: gzip,deflate,sdch";
		$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
		$httpheader[] = "Connection: keep-alive";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		if($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if($header){
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
		}
		if($cookie){
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		}
		if($referer){
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
		if($ua){
			curl_setopt($ch, CURLOPT_USERAGENT,$ua);
		}else{
			curl_setopt($ch, CURLOPT_USERAGENT,$this->ua);
		}
		if($nobaody){
			curl_setopt($ch, CURLOPT_NOBODY,1);

		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
	private function get_curl_split($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$httpheader[] = "Accept: */*";
		$httpheader[] = "Accept-Encoding: gzip,deflate,sdch";
		$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
		$httpheader[] = "Connection: keep-alive";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT,$this->ua);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($ret, 0, $headerSize);
		$body = substr($ret, $headerSize);
		$ret=array();
		$ret['header']=$header;
		$ret['body']=$body;
		curl_close($ch);
		return $ret;
	}
	private function get_curl_proxy($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0){
		$data = array('url'=>$url, 'post'=>$post, 'referer'=>$referer, 'cookie'=>$cookie, 'header'=>$header, 'ua'=>$ua, 'nobaody'=>$nobaody);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_URL,$this->loginapi);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
}