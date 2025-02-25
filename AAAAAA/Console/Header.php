<?php 
if(isset($_SESSION['Login']) != 1 || isset($_SESSION['LoginTime']) != 1){
	header("Location:./Login.php");
	exit();
};
$LoginSession = $_SESSION['Login'];
if(strpos($_SESSION['Login'], "@")){
	$sql = "SELECT * FROM UserName WHERE Email='$LoginSession'";
	$result = $conn->query($sql);
	 
	if ($result->num_rows > 0) {
	    $row = $result->fetch_assoc();
	}
}else {
	$sql = "SELECT * FROM UserName WHERE UserName='$LoginSession'";
	$result = $conn->query($sql);
	 
	if ($result->num_rows > 0) {
	    $row = $result->fetch_assoc();
	}
}
?>
<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8" />
  <title><?php echo $title;?> - API管理|与梦城</title>
  <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var//libs/assets/animate.css/animate.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var//libs/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var//libs/assets/simple-line-icons/css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var//libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />

  <link rel="stylesheet" href="https://cdn.gqink.cn/var/html/css/font.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var/html/css/app.css" type="text/css" />

</head>
<body>
<div class="app app-header-fixed app-aside-fixed">
  

    <!-- header -->
  <header id="header" class="app-header navbar" role="menu">
      <!-- navbar header -->
      <div class="navbar-header bg-dark">
        <button class="pull-right visible-xs dk" ui-toggle-class="show" target=".navbar-collapse">
          <i class="glyphicon glyphicon-cog"></i>
        </button>
        <button class="pull-right visible-xs" ui-toggle-class="off-screen" target=".app-aside" ui-scroll="app">
          <i class="glyphicon glyphicon-align-justify"></i>
        </button>
        <!-- brand -->
        <a href="./Home.php" class="navbar-brand text-lt">
          <i class="fa fa-database"></i>
          <span class="hidden-folded m-l-xs">与梦城数据</span>
        </a>
        <!-- / brand -->
      </div>
      <!-- / navbar header -->

      <!-- navbar collapse -->
      <div class="collapse pos-rlt navbar-collapse box-shadow bg-white-only">
      	
		<!-- buttons -->
        <div class="nav navbar-nav hidden-xs">
          <a href="#" class="btn no-shadow navbar-btn" ui-toggle-class="show" target="#aside-user">
            <i class="icon-user fa-fw"></i>
          </a>
        </div>
        <!-- / buttons -->
        
        <!-- link and dropdown -->
        <ul class="nav navbar-nav hidden-sm">
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
              <i class="fa fa-fw fa-plus visible-xs-inline-block"></i>
              <span translate="header.navbar.new.NEW">快捷栏</span> <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#" translate="header.navbar.new.PROJECT">Projects</a></li>
              <li>
                <a href>
                  <span class="badge bg-info pull-right">5</span>
                  <span translate="header.navbar.new.TASK">Task</span>
                </a>
              </li>
              <li><a href translate="header.navbar.new.USER">User</a></li>
              <li class="divider"></li>
              <li>
                <a href>
                  <span class="badge bg-danger pull-right">4</span>
                  <span translate="header.navbar.new.EMAIL">Email</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
        <!-- / link and dropdown -->

        <!-- nabar right -->
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
              <i class="icon-bell fa-fw"></i>
              <span class="visible-xs-inline">通知消息</span>
              <span class="badge badge-sm up bg-danger pull-right-xs">2</span>
            </a>
            <!-- dropdown -->
            <div class="dropdown-menu w-xl animated fadeInUp">
              <div class="panel bg-white">
                <div class="panel-heading b-light bg-light">
                  <strong>有<span>1</span> 条新消息</strong>
                </div>
                <div class="list-group">
                  <a href class="list-group-item">
                    <span class="clear block m-b-none">
                      V2.0开发中<br>
                      <small class="text-muted">24小时前</small>
                    </span>
                  </a>
                </div>
              </div>
            </div>
            <!-- / dropdown -->
          </li>
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle clear" data-toggle="dropdown">
              <span class="thumb-sm avatar pull-right m-t-n-sm m-b-n-sm m-l-sm">
                <img src="https://cdn.v2ex.com/gravatar/<?php echo md5($row['Email']);?>?s=65&r=G&d=" alt="...">
                <i class="on md b-white bottom"></i>
              </span>
              <span class="hidden-sm hidden-md"><?php echo $row['UserName'];?></span> <b class="caret"></b>
            </a>
            <!-- dropdown -->
            <ul class="dropdown-menu animated fadeInRight w">
              <li>
                <a href="./UserInfo.php">
                  <span class="badge bg-danger pull-right"><i class="icon-user"></i></span>
                  <span>个人设置</span>
                </a>
              </li>
              <li>
                <a href="./Security.php">
                  <span class="badge bg-danger pull-right"><i class="icon-settings"></i></span>
                  <span>安全设置</span>
                </a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="javascript:LoginOut();">
                  <span class="badge bg-danger pull-right"><i class="icon-login"></i></span>
                  <span>退出登录</span>
                </a>
              </li>
            </ul>
            <!-- / dropdown -->
          </li>
        </ul>
        <!-- / navbar right -->
      </div>
      <!-- / navbar collapse -->
  </header>
  <!-- / header -->


    <!-- aside -->
  <aside id="aside" class="app-aside hidden-xs bg-dark">
      <div class="aside-wrap">
        <div class="navi-wrap">
          <!-- user -->
          <div class="clearfix hidden-xs text-center hide show" id="aside-user">
            <div class="dropdown wrapper">
              <a href="./UserInfo.php">
                <span class="thumb-lg w-auto-folded avatar m-t-sm">
                  <img src="https://cdn.v2ex.com/gravatar/<?php echo md5($row['Email']);?>?s=200&r=G&d=" class="img-full" alt="...">
                </span>
              </a>
              <a href="#" data-toggle="dropdown" class="dropdown-toggle hidden-folded">
                <span class="clear">
                  <span class="block m-t-sm">
                    <strong class="font-bold text-lt"><?php echo $row['UserName'];?></strong> 
                    <b class="caret"></b>
                  </span>
                  <span class="text-muted text-xs block"><?php echo $row['Email'];?></span>
                </span>
              </a>
              <!-- dropdown -->
              <ul class="dropdown-menu animated fadeInRight w hidden-folded">
                <li>
                  <a href="./UserInfo.php">个人设置<span class="badge bg-danger pull-right"><i class="icon-user"></i></span></a>
                </li>
                <li>
                  <a href="./Security.php">安全设置<span class="badge bg-danger pull-right"><i class="icon-settings"></i></span></a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="javascript:LoginOut();">退出登录<span class="badge bg-danger pull-right"><i class="icon-login"></i></span></a>
                </li>
              </ul>
              <!-- / dropdown -->
            </div>
            <div class="line dk hidden-folded"></div>
          </div>
          <!-- nav -->
          <nav ui-nav class="navi clearfix">
            <ul class="nav">
              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>数据统计</span>
              </li>
              <li>
                <a href="./Home.php">
                  <b class="badge bg-info pull-right">9</b>
                  <i class="fa fa-home"></i>
                  <span class="font-bold">用户首页</span>
                </a>
              </li>
              <li>
                <a href="./State.php">
                  <b class="badge bg-info pull-right">9</b>
                  <i class="icon-bar-chart"></i>
                  <span class="font-bold">调用统计</span>
                </a>
              </li>
              <li class="line dk"></li>

              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>数据接口</span>
              </li>
              <li>
                <a href class="auto">
                  <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                  </span>
                  <i class="glyphicon glyphicon-edit"></i>
                  <span>数据接口</span>
                </a>
                <ul class="nav nav-sub dk" id="API">
                  
                </ul>
              </li>

              <li class="line dk hidden-folded"></li>

              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">          
                <span>用户设置</span>
              </li>  
              <li>
                <a href="./UserInfo.php">
                  <i class="icon-user"></i>
                  <span>个人设置</span>
                </a>
              </li>
              <li>
                <a href="./Security.php">
                  <i class="icon-settings"></i>
                  <span>安全设置</span>
                </a>
              </li>
              <li>
                <a href="javascript:LoginOut();">
                  <i class="icon-login"></i>
                  <span>退出登录</span>
                </a>
              </li>
            </ul>
          </nav>
          <!-- nav -->

          <!-- aside footer -->
          <div class="wrapper m-t">
            <div class="text-center-folded">
              <span class="pull-right pull-none-folded" id="Cpu"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>
              <span class="hidden-folded">CPU</span>
            </div>
            <div class="progress progress-xxs m-t-sm dk">
              <div class="progress-bar progress-bar-danger" id="Cpu_css" style="width: 100%;">
              </div>
            </div>
            <div class="text-center-folded">
              <span class="pull-right pull-none-folded" id="Memory"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>
              <span class="hidden-folded">内存</span>
            </div>
            <div class="progress progress-xxs m-t-sm dk">
              <div class="progress-bar progress-bar-danger" id="Memory_css" style="width: 100%;">
              </div>
            </div>
          </div>
          <!-- / aside footer -->
        </div>
      </div>
  </aside>
  <!-- / aside -->


  <!-- content -->
  <div id="content" class="app-content" role="main">
  	<div class="app-content-body ">
	    

<div class="hbox hbox-auto-xs hbox-auto-sm" ng-init="
    app.settings.asideFolded = false; 
    app.settings.asideDock = false;
  ">