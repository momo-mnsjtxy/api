<?php
$title = '用户首页';
include_once('../Conf/Config.php');
include_once('./Header.php');
?>

 <!-- main -->
  <div class="col">
    <div class="bg-light lter b-b wrapper-md">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <h1 class="m-n font-thin h3 text-black"><?php echo $title;?></h1>
          <small class="text-muted">欢迎使用与梦城数据API</small>
        </div>
      </div>
    </div>
    <!-- / main header -->
    <div class="wrapper-md" ng-controller="FlotChartDemoCtrl">
      <!-- stats -->
      <div class="row">
        <div class="col-md-5">
          <div class="row row-sm text-center">
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="h1 text-info font-thin h1" id="AAPI">Loading</div>
                <span class="text-muted text-xs">总数据接口</span>
                <div class="top text-right w-full">
                  <i class="fa fa-caret-down text-warning m-r-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-6">
              <a href class="block panel padder-v bg-primary item">
                <span class="text-white font-thin h1 block" id="BAPI">Loading</span>
                <span class="text-muted text-xs">已开通数据接口</span>
                <span class="bottom text-right w-full">
                  <i class="fa fa-cloud-upload text-muted m-r-sm"></i>
                </span>
              </a>
            </div>
            <div class="col-xs-6">
              <a href class="block panel padder-v bg-info item">
                <span class="text-white font-thin h1 block" id="ALLANUM">Loading</span>
                <span class="text-muted text-xs">接口调用次数</span>
                <span class="top">
                  <i class="fa fa-caret-up text-warning m-l-sm m-r-sm"></i>
                </span>
              </a>
            </div>
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="font-thin h1">Loading</div>
                <span class="text-muted text-xs">今日调用次数</span>
                <div class="bottom">
                  <i class="fa fa-caret-up text-warning m-l-sm m-r-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-12 m-b-md">
              <div class="r bg-light dker item hbox no-border">
                <div class="col dk padder-v r-r">
                  <div class="text-primary-dk font-thin h1" id="BALLNUM">Loading</div>
                  <span class="text-muted text-xs">剩余调用次数</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="panel wrapper">
            <label class="i-switch bg-warning pull-right" ng-init="showSpline=true">
              <input type="checkbox" ng-model="showSpline">
              <i></i>
            </label>
            <h4 class="font-thin m-t-none m-b text-muted">近七日总调用情况</h4>
            <div ui-jq="plot" ui-refresh="showSpline" ui-options="
              [
                { data: [ [0,7],[1,6.5],[2,12.5],[3,7],[4,9],[5,6],[6,11],[7,6.5]], label:'TV', points: { show: true, radius: 1}, splines: { show: true, tension: 0.4, lineWidth: 1, fill: 0.8 } },
                { data: [ [0,4],[1,4.5],[2,7],[3,4.5],[4,3],[5,3.5],[6,6],[7,3]], label:'Mag', points: { show: true, radius: 1}, splines: { show: true, tension: 0.4, lineWidth: 1, fill: 0.8 } }
              ], 
              {
                colors: ['#23b7e5', '#7266ba'],
                series: { shadowSize: 3 },
                xaxis:{ font: { color: '#a1a7ac' } },
                yaxis:{ font: { color: '#a1a7ac' }, max:20 },
                grid: { hoverable: true, clickable: true, borderWidth: 0, color: '#dce5ec' },
                tooltip: true,
                tooltipOpts: { content: 'Visits of %x.1 is %y.4',  defaultTheme: false, shifts: { x: 10, y: -25 } }
              }
            " style="height:246px" >
            </div>
          </div>
        </div>
      </div>
      <!-- / stats -->

      <!-- tasks -->
      <div class="row">
        <div class="col-md-6">
          <div class="panel no-border">
            <div class="panel-heading wrapper b-b b-light">
              <span class="text-xs text-muted pull-right" id="ANUMS">
                
              </span>
              <h4 class="font-thin m-t-none m-b-none text-muted">已开通数据接口</h4>              
            </div>
            <ul class="list-group list-group-lg m-b-none" id="ALLLIST">
            	Loading
            </ul>
            <div class="panel-footer">
              <span class="pull-right badge bg-info m-t-xs" id="ALLNUMS">Loading</span>
              <button class="btn btn-default btn-addon btn-sm"><i class="fa fa-plus"></i>开通更多数据接口</button>
            </div>
          </div>
        </div>
        <div class="col-md-6">            
          <div class="list-group list-group-lg list-group-sp">
          	<a herf class="list-group-item clearfix">
              <span class="pull-left thumb-sm avatar m-r">
                <img src="https://cdn.v2ex.com/gravatar/99816921fffed9a501768ff49daf6901?s=65&r=G&d=" alt="...">
                <i class="on b-white right"></i>
              </span>
              <span class="clear">
                <span>系统消息</span>
                <small class="text-muted clear text-ellipsis">账号注册成功</small>
              </span>
            </a>
            <a herf class="list-group-item clearfix">
              <span class="pull-left thumb-sm avatar m-r">
                <img src="https://cdn.v2ex.com/gravatar/99816921fffed9a501768ff49daf6901?s=65&r=G&d=" alt="...">
                <i class="on b-white right"></i>
              </span>
              <span class="clear">
                <span>系统消息</span>
                <small class="text-muted clear text-ellipsis">请验证邮箱账号</small>
              </span>
            </a>
            <a herf class="list-group-item clearfix">
              <span class="pull-left thumb-sm avatar m-r">
                <img src="https://cdn.v2ex.com/gravatar/99816921fffed9a501768ff49daf6901?s=65&r=G&d=" alt="...">
                <i class="on b-white right"></i>
              </span>
              <span class="clear">
                <span>系统消息</span>
                <small class="text-muted clear text-ellipsis">请绑定并验证手机号</small>
              </span>
            </a>
          </div>
        </div>
      </div>
      <!-- / tasks -->
    </div>
  </div>
  <!-- / main -->
  <!-- right col -->
  <div class="col w-md bg-white-only b-l bg-auto no-border-xs">
    <div class="tab-content">
		<div class="panel no-border">
			<div class="panel-heading wrapper b-b b-light">
			  <span class="text-xs text-muted pull-right" id="ANUMSS">
			  	
			  </span>
			  <h4 class="font-thin m-t-none m-b-none text-muted">已开通数据接口</h4>              
			</div>
			<ul class="list-group list-group-lg m-b-none" id="ALLLISTS">
				Loading
			</ul>
			<div class="panel-footer">
			  <span class="pull-right badge bg-info m-t-xs" id="ALLNUMSS"> Loading</span>
			  <button class="btn btn-default btn-addon btn-sm"><i class="fa fa-plus"></i>开通更多数据接口</button>
			</div>
		</div>
    </div>
    <div class="padder-md">      
      <!-- streamline -->
      <div class="m-b text-md">近七天账号记录</div>
      <div class="streamline b-l m-b">
        <div class="sl-item b-l">
          <div class="m-l">
            <div class="text-muted"> <?php echo date('Y-m-d h:i:s', time());?></div>
            <p>开通数据接口 <span class="text-danger">一言</span>.</p>
          </div>
        </div>
        <div class="sl-item b-l">
           <div class="m-l">
            <div class="text-muted"> <?php echo date('Y-m-d h:i:s', time());?></div>
            <p>IP <span class="text-danger"><? echo $_SERVER['REMOTE_ADDR'];?></span> 登陆成功</a>.</p>
          </div>
        </div>
      </div>
      <!-- / streamline -->
    </div>
  </div>
  <!-- / right col -->
  
