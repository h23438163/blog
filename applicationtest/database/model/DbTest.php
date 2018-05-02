<?php

namespace app\database\model;
use think\Model;

class Dbtest extends Model
{
	//读取器
	//命名方式get+字段名+Attr
	public function getIdAttr($value,$obj){
		
		//读取器可以替换系统默认的方法来自定义代码
		echo  '这是读取器获取的ID:'.$value;

		echo '<pre>';
		print_r($obj);
		echo '</pre>';
	}

	//修改器
	//命名方式set+字段名+Attr
	public function setNameAttr ($value){
		
		//可以在插入数据库之前对数据进行处理
		
	}

	public function scopeName($query){
		
		$query->where('name','turnover');
	}
}

?>