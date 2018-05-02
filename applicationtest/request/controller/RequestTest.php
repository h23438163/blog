<?php

namespace app\request\Controller;

use think\Controller;
use think\Request;
use think\Db;

class RequestTest extends Controller
{
	public function test(){

		//测试url为http://tp5.com/request/request_test/test.html?php=7
		
		//实例化Request对象,如果继承Controller类,则不需要实例化直接即可使用($this->request)
		//$request = Request::instance();
		
		//输出当前url
		echo $this->request->url().'<br>';
		
		//bind方法绑定的参数可以跨控制器使用
		$this->request->bind('user_name','张三');
		
		//可以直接通过这种方式使用
		echo $this->request->user_name.'<br>';
		
		//这个函数可以全局使用(助手函数)
		echo request()->url().'<br>';

		//输出绑定的指定参数(如果有)
		print_r('$this->request->param(php)方法='.$this->request->param('php').'<br>');

		//input()助手函数等价于上面的方法
		print_r('input(php)助手函数='.input('php').'<br>');

		//request->param()方法可以设置参数的默认值(更多使用情况可以查阅手册)
		//如果没有传递email参数,则默认为234386163@qq.com
		print_r('email默认值='.$this->request->param(/*参数名*/'email',/*默认值*/ '234386163@QQ.COM',/*过滤函数名*/'strtolower'));

		echo '<br><br>=================输出get方式提交的参数===============<br>';
		
		//还有类似的方法post(),cookie()
		print_r($this->request->get());

		echo '<br><br>=================输出input方式提交的参数===============<br>';

		//助手函数input(),可以使用get.参数名获取参数值,类似的方法有input('post.email')
		print_r(input('get.email'));

		echo '<br><br>=================输出当前方模块/控制器方法信息===============<br>';

		echo '当前模块名称:'.$this->request->module();
		echo '<br>当前控制器(类名)名称:'.$this->request->controller();
		echo '<br>当前调用的方法名称:'.$this->request->action();

		echo '<br><br>=================request 其他参数===============<br>';
		
		echo '请求方法:'.$this->request->method();
		echo '<br>访问的IP地址:'.$this->request->ip();
		echo '<br>是否使用AJAX:'.($this->request->isAjax() ? 'YES' : 'NO');
		echo '<br>当前域名:'.$this->request->domain();
		echo '<br>当前入口文件:'.$this->request->baseFile();
		echo '<br>包含域名的完整URL地址:'.$this->request->url(true);
		echo '<br>URL地址的参数信息:'.$this->request->query();
		echo '<br>当前URL地址,不含QUERY_STRING:'.$this->request->baseUrl();
		echo '<br>URL地址中的pathinfo信息:'.$this->request->pathinfo();
		echo '<br>URL地址中的后缀信息:'.$this->request->ext();
	}
}

?>