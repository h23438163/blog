<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 23:07
 */

namespace app\index\controller;

use app\message\controller\Message;
use think\Controller;
use app\index\model\BlogArticle;
use think\Session;
use think\View;

class Index extends Controller
{

    public function showArticle($page = 1,$PageSize = 5){

        //页码验证
        if (!is_numeric($page) || $page < 1) {
            $this->error('页码错误',url('index/index/showarticle?page=1'));
        }

        $blogArticle = new BlogArticle();
        //分页处理
        $article_count = $blogArticle->count();
        $PageCount     = ceil($article_count/$PageSize);

        if ($page > $PageCount) {
            $this->error('页码错误',url('index/index/showarticle?page=1'));
        }

        $PageStart     = ($page - 1) * $PageSize;
        $path = '/index/index/showarticle';
        //分页函数
        $Navi = Navi($page,$PageCount,$path);

        //查询字段
        $fields = 'article_id,article_title,add_date,u.username as author,b.user_id,article_tag1,article_tag2,article_tag3,article_img,content,comments_num';
        $article_list  = $blogArticle->alias('b') //别名
                                     ->join('user u','u.user_id = b.user_id') //内连接
                                     ->limit($PageStart,$PageSize)
                                     ->order('add_date','DESC')
                                     ->field($fields) //设置字段
                                     ->select();

        $this->assign('navi',$Navi);
        $this->assign('pagenow',$page);
        $this->assign('pagecount',$PageCount);
        $this->assign('article_list',$article_list);

        return $this->fetch('index');
    }

    public function addArticle(){
        if (!Session::has('username','user')) {
            $this->error('请登陆',url('index/index/login'));
        }
        return View();
    }

    public function showMessage($page = 1, $PageSize = 5){

        //页码验证
        if (!is_numeric($page) || $page < 1) {
            $this->error('页码错误',url('index/index/showmessage?page=1'));
        }

        $PageStart   = ($page - 1) * $PageSize;
        $MessageList  = Message::showMessage($PageStart, $PageSize);
        $MessageCount = Message::getMessageCount();
        $PageCount    = ceil($MessageCount / $PageSize);

        if ($page > $PageCount) {
            $this->error('页码错误',url('index/index/showmessage?page=1'));
        }

        $Navi = Navi($page, $PageCount, 'index/index/showmessage');

        $this->assign('Navi', $Navi);
        $this->assign('pagenow',$page);
        $this->assign('pagestart',$PageStart);
        $this->assign('pagecount',$PageCount);
        $this->assign('messagelist', $MessageList);
        return $this->fetch();

    }

    public function addMessage() {
        if (!Session::has('username','user')) {
            $this->error('请登陆',url('index/index/login'));
        }
        return View();
    }

    public function showAbout(){

        return View('about');
    }

    public function login() {
        return View();
    }

    public function register() {
        return View();
    }

}