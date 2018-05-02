<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

class Index extends Controller
{
    public function index()
    {	
		//调用/index(控制器文件)/index类默认方法是index(),所以等同于/index(控制器文件)/index(类)/index(方法名)
		return '成功运行TP5';
	
    }

	public function admin($admin = '贺涵'){
		//如果需要调用Index下的admin方法要这样访问:/index(控制器文件)/index(类)/admintest(方法名)
		//如果需要传递参数例如$admintest,则需要这样传递:/index(控制器文件)/index(类)/admintest(方法名)/admintest(参数名)/贺涵(参数值)
		print_r($this->request->param());
		exit;
	}

	public function dbtest(){
	
		$data = Db::name('dbtest')->find();
		$this->assign('data',$data['name']);
		//$this->assign('data',$name);
		//print_r($data);
		return $this->fetch('index');

	}
}
