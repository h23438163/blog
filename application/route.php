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
	//如需更多设置,参考使用手册                                                                    /*这里可以对参数进行匹配*/
	'hehan/[:admintest]' => ['index/hello_world/admintest',['method' => 'get', 'ext' => 'html'],['admintest' => '\w+']],

	'today/:year/:month'  => ['index/hello_world/today', ['method' => 'get'], ['year'  => '\d{4}', 'month' => '\d{2}']],

    //index
    'index/[:page]'    => ['index/index/showarticle', ['method' => 'get'],['page' => '\d+']],
	'loginPage'        => ['index/index/login',       ['method' => 'get']],
	'registerPage'     => ['index/index/register',    ['method' => 'get']],
	'message/[:page]'  => ['index/index/showmessage', ['method' => 'get'],['page' => '\d+']],
	'about'            => ['index/index/showabout',   ['method' => 'get']],
	'addarticle' => ['index/index/addarticle', ['method' => 'get']],
	'addmessage' => ['index/index/addmessage', ['method' => 'get']],
	'download'   => ['index/index/download', ['method' => 'get']],

    //article
    'insertarticle' => ['article/article/addarticle',    ['method' => 'post']],
    'updatearticle' => ['article/article/updatearticle', ['method' => 'post']],
    'article/:articleId'     => ['article/article/article',     ['method' => 'get'],['articleId' => '\d+']],
    'editarticle/:articleId' => ['article/article/editarticle', ['method' => 'get'],['articleId' => '\d+']],
    //comment
    'addcomment'    => ['article/comment/addcomment',    ['method' => 'post']],
    'updatecomment' => ['article/comment/updatecomment', ['method' => 'post']],
    'editcomment/:commentId' => ['article/comment/editcomment', ['method' => 'get'],['commentId' => '\d+']],
    //reply
    'addreply'    => ['article/reply/addreply',    ['method' => 'post']],
    'updatereply' => ['article/reply/updatereply', ['method' => 'post']],
    'editreply/:replyId' => ['article/reply/editreply', ['method' => 'get'],['replyId' => '\d+']],
    //message
    'addmessage'    => ['message/message/addmessage',    ['method' => 'get']],
    'updatemessage' => ['message/message/updatemessage', ['method' => 'post']],
    'editmessage/:messageId/:pageNow' => ['message/message/editmessage', ['method' => 'get'],['messageId' => '\d+' ,'pageNow' => '\d+']],
    //user
    'userInfo' => ['user/user/userinfo', ['method' => 'get']],
    'login'    => ['user/user/login',    ['method' => 'post']],
    'register' => ['user/user/register', ['method' => 'post']],
    'remindlist/[:page]'  => ['user/user/remindlist',  ['method' => 'get'],['page' => '\d+']],
    'articleist/[:page]'  => ['user/user/articlelist', ['method' => 'get'],['page' => '\d+']],
    'commentlist/[:page]' => ['user/user/commentlist', ['method' => 'get'],['page' => '\d+']],
    'replyist/[:page]'    => ['user/user/replylist',   ['method' => 'get'],['page' => '\d+']],
    'messagelist/[:page]' => ['user/user/messagelist', ['method' => 'get'],['page' => '\d+']],
    'favorite/[:page]'  => ['user/user/favorite', ['method' => 'get'],['page' => '\d+']],
    'history/[:page]'   => ['user/user/history',  ['method' => 'get'],['page' => '\d+']],
    'hasemail'    => ['user/user/hasemail',    ['method' => 'post', 'ajax' => true],['email'    => '\w+']],
    'hasusername' => ['user/user/hasusername', ['method' => 'post', 'ajax' => true],['username' => '\w+']],
    //favorite
    'addfavorite/:articleId'  => ['favorite/favorite/addfavorite', ['method' => 'get', 'ajax' => true],['articleId' => '\d+']],
    'isfavorite/:articleId'   => ['favorite/favorite/isfavorite',  ['method' => 'get', 'ajax' => true],['articleId' => '\d+']],
    //captcha
    'check' => ['captcha/captcha/check',  ['method' => 'post', 'ajax' => true],['code' => '\d+']],
    //remind
    'isremind/:id' => ['remind/remind/isremind',     ['method' => 'get'],['id' => '\d+']],
    'allisremind'  => ['remind/remind/allisremind',  ['method' => 'get']],
    //search
    'searchtag/:tag/[:page]'       => ['search/search/searchtag',    ['method' => 'get'],['page' => '\d+', 'tag' => '\w+']],
    'searchauthor/:userId/[:page]' => ['search/search/searchauthor', ['method' => 'get'],['page' => '\d+', 'userId' => '\d+']],
    'searchall' => ['search/search/searchall', ['method' => 'get']],


];
