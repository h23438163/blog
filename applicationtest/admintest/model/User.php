<?php

namespace app\admintest\model;
use think\Model;

class User extends Model
{
    protected $auto = [

        'birthday'
    ];

    //修改器
	public function setNameAttr($name){

	    return $name.'修改完成';
    }

    public function setBirthdayAttr($birthday){

	    return time();
    }

    //获取器
    public function getBirthdayAttr($birthday){

	    return date('Y-m-d',$birthday);
    }
}

?>