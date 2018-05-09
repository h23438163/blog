<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/9
 * Time: 11:25
 */

namespace app\search\controller;


use app\index\model\BlogArticle;
use think\Controller;

class Search extends Controller
{
    public function searchTag ($page = 1, $PageSize = 5, $tag = '') {
        //页码验证
        if (!is_numeric($page)) {
            $this->error('页码错误',url('index/index/showarticle?page=1'));
        }

        $blogArticle = new BlogArticle();
        //分页处理
        $article_count = $blogArticle->whereLike('concat(article_tag1,article_tag2,article_tag3)', '%'.$tag.'%')->count();
        $PageCount     = ceil($article_count/$PageSize);
        $PageStart     = ($page - 1) * $PageSize;
        $path = '/search/search/searchtag';
        //分页函数
        $Navi = Navi($page,$PageCount,$path);

        //查询字段
        $fields = 'article_id,article_title,add_date,u.username as author,article_tag1,article_tag2,article_tag3,article_img,content,comments_num';
        $article_list  = $blogArticle->alias('b') //别名
                                     ->join('user u','u.user_id = b.user_id') //内连接
                                     ->limit($PageStart,$PageSize)
                                     ->order('add_date','DESC')
                                     ->field($fields) //设置字段
                                     ->whereLike('concat(article_tag1,article_tag2,article_tag3)', '%'.$tag.'%')
                                     ->select();
        //dump($article_list);exit;

        $this->assign('navi',$Navi);
        $this->assign('pagenow',$page);
        $this->assign('pagecount',$PageCount);
        $this->assign('article_list',$article_list);

        return $this->fetch('index@index/index');
    }

    public function searchAuthor ($author) {
        echo $author;
    }
}