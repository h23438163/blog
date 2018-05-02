<?php

namespace app\admintest\Controller;

use think\Controller;
use think\Db;
use think\Request;
use think\View;
use think\Url;
use app\admintest\model\User;

class Users extends Controller
{

	public function create(){

		return view();
	}

	public function add(){
		
		//实例化User模型
		$users = new User();

		$data = input('post.');
									    /*验证器名称*/
		$result = $this->validate($data,'User');

		if($result === true){
		
			$data['password'] = md5($data['password']);

		}else {

			exit($result);
		}

		if($users->allowField(true)->save($data)){
			return 'ok';
		}else {
			return $users->getError();
		}

		/* 单独验证某个提交的信息(例如username)
		$check = Validate::is($data['username'],'require');
		if($check === false){
			return '错误信息';
		}
		
		if($users->allowField(true)->save($data)){
			return 'ok';
		}else {
			return $users->getError();
		}

		*/
		
		/*if($users->allowField(true)->validate(true)->save($data)){
			return 'ok';
		}else {
			return $users->getError();
		}*/
	}

	public function setAttr(){

	    $user = new User();

	    $user->birthday = '1991-05-30';
	    $user->name     = 'hehan';
	    $user->email    = '234386163@163.com';
	    if($user->save()){
	        echo '插入成功';
        }


    }

    public function getAttr(){

	    $user = User::get(11);

	    dump($user->birthday);
    }

    public function auto(){

	    $user = new User();

        $user->name     = 'hehan';
        $user->email    = '234386163@163.com';
        if($user->save()){
            echo '插入成功';
        }
    }
}

?>