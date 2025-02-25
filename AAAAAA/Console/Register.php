<?php
include_once('../Conf/Config.php');
if(isset($_SESSION['Login']) == 1 && isset($_SESSION['LoginTime']) == 1){
	header("Location:./Home.php");
	exit();
};
?>
<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8" />
  <title>账户注册 - API管理|与梦城</title>
  <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var/libs/assets/animate.css/animate.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var/libs/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var/libs/assets/simple-line-icons/css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var/libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />

  <link rel="stylesheet" href="https://cdn.gqink.cn/var/html/css/font.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.gqink.cn/var/html/css/app.css" type="text/css" />

</head>
<body>
<div class="app app-header-fixed ">
  

    <div class="container w-xxl w-auto-xs" ng-controller="SignupFormController" ng-init="app.settings.container = false;">
      <a href class="navbar-brand block m-t">账户注册</a>
      <div class="m-b-lg">
        <div class="wrapper text-center">
          <strong>填写您的信息注册账户</strong>
        </div>
          <div class="list-group list-group-sm">
            <div class="list-group-item">
              <input type="text" id="UserName" placeholder="用户名" class="form-control no-border"  required>
            </div>
            <div class="list-group-item">
              <input type="email" id="Email" placeholder="邮箱账号" class="form-control no-border" required>
            </div>
            <div class="list-group-item">
               <input type="password" id="Password" placeholder="密码" class="form-control no-border" required>
            </div>
            <div class="list-group-item">
               <input type="password" id="IsPassword" placeholder="确定密码" class="form-control no-border" required>
            </div>
          </div>
          <div class="checkbox m-b-md m-t-none">
            <label class="i-checks">
              <input type="checkbox" id="Agreement" required><i></i> 我同意 <a href>条款和政策</a>
            </label>
          </div>
          <button type="submit" class="btn btn-lg btn-primary btn-block" id="register">注册</button>
          <div class="line line-dashed"></div>
          <p class="text-center"><small>已经有帐户了？</small></p>
          <a href="./Login.php" class="btn btn-lg btn-default btn-block">登录</a>
      </div>
      <div class="text-center">
        <p>
  <small class="text-muted">与梦城<br>&copy; 2019</small>
</p>
      </div>
    </div>


</div>

<script src="https://cdn.gqink.cn/var/libs/jquery/jquery/dist/jquery.js"></script>
<script src="https://cdn.gqink.cn/var/libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<script src="https://cdn.gqink.cn/var/html/js/ui-load.js"></script>
<script src="https://cdn.gqink.cn/var/html/js/ui-jp.config.js"></script>
<script src="https://cdn.gqink.cn/var/html/js/ui-jp.js"></script>
<script src="https://cdn.gqink.cn/var/html/js/ui-nav.js"></script>
<script src="https://cdn.gqink.cn/var/html/js/ui-toggle.js"></script>
<script src="https://cdn.gqink.cn/var/html/js/ui-client.js"></script>
<script src="https://cdn.gqink.cn/layer/3.1.1/layer.js"></script>
<script>
	$("#register").click(function(){
		var UserName = $("#UserName").val();
		var Email = $("#Email").val();
		var Password = $("#Password").val();
		var IsPassword = $("#IsPassword").val();
		if(UserName == ""){
			layer.msg("请输入用户名");
		}else if(Email == ""){
			layer.msg("请输入邮箱账号");
		}else if(Password == ""){
			layer.msg("请输入密码");
		}else if(IsPassword == ""){
			layer.msg("请输入确定密码");
		}else if(Password != IsPassword){
			layer.msg("两次密码不一致");
		}else if(!$("input[type='checkbox']").is(':checked')){
			layer.msg("请同意协议条款");
		}else{
			$.ajax({
				type: "post",
				url: "../Conf/Register.php",
				data: {UserName:UserName,Email:Email,Password:Password,IsPassword:IsPassword},   
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
						layer.msg("账号注册成功");
						setTimeout (function(){
							location.href='/';
						},2000);
					}else{
						layer.msg(Result.msg);
					}
				}
			}); 
		}
	});
</script>
</body>
</html>
