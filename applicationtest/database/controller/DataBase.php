<?php

namespace app\database\Controller;

use think\Controller;
use think\Request;
use think\Db;

class DataBase extends Controller
{
	public function insertsql(){

		//Db::execute如果执行成功返回影响的行数
		//$insertResult = Db::execute("insert into  tp_dbtest values (null,'test')");

		//print_r('影响的行数:'.$insertResult);

		//Db::query()查询操作,返回结果集
		//结果集为二维数组和mysqli的fetch_assoc一样
		$result = Db::query("select * from tp_dbtest");
		
		echo '<pre>';
		print_r($result);
		echo '</pre>';

	}

	public function dbtable(){

		//表名需要带前缀
		$table = Db::table('tp_dbtest');

		//返回受影响的行数
		//echo $table->insert(['id' => 'null','name' => 'turnover']);
		
		//可以加入where条件,同样返回受影响的行数
		//echo $table->where('name','changsha')->update(['name' => 'hehan']);

		//一次性插入多条数据
		$data = array(['name' => 'turnover'],['name' => 'Dreamer']);
		echo $table->insertAll($data);
	}

	public function dbname(){
		
		//使用Db::name查询数据库可以省略数据表名前缀,在database.php里设置
		$tablename = Db::name('dbtest');

		//取出某个条件某个字段的一条记录(返回字符串),例如取出id=1的name字段
		echo $tablename->where('id','exp','=1')->value('name');

		//取出某个范围内(where条件)某个字段的全部数据(返回数组),例如取出id>1的name字段的所有数据
		echo '<pre>';
		print_r($tablename->where('id','exp','>1')->column('name'));
		echo '</pre>';

		//统计结果集总数(count)
		echo $tablename->count(); //统计表记录
		echo '<br>';
		echo $tablename->where('name','exp','="test"')->count();
	


	}

	public function dbfind(){

		//find()方法只会获取符合条件的第一条结果(无论有多少结果集)
		//$table = Db::name('dbtest')->where('id','exp','>1')->find();
		
		//select()方法可以提取符合条件的全部结果集,field()方法可以指定字段
		//$table = Db::table('tp_dbtest')->where('id','exp','>1')->field('name')->select();
		//模糊查询
		//$table = Db::name('dbtest')->where('name','like','%te%')->select();
		//OR查询
		//$table = Db::table('tp_dbtest')->where('id','exp','>6')->whereOr('id','exp','<=2')->select();
		//BETWEEN查询
		$table = DB::name('dbtest')->where('id','between',[2,6])->select();

		echo '<pre>';
		print_r($table);

	}

	public function selectchunk(){

		$tablename = Db::name('dbtest');
		
		//chunk函数可以有效解决大型数据的查询效率问题
		//查询方式为按照设置的每次查询数分批查询,然后汇总在一个数组里返回
		$tablename->where('id','exp','>1')->chunk(/*每次查询条数*/2,/*回调函数*/function (/*每次查询的结果集*/$list){
			foreach($list as $key => $val){

				foreach($val as $key => $val){
					
					echo $key.'=>'.$val.'<br>';;
				}
			}
		});
	}

}

?>