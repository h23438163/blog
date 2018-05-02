<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 23:07
 */

namespace app\index\controller;

use think\Controller;
use app\index\model\BlogArticle;
use think\Session;
use think\View;

class Index extends Controller
{

    public function showArticle($page = 1,$PageSize = 5){

        //页码需验证
        if (!is_numeric($page)) {
            $this->error('页码错误',url('index/index/showarticle?page=1'));
        }

        $blogArticle = new BlogArticle();

        $article_count = $blogArticle->count();
        $PageCount     = ceil($article_count/$PageSize);
        $PageStart     = ($page - 1) * $PageSize;
        $path = '/index/index/showarticle';
        $Navi = Navi($page,$PageCount,$path);

        $fields = 'article_id,article_title,add_date,u.username as author,article_tag1,article_tag2,article_tag3,article_img,content,comments_num';
        $article_list  = $blogArticle->alias('b')
                                     ->join('user u','u.user_id = b.user_id')
                                     ->limit($PageStart,$PageSize)
                                     ->order('add_date','DESC')
                                     ->field($fields)
                                     ->select();

        //dump($article_list);exit;
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
        return view();
    }

    public function showMessage(){

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