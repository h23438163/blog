<?php

namespace app\index\Controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Url;

class HelloWorld extends Controller {

	public function admin($admin = 'hehan',$hello = ''){

		echo $admin,'<br>';
		echo $hello,'<br>';

		print_r($this->request->param());
	}

	public function today($year = '2018',$month = '04'){
		
		echo '今天是'.$year.'年'.$month.'月';
	
	}

	public function url(){
		
		echo url('index/hello_world/today','year=2016&month=05',true,true);
		echo '<br>';
		echo url('index/hello_world/admintest','admintest=hehan123&hello=你好',true,true);
		echo '<br>';
		echo url('','page=1&id=1');;
	}

	
}

?>