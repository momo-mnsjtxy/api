<?php 
include_once('./Conf/Config.php');
exit("开发中");
?>
<!DOCTYPE html>
<html lang="en">
<head>  
  <meta charset="utf-8" />
  <title>API系统 - 与梦城</title>
  <meta name="description" content="API系统 - 与梦城" />
  <meta name="keywords" content="API接口,接口,与梦城,ymc,gqink.cn" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var//libs/assets/animate.css/animate.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var//libs/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var//libs/assets/simple-line-icons/css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var//libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />

  <link rel="stylesheet" href="https://cdn.gqink.cn/var/landing/css/font.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var/landing/css/app.css" type="text/css" />

</head>
<body>
	
  <!-- header -->
  <header id="header" class="navbar navbar-fixed-top bg-white-only padder-v"  data-spy="affix" data-offset-top="1">
    <div class="container">
      <div class="navbar-header">
        <button class="btn btn-link visible-xs pull-right m-r" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
        <a href="#" class="navbar-brand m-r-lg"><span class="h3 font-bold">与梦城</span></a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav font-bold">
          <li>
            <a href="#what" data-ride="scroll">介绍</a>
          </li>
          <li>
            <a href="#why" data-ride="scroll">用处</a>
          </li>
          <li>
            <a href="#features" data-ride="scroll">功能</a>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li>
            <div class="m-t-sm">
              <?php 
              if(isset($_SESSION['Login']) == 1 && isset($_SESSION['LoginTime']) == 1){
					echo '<a href="./Console/Login.php"   class="btn btn-sm btn-success btn-rounded m-l"><strong>管理控制台</strong></a>
					<a href="javascript:LoginOut();" class="btn btn-link btn-sm">退出登录</a>';
			  }else {
			  	 echo '<a href="./Console/Register.php"  class="btn btn-link btn-sm">注册</a> or 
              <a href="./Console/Login.php"   class="btn btn-sm btn-success btn-rounded m-l"><strong>登录</strong></a>';
			  }
              ?>
            </div>
          </li>
        </ul>     
      </div>
    </div>
  </header>
  <!-- / header -->
  <div id="content">
    
    <div class="bg-white-only">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-md-offset-2 text-center">
            <div class="m-t-xxl m-b-xxl padder-v">
              <h1 class="font-bold l-h-1x m-t-xxl text-black padder-v animated fadeInDown">
                基于API全新结构
                <br><span class="b-b b-black b-3x">v2</span> 更灵活、更一致、更敏捷
              </h1>
              <h3 class="text-muted m-t-xl l-h-1x">New structure based on API, more flexible, more consistent and more agile
              <span class="b-b b-2x">v2</span>.gqink.cn</h3>
            </div>
            <p class="text-center m-b-xxl wrapper">
              <a href="./Console/Register.php"  class="btn b-2x btn-lg b-black btn-default btn-rounded text-lg font-bold m-b-xxl animated fadeInUpBig">立即使用</a>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div id="what" class="padder-v bg-white-only">
      <div class="container m-b-xxl">
        <div class="row no-gutter">
          <div class="col-md-3 col-sm-6">
            <div class="bg-light m-r-n-md no-m-xs no-m-sm">
              <a href="../angular" class="wrapper-xl block">
                <span class="h3 m-t-sm text-black">专业</span>
                <span class="block m-b-md m-t">专业的开发，针对开发者定制各种数据</span>
                <i class="icon-arrow-right text-lg"></i>
              </a>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="bg-black dker m-t-xl m-l-n-md m-r-n-md text-white no-m-xs no-m-sm">
              <a href="../html" class="wrapper-xl block">
                <span class="h3 m-t-sm text-white">标准</span>
                <span class="block m-b-md m-t">接口标准，对接方式简捷，减少开发成本，提高开发效率</span>
                <i class="icon-arrow-right text-lg"></i>
              </a>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="bg-light dker m-t-n m-l-n-md m-r-n-md no-m-xs no-m-sm">
              <a href="../angular/#music/home" class="wrapper-xl block">
                <span class="h3 m-t-sm text-black">极速</span>
                <span class="block m-b-md m-t">极速响应，利于企业及时获取数据，提升服务体验</span>
                <i class="icon-arrow-right text-lg"></i>
              </a>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="bg-white dker m-t m-l-n-md m-r-n-md no-m-xs no-m-sm">
              <a href="../angular/material.html" class="wrapper-xl block">
                <span class="h3 m-t-sm text-black">稳定</span>
                <span class="block m-b-md m-t">多台服务器并行响应，稳定性99.99%</span>
                <i class="icon-arrow-right text-lg"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="why" class="bg-light">
      <div class="container">
        <div class="m-t-xxl m-b-xl text-center wrapper">
          <h2 class="text-black font-bold"><span class="b-b b-dark">数据接口</span> 的价值</h2>
          <p class="text-muted h4 m-b-xl">The value of API interface</p>
        </div>
        <div class="row m-t-xl m-b-xl text-center">
          <div class="col-sm-4" data-ride="animated" data-animation="fadeInLeft" data-delay="300">
            <p class="h3 m-b-xl inline b b-dark rounded wrapper-lg">
              <i class="fa w-1x fa-google"></i>
            </p>
            <div class="m-b-xl">
              <h4 class="m-t-none">更灵活</h4>
              <p class="text-muted m-t-lg">将客户端与企业应用程序的直接耦合和依赖性隔离开，