<?php
include_once('./Footer.php');
?>
<script>
$(function(){
	$.ajax({
		type: "post",
		url: "../Conf/Home.php",
		data: {action:'UserApi'},   
		timeout: 5000,   
		async: true,  
		beforeSend:function(){
			
		},
		complete:function(){
			
		},
		error: function() {
			
		}, 
		success: function(Result) {
			if(Result){
				$("#AAPI").html(Result.APINUM);
				$("#BAPI").html(Result.UserApi.length);
				var ALLBNUM = 0;
				var BALLNUM = 0;
				var html = '';
				for(var i = 0 ; i < Result.UserApi.length ; i++){
					ALLBNUM = Number(ALLBNUM) + Number(Result.UserApi[i].B)
					BALLNUM = (Number(BALLNUM) + Number(Result.UserApi[i].A));
					html += '<li class="list-group-item">'+
			                '<span class="pull-right label bg-info">总'+Result.UserApi[i].A+' / 剩'+Result.UserApi[i].B+'</span>'+
			                '<a href>'+Result.UserApi[i].ApiName+'</a>'+
			              '</li>';
				};
				$("#ALLANUM").html(ALLBNUM);
				$("#BALLNUM").html(BALLNUM-Number(ALLBNUM));
				$("#ALLLIST").html(html);
				$("#ALLLISTS").html(html);
				$("#ANUMS").html('<span class="pull-right label bg-info">'+Result.UserApi.length+'</span>');
				$("#ANUMSS").html('<span class="pull-right label bg-info">'+Result.UserApi.length+'</span></span>');
			}
		}
	}); 
})
</script>
<?php 
include_once('./JavaScript.php');
?>