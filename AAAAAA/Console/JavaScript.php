
<script>
function returnFloat(value){
	return value.toFixed(2)+'%';
};
function getPercent(curNum, totalNum, isHasPercentStr) {
    curNum = parseFloat(curNum);
    totalNum = parseFloat(totalNum);

    if (isNaN(curNum) || isNaN(totalNum)) {
        return 'Error';
    }

    return isHasPercentStr ?
        totalNum <= 0 ? '0%' : (Math.round(curNum / totalNum * 10000) / 100.00 + '%') :
        totalNum <= 0 ? 0 : (Math.round(curNum / totalNum * 10000) / 100.00);
};
function getPercents(curNum, totalNum, isHasPercentStr) {
    curNum = parseFloat(curNum);
    totalNum = parseFloat(totalNum);

    if (isNaN(curNum) || isNaN(totalNum)) {
        return 'Error';
    }

    return isHasPercentStr ?
        totalNum <= 0 ? '0%' : (Math.round(curNum / totalNum * 10000) / 100.00) :
        totalNum <= 0 ? 0 : (Math.round(curNum / totalNum * 10000) / 100.00);
};
$(function(){
	$.ajax({
	    type : "get",
	    url : "https://api.gqink.cn/State/info.php?action=fetch",
	    async : true,
	    beforeSend : function(){
	    	
	    },
	    complete : function(){
	    	
	    },
	    error : function(){
	        
	    },
	    success : function(data){
	    	if(data){
	    	    var cpu = data.serverStatus.cpuUsage['user']+data.serverStatus.cpuUsage['nice']+data.serverStatus.cpuUsage['sys'];
		    	$("#Cpu").html(returnFloat(cpu));
		    	$("#Cpu_css").css("width",returnFloat(cpu));
		    	if(cpu<70){
		    		$("#Cpu_css").removeClass();
		    		$("#Cpu_css").addClass("progress-bar progress-bar-success");
		    	}
		    	if(cpu>=70){
		    		$("#Cpu_css").removeClass();
		    		$("#Cpu_css").addClass("progress-bar progress-bar-warning");
		    	}
		    	if(cpu>=90){
		    		$("#Cpu_css").removeClass();
		    		$("#Cpu_css").addClass("progress-bar progress-bar-danger");
		    	}
		    	
		    	var memory_value = data.serverStatus.memRealUsage['value'];
		    	var memory_max = data.serverStatus.memRealUsage['max'];
		    	$("#Memory").html(getPercent(memory_value,memory_max,memory_value));
		    	$("#Memory_css").css("width",getPercent(memory_value,memory_max,memory_value));
		    	var me = getPercents(memory_value,memory_max,memory_value);
		    	if(me<70){
		    		$("#Memory_css").removeClass();
		    		$("#Memory_css").addClass("progress-bar bg-success");
		    	}
		    	if(me>=70){
		    		$("#Memory_css").removeClass();
		    		$("#Memory_css").addClass("progress-bar bg-warning");
		    	}
		    	if(me>=90){
		    		$("#Memory_css").removeClass();
		    		$("#Memory_css").addClass("progress-bar bg-danger");
		    	}
	    	}
	    },
	});
});
function LoginOut(){
	$.ajax({
		type: "post",
		url: "../Conf/Login.php",
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
$(function(){
	$.ajax({
		type: "post",
		url: "../Conf/Home.php",
		data: {action:'API'},   
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
				var html = '<li class="nav-sub-header"><a href><span>数据接口</span></li>';
				for(var i = 0 ; i < Result.length ; i++){
					html += '<li><a href="./api.php?Cname='+Result[i].Cname+'"><span>'+Result[i].ApiName+'</span></a></li>';
				}
				$("#API").html(html);
				$("#ALLNUMS").html(Result.length);
				$("#ALLNUMSS").html(Result.length);
			}
		}
	}); 
});
</script>
</body>
</html>