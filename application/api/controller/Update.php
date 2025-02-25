<?php
namespace app\api\controller;
use \think\Request;
use \think\Db;

class Update
{
    public function index(){
		return json(['code'=>400,'msg'=>'接口错误','api'=>""]);
	}
	
	//热歌榜更新
	public function Music_hot(){
		$url = 'https://www.gqink.cn/usr/themes/handsome/libs/Get.php?type=collect&media=netease&id=3778678';
		$res = @file_get_contents($url);
		$list = json_decode(trim($res,chr(239).chr(187).chr(191)),true);
		$data = [];
		for ($i = 0; $i < count($list); $i++) {
			$result = @m_163($list[$i]['song_id'])['lrc'];
			$data[] = [
				'name' => $list[$i]['name'],
				'url' => 'https://music.163.com/song/media/outer/url?id='.$list[$i]['song_id'],
				'cover' => $list[$i]['cover'],
				'author' => $list[$i]['author'],
				'lrc' => $result,
			];
		}
		$json = json_encode($data);
		if(is_array($data)){
			   $filename = 'public/hot.json';
			   $fp= fopen($filename, "w");  //w是写入模式，文件不存在则创建文件写入。
			   $len = fwrite($fp, $json);
			   fclose($fp);
			   print $len .'更新成功';
		}else {
			echo "更新失败";
		}
	}
	
	
	//更新一言
	public function Word(){
		$res = @file_get_contents('https://v1.hitokoto.cn/');
		$GET_JSON = json_decode(trim($res,chr(239).chr(187).chr(191)),true);
		if(is_array(@$GET_JSON)){
			$result = Db::table('word')->where('text',@$GET_JSON['hitokoto'])->find();
			if($result == null){
				$result = Db::table('word')->data(['text'=>@$GET_JSON['hitokoto'],'love'=>520])->insert();
				if($result){
					echo "更新成功";
				}else{
					echo "更新失败";
				}
			}else{
				echo "数据重复";
			}
		}else{
			echo "内部错误";
		}
	}	
	
	public function Wordtime(){
	    if(!empty(Request::instance()->param('time'))){
	        $time = Request::instance()->param('time');
	    }else{
	        $time = str_replace('/', '-', date("Y/m/d"));
	    }
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
		if(@count(@$GET_JSON['data'])){
		    for ($i = 0; $i < count($GET_JSON['data']); $i++) {
		        $result = Db::table('word')->where('text',@$GET_JSON['data'][$i]['data'])->find();
    			if($result == null){
    				$result = Db::table('word')->data(['text'=>@$GET_JSON['data'][$i]['data'],'love'=>520])->insert();
    				if($result){
    					echo "更新成功<br/>";
    				}else{
    					echo "更新失败<br/>";
    				}
    			}else{
    				echo "数据重复<br/>";
    			}
		    }
		}else{
			echo "内部错误";
		}
	}
	
	//更新热评
	public function netease(){
		$GET_JSON = GET_JSON('https://api.uomg.com/api/comments.163');
		if(is_array(@$GET_JSON)){
			$result = Db::table('hot')->where('comment_content',@$GET_JSON['data']['content'])->find();
			if($result == null){
				$name = @$GET_JSON['data']['name'];
				$images = @$GET_JSON['data']['picurl'];
				$author = @$GET_JSON['data']['artistsname'];
				$mp3_url = @$GET_JSON['data']['url'];
				$comment_nickname = @$GET_JSON['data']['nickname'];
				$comment_content = @$GET_JSON['data']['content'];
				$love = 520;
				$result = Db::table('hot')->data(['name'=>$name,'images'=>$images,'author'=>$author,'mp3_url'=>$mp3_url,'comment_nickname'=>$comment_nickname,'comment_content'=>$comment_content,'love'=>$love])->insert();
				if($result){
					echo "更新成功";
				}else{
					echo "更新失败";
				}
			}else{
				echo "数据重复";
			}
		}else{
			echo "内部错误";
		}
	}
	
	//更新头像
	public function headimg(){
	    $kw = Request::instance()->param('kw');
	    $kwn = Request::instance()->param('kwn');
	    $a = Request::instance()->param('start');
	    $b = $a+30;//recommend
	    $url = 'http://api4.qzone.cc/feed/default/get-new-feed-by-feed-tag?feed_tag_id='.$kwn.'&start='.$a.'&len=30';
		$GET_JSON = GET_JSON($url);
		if(is_array(@$GET_JSON)){
		    $data = 0;
		    for ($i = 0; $i < count(@$GET_JSON['data']); $i++) {
		         for ($a = 0; $a < count(@$GET_JSON['data'][$i]['image']); $a++) {
                    $result = Db::table($kw)->where('url',@$GET_JSON['data'][$i]['image'][$a]['image_url'])->find();
        			if($result == null){
        				$result = Db::table($kw)->data(['url'=>@$GET_JSON['data'][$i]['image'][$a]['image_url']])->insert();
        				if($result){
        				    $data = $data+1;
        				}else{
        				    
        				}
        			}else{
        				
        			}
		         }
		    }
		    echo "<title>写入".$data."条成功</title>";
		    echo "写入".$data."条成功";
		}else{
			echo "内部错误";
		}
		echo "<script>
		window.onload=function(){
		    window.location.replace('https://api.gqink.cn/api/update/headimg/kw/$kw/kwn/$kwn/start/$b/');
		}
	   </script>";
	}
	
