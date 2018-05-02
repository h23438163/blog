<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
//资源路由
Route::resource('blog','index/blog');
//快捷路由
Route::controller('detail','index/Detail');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
	//使用方法:使用自定义名称对控制器的长url进行替换例如:定义'hehan'来替换控制器的长url'index/hello_world/admintest'
	//[]是可选变量,[]内的:admin表示传递的参数名为admin,如需多参数设置,参考上面的配置
	//method表示参数传递的方法,可以为POST,GET等
	//ext表示文件的扩展名
	//如需更多设置,参考使用手册
	'hehan/[:admintest]' => ['index/hello_world/admintest',['method' => 'get', 'ext' => 'html'],/*这里可以对参数进行匹配*/['admintest' => '\w+']],

	'today/:year/:month'  => ['index/hello_world/today', ['method' => 'get'], ['year'  => '\d{4}', 'month' => '\d{2}']],

];
