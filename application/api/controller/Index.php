<?php
namespace app\api\controller;

class Index
{
    public function index(){
		return json(['code'=>400,'msg'=>'接口错误','api'=>""]);
	}
}
