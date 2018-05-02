<?php

namespace app\response\Controller;

use think\Controller;
use think\Request;
use think\Db;

class ResponseTest extends Controller
{
	public function response(){
		
		$data = ['name' => 'thinkphp','status' => '1'];
		
		//直接返回数组会报错
		//return $data;
		
		//json格式返回
		//return json($data);

		//xml格式返回
		return xml($data);
		
	
	}

	public function redirecttest(){

		//$this->success('成功','response');
		//$this->error('错误','response');
		//重定向
		$this->redirect('http://yuudachi.fun');
	}
}

?>