<?php

namespace app\database\Controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Rul;
use app\database\model\DbTest;

class TestModel extends Controller
{
	public function testModel(){

		$dbtest = DbTest::get(1);
	
		echo '<pre>';
		print_r($dbtest);
	}

	public function setvalue(){
		
		//通过model插入数据
		$dbtest = new DbTest();
//		
//		//设置需要插入的(字段名)
//		$dbtest->id    = '20';
//		$dbtest->name  = 'dreamer';
//		//如果字段不存在会报错
//		$dbtest->aaaaa = 'dsddaa';
//		//保存
//		$dbtest->save();

		//方式二
		//设置需要插入的数组
		//$data = array('id' => '33','name' => 'ddasdas');
		
		//使用静态方法create插入数据,并返回插入数据的结果对象
		//if($result = $dbtest::create($data)){
			
			//echo 'id:'.$result->id.'name:'.$result->name;
		//}

		//批量插入
		$list = array(
			array('id' => '35','name' => 'name35'),
			array('id' => '36','name' => 'name36'),
			
		);
		
		//返回bool值
		if($dbtest->saveAll($list)){
			echo '批量插入成功';

		}

		
	}

	public function read(){

		$get_obj = DbTest::get(1);

		echo $get_obj->id;
	}

	public function scope(){
		$list = DbTest::scope();
	}
}

?>