有助于源系统的独立更新、升级、部署，以及多后端操作。</p>
            </div>
          </div>
          <div class="col-sm-4" data-ride="animated" data-animation="fadeInLeft" data-delay="600">
            <p class="h3 m-b-xl inline b b-dark rounded wrapper-lg">
              <i class="fa w-1x fa-gears"></i>
            </p>
            <div class="m-b-xl">
              <h4 class="m-t-none">一致性</h4>
              <p class="text-muted m-t-lg">提供一致的数据转换、负载均衡、安全、监控和流量管理。</p>
            </div>
          </div>
          <div class="col-sm-4" data-ride="animated" data-animation="fadeInLeft" data-delay="900">
            <p class="h3 m-b-xl inline b b-dark rounded wrapper-lg">
              <i class="fa w-1x fa-clock-o"></i>
            </p>
            <div class="m-b-xl">
              <h4 class="m-t-none">更敏捷</h4>
              <p class="text-muted m-t-lg">进与其他系统和设备的集成，以及多后端系统数据的使用。</p>
            </div>
          </div>
        </div>
        <div class="m-t-xl m-b-xl text-center wrapper">
          <p class="h4">支持调理数据类型
            <span class="text-primary">Xml</span>, 
            <span class="text-primary">Json</span>, 
            <span class="text-primary">TXT</span>...
          </p>
          <p class="m-t-xl"><a href="./Console/Register.php"  class="btn btn-lg btn-white b-2x b-dark btn-rounded bg-empty m-sm">立即使用</a></p>
        </div>
      </div>
    </div>
    <div id="features" class="bg-white-only">
      <div class="container">
        <div class="row m-t-xl m-b-xxl">
          <div class="col-sm-6" data-ride="animated" data-animation="fadeInLeft" data-delay="300">
              <div class="m-t-xxl">
                <div class="m-b">
                  <a href class="pull-left thumb-sm avatar"><img src="https://api.gqink.cn/qlogo/?qq=1825642827" alt="..."></a>
                  <div class="m-l-sm inline">
                    <div class="pos-rlt wrapper b b-light r r-2x">
                      <span class="arrow left pull-up"></span>
                      <p class="m-b-none">开发价值</p>
                    </div>
                  </div>
                </div>
                <div class="m-b text-right">
                  <a href class="pull-right thumb-sm avatar"><img src="https://cdn.gqink.cn/blog/logo.png" class="img-circle" alt="..."></a>
                  <div class="m-r-sm inline text-left">
                    <div class="pos-rlt wrapper bg-primary r r-2x">
                      <span class="arrow right pull-up arrow-primary"></span>
                      <p class="m-b-none">提升IT资源可复用性</p>
                    </div>
                  </div>
                </div>
                <div class="m-b">
                  <a href class="pull-left thumb-sm avatar"><img src="https://api.gqink.cn/qlogo/?qq=1825642827" alt="..."></a>
                  <div class="m-l-sm inline">
                    <div class="pos-rlt wrapper b b-light r r-2x">
                      <span class="arrow left pull-up"></span>
                      <p class="m-b-none">管理价值</p>
                    </div>
                  </div>
                </div>
                <div class="m-b text-right">
                  <a href class="pull-right thumb-sm avatar"><img src="https://cdn.gqink.cn/blog/logo.png" class="img-circle" alt="..."></a>
                  <div class="m-r-sm inline text-left">
                    <div class="pos-rlt wrapper bg-info r r-2x">
                      <span class="arrow right pull-up arrow-info"></span>
                      <p class="m-b-none">可视化实时跟踪API调用情况</p>
                    </div>
                  </div>
                </div>
                <div class="m-b">
                  <a href class="pull-left thumb-sm avatar"><img src="https://api.gqink.cn/qlogo/?qq=1825642827" alt="..."></a>
                  <div class="m-l-sm inline">
                    <div class="pos-rlt wrapper b b-light r r-2x">
                      <span class="arrow left pull-up"></span>
                      <p class="m-b-none">应用价值</p>
                    </div>
                  </div>
                </div>
                <div class="m-b text-right">
                  <a href class="pull-right thumb-sm avatar"><img src="https://cdn.gqink.cn/blog/logo.png" class="img-circle" alt="..."></a>
                  <div class="m-r-sm inline text-left">
                    <div class="pos-rlt wrapper bg-info r r-2x">
                      <span class="arrow right pull-up arrow-info"></span>
                      <p class="m-b-none">实现API资源共享</p>
                    </div>
                  </div>
                </div>   
              </div>
          </div>
          <div class="col-sm-6 wrapper-xl">
            <h3 class="text-dark font-bold m-b-lg">全方位角色赋能</h3>
            <ul class="list-unstyled  m-t-xl">
              <li data-ride="animated" data-animation="fadeInUp" data-delay="600">
                <i class="icon-check pull-left text-lg m-r m-t-sm"></i>
                <p class="clear m-b-lg"><strong>开发价值</strong>: 提升IT资源可复用性；减少重复开发最多60%；减少沟通成本；API高配置化功能；减少代码量；提升开发效率；优化系统间对接模式；解耦合实现自定义与敏捷集成 </p>
              </li>
              <li data-ride="animated" data-animation="fadeInUp" data-delay="900">
                <i class="icon-check pull-left text-lg m-r m-t-sm"></i>
                <p class="clear m-b-lg"><strong>管理价值</strong>: 企业IT运维可视化实时跟踪API调用情况；获得企业级洞察结果；安全管控完善的API调用授权和监控机制；全方位降低；潜在调用风险；API资产管理实现API统一管理</p>
              </li>
              <li data-ride="animated" data-animation="fadeInUp" data-delay="1100">
                <i class="icon-check pull-left text-lg m-r m-t-sm"></i>
                <p class="clear m-b-lg"><strong>应用价值</strong>：增加应用类项目的价值和收益；大幅缩短项目落时间；开发人员进行敏捷开发；实现API资源共享</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div> 
    <div class="bg-light">
      <div class="container">
        <div class="row m-t-xl m-b-xxl">
          <div class="col-sm-6 wrapper-xl">
            <h3 class="text-black font-bold m-b-lg">在所有屏幕上响应</h3>
            <p class="h4 l-h-1x">一个响应灵敏的数据系统，目标是广泛的设备，如台式机、平板电脑、智能手机等</p>
          </div>
          <div class="col-sm-6" data-ride="animated" data-animation="fadeInLeft" data-delay="300">
            <div class="m-t-xxl text-center">
              <span class="text-2x text-muted"><i class="icon-screen-smartphone text-2x"></i></span>
              <span class="text-3x text-muted"><span class="text-2x"><i class="icon-screen-desktop text-2x"></i></span></span>
              <span class="text-3x text-muted"><i class="icon-screen-tablet text-2x"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white-only">
      <div class="container">
        <div class="row m-t-xl m-b-xxl">
          <div class="col-sm-6" data-ride="animated" data-animation="fadeInLeft" data-delay="300">
            <div class="m-t-xxl text-center">
              <p>
              <a href="http://themeforest.net/user/Flatfull/portfolio?ref=flatfull"  class="text-sm btn btn-lg btn-rounded btn-default m-sm">
                <i class="fa fa-apple fa-3x pull-left m-l-sm"></i>
                <span class="block clear m-t-xs text-left m-r m-l">APP下载 <b class="text-lg block font-bold">IOS</b>
                </span>
              </a>
              </p>
              <p>
              <a href="index.html"  class="text-sm btn btn-lg btn-rounded btn-default m-sm">
                <i class="fa fa-android fa-3x pull-left m-l-sm"></i>
                <span class="block clear m-t-xs text-left m-r m-l">APP下载 <b class="text-lg block font-bold">Android</b>
                </span>
              </a>
              </p>
            </div>
          </div>
          <div class="col-sm-6 wrapper-xl">
            <h3 class="text-black font-bold m-b-lg">APP在线数据管理</h3>
            <p class="h4 l-h-1x">用户可以通过website及Android和IOS系统下APP查询API的实时调理情况及数据统。简介的的网页管理及便捷的移动端管理</p>
          </div>
        </div>
      </div>
    </div>
  <!-- footer -->
  <footer id="footer">
    <div class="bg-info">
      <div class="container">
        <div class="row m-t-xl m-b-xl">
          <div class="col-sm-6 text-white text-center">
            <h4 class="m-b">你准备好使用了吗？</h4>
          </div>
          <div class="col-sm-6 text-center">
            <a href="./Console/Register.php"  class="btn btn-lg btn-default btn-rounded">立即注册</a>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white">
      <div class="container">
        <div class="row m-t-xl m-b-xl">
          <div class="col-sm-3">
            <h4 class="text-u-c m-b font-thin"><span class="b-b b-dark font-bold">友情</span> 链接</h4>
            <ul class="list-unstyled">
              <li><a href="#"><i class="fa fa-angle-right m-r-sm"></i>博客</a></li>
              <li><a href="#"><i class="fa fa-angle-right m-r-sm"></i>主页</a></li>
            </ul>
          </div>
          <div class="col-sm-3">
            <h4 class="text-u-c m-b font-thin"><span class="b-b b-dark font-bold">联系</span> 我们</h4>
            <p class="text-sm">7*24小时 <br>
              19866618835<br>
             </p>
             <p>qq: <a href="mailto:admin@gqink.cn">admin@gqink.cn</a></p>
             <p class="m-b-xl">email: <a href="mailto:admin@gqink.cn">admin@gqink.cn</a></p>
          </div>
          <div class="col-sm-3">
            <h4 class="text-u-c m-b-xl font-thin"><span class="b-b b-dark font-bold">关注</span> 我们</h4>
            <div class="m-b-xl">
              <a href="#" class="btn btn-icon btn-rounded btn-dark"><i class="fa fa-facebook"></i></a>
              <a href="#" class="btn btn-icon btn-rounded btn-dark"><i class="fa fa-twitter"></i></a>
              <a href="#" class="btn btn-icon btn-rounded btn-dark"><i class="fa fa-google-plus"></i></a>
              <a href="#" class="btn btn-icon btn-rounded btn-dark"><i class="fa fa-linkedin"></i></a>
              <a href="#" class="btn btn-icon btn-rounded btn-dark"><i class="fa fa-pinterest"></i></a>
            </div>
          </div>
          <div class="col-sm-3">
            <h4 class="text-u-c m-b font-thin"><span class="b-b b-dark font-bold">订阅</span> 邮箱</h4>
            <p>不想错过什么吗？订阅我们的通讯箱</p>
            <form class="form-inline m-t m-b text-center m-b-xl">
              <div class="aside-xl inline">
                <div class="input-group">
                    <input type="text" id="address" name="address" class="form-control btn-rounded" placeholder="Your email">
                    <span class="input-group-btn">
                      <button class="btn btn-default btn-rounded" type="submit">订阅</button>
                    </span>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-light dk">
      <div class="container">
        <div class="row padder-v m-t">
          <div class="col-xs-8">
            <ul class="list-inline">
              <li><a href="#">博客</a></li>
              <li><a href="#">主页</a></li>
            </ul> 
          </div>
          <div class="col-xs-4 text-right">
            与梦城 &copy; 2019
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- / footer -->

  <script src="https://cdn.gqink.cn/var//libs/jquery/jquery/dist/jquery.js"></script>
  <script src="https://cdn.gqink.cn/var//libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
  <script src="https://cdn.gqink.cn/var//libs/jquery/jquery_appear/jquery.appear.js"></script>
  <script src="https://cdn.gqink.cn/var/landing/js/landing.js"></script>
  <script src="https://cdn.gqink.cn/layer/3.1.1/layer.js"></script>
  <script>
	function LoginOut(){
		$.ajax({
			type: "post",
			url: "./Conf/Login.php",
			data: {LoginOut:1},   
			timeout: 5000,   
			async: true,  
			beforeSend:function(){
				layer.load();
			},
			complete:function(){
				layer.closeAll('loading');
			},
			error: function() {
				layer.closeAll('loading');
				layer.msg("网络错误");
			}, 
			success: function(Result) {
				if(Result.code == 1){
					layer.msg(Result.msg);
					setTimeout (function(){
						location.href='/';
					},2000);
				}else{
					layer.msg(Result.msg);
				}
			}
		}); 
		};
	</script>
</body>
</html>