	//words
	public function words(){
	    $a = Request::instance()->param('start');
	    $b = $a+100;//recommend
	    $url = 'http://api4.qzone.cc/feed/default/get-new-feed-by-feed-tag?feed_tag_id=1070092&start='.$a.'&len=100';
		$GET_JSON = GET_JSON($url);
		if(is_array(@$GET_JSON)){
		    $data = 0;
	         for ($a = 0; $a < count(@$GET_JSON['data']); $a++) {
                $result = Db::table("word")->where('text',@$GET_JSON['data'][$a]['text'])->find();
    			if($result == null){
    				$result = Db::table("word")->data(['text'=>@$GET_JSON['data'][$a]['text']])->insert();
    				if($result){
    				    $data = $data+1;
    				}else{
    				    
    				}
    			}else{
    				
    			}
		    }
		    echo "<title>写入".$data."条成功</title>";
		    echo "写入".$data."条成功";
		}else{
			echo "内部错误";
		}
		echo "<script>
		window.onload=function(){
		    window.location.replace('https://api.gqink.cn/api/update/words/start/$b/');
		}
	   </script>";
	}
	
	//words
	public function qianming(){
	    $a = Request::instance()->param('start');
	    $b = $a+100;//recommend
	    $url = 'http://api4.qzone.cc/feed/default/get-new-feed-by-feed-tag?feed_tag_id=1019&start='.$a.'&len=100';
		$GET_JSON = GET_JSON($url);
		if(is_array(@$GET_JSON)){
		    $data = 0;
	         for ($a = 0; $a < count(@$GET_JSON['data']); $a++) {
                $result = Db::table("autograph")->where('word',@$GET_JSON['data'][$a]['text'])->find();
    			if($result == null){
    				$result = Db::table("autograph")->data(['word'=>@$GET_JSON['data'][$a]['text'],'keyword'=>"伤感"])->insert();
    				if($result){
    				    $data = $data+1;
    				}else{
    				    
    				}
    			}else{
    				
    			}
		    }
		    echo "<title>写入".$data."条成功</title>";
		    echo "写入".$data."条成功";
		}else{
			echo "内部错误";
		}
		echo "<script>
		window.onload=function(){
		    window.location.replace('https://api.gqink.cn/api/update/qianming/start/$b/');
		}
	   </script>";
	}
	
	public function wangming(){
	    $a = Request::instance()->param('start');
	    $b = $a+100;//recommend
	    $url = 'http://api4.qzone.cc/feed/default/get-new-feed-by-feed-tag?feed_tag_id=1025&start='.$a.'&len=100';
		$GET_JSON = GET_JSON($url);
		if(is_array(@$GET_JSON)){
		    $data = 0;
	         for ($a = 0; $a < count(@$GET_JSON['data']); $a++) {
                $result = Db::table("name")->where('word',@$GET_JSON['data'][$a]['text'])->find();
    			if($result == null){
    				$result = Db::table("name")->data(['word'=>@$GET_JSON['data'][$a]['text'],'keyword'=>"女生"])->insert();
    				if($result){
    				    $data = $data+1;
    				}else{
    				    
    				}
    			}else{
    				
    			}
		    }
		    echo "<title>写入".$data."条成功</title>";
		    echo "写入".$data."条成功";
		}else{
			echo "内部错误";
		}
		echo "<script>
		window.onload=function(){
		    window.location.replace('https://api.gqink.cn/api/update/wangming/start/$b/');
		}
	   </script>";
	}
	
	public function net(){
		require 'extend/netease/GetWangYiYunInfo.php';
        $res = new \GetWangYiYunInfo();
        $data = $res->hot('http://music.163.com/discover/toplist?id=3778678');
        for($a = 0 ; $a < count($data); $a++){
            $result = Db::table('hot')->where('comment_content',@$data[$a]['hotComments'])->find();
    		if($result == null){
    			$name = $data[$a]['song_name'];
    			$images = $data[$a]['cover'];
    			$author = $data[$a]['author'];
    			$mp3_url = $data[$a]['mp3_url'];
    			$comment_nickname = $data[$a]['nickname'];
    			$comment_content = $data[$a]['hotComments'];
    			$love = 520;
    			$result = Db::table('hot')->data(['name'=>$name,'images'=>$images,'author'=>$author,'mp3_url'=>$mp3_url,'comment_nickname'=>$comment_nickname,'comment_content'=>$comment_content,'love'=>$love])->insert();
    			if($result){
    				echo "更新成功";
    			}else{
    				echo "更新失败";
    			}
    		}else{
    			echo "数据重复";
    		}
        }
	}
	
	public function dog(){
	    $a = Request::instance()->param('start');
	    $b = $a+100;//recommend
	    $url = 'https://v1.alapi.cn/api/dog?format=json';
		$GET_JSON = GET_JSON($url);
		if(is_array(@$GET_JSON)){
			if(@$GET_JSON['code'] == 200){
				$result = Db::table("dog")->where('text',@$GET_JSON['data']['content'])->find();
				var_dump($result);
				if($result == null){
    				$result = Db::table("dog")->data(['text'=>@$GET_JSON['data']['content']])->insert();
    				if($result){
    				    echo "写入成功";
    				}else{
    				    echo "失败";
    				}
    			}else{
    				echo "重复";
    			}
			}
		}else{
			echo "内部错误";
		}
		echo "<script>
		window.onload=function(){
		    window.location.replace('https://api.gqink.cn/api/update/dog/');
		}
	   </script>";
	}
}