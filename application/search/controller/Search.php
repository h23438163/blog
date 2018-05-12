<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/9
 * Time: 11:25
 */

namespace app\search\controller;


use app\index\model\BlogArticle;
use app\user\model\User;
use think\Controller;

class Search extends Controller
{
    public function searchTag ($PageSize = 5, $tag = '') {

        if (empty($page)) {
            $page = $this->request->get('page', '1', 'htmlspecialchars');
        }

        //页码验证
        if (!is_numeric($page) || $page < 1) {
            $this->error('页码错误',url('search/search/searchtag','page=1&tag='.$tag));
        }

        $blogArticle = new BlogArticle();
        //分页处理
        $article_count = $blogArticle->whereLike('concat(article_tag1,article_tag2,article_tag3)', '%'.$tag.'%')->count();

        if ($article_count == 0) {
            return $this->fetch();
        }

        $PageCount     = ceil($article_count/$PageSize);

        if ($page > $PageCount) {
            $this->error('页码错误',url('search/search/searchtag','page=1&tag='.$tag));
        }

        $PageStart     = ($page - 1) * $PageSize;
        //分页函数
        $Navi = Navi($page,$PageCount,'search/search/searchtag','&tag='.$tag);

        //查询字段
        $fields = 'article_id,article_title,add_date,u.username as author,b.user_id,article_tag1,article_tag2,article_tag3,article_img,content,comments_num';
        $article_list  = $blogArticle->alias('b') //别名
                                     ->join('user u','u.user_id = b.user_id') //内连接
                                     ->limit($PageStart,$PageSize)
                                     ->order('add_date','DESC')
                                     ->field($fields) //设置字段
                                     ->whereLike('concat(article_tag1,article_tag2,article_tag3)', '%'.$tag.'%')
                                     ->select();
        //dump($article_list);exit;

        $this->assign('tag',$tag);
        $this->assign('navi',$Navi);
        $this->assign('pagenow',$page);
        $this->assign('pagecount',$PageCount);
        $this->assign('article_list',$article_list);

        return $this->fetch();
    }

    public function searchAuthor ($PageSize = 5, $userId = '') {

        if (empty($page)) {
            $page = $this->request->get('page', '1', 'htmlspecialchars');
        }

        //用户ID类型验证
        if (!is_numeric($userId)) {
            $this->error('参数错误');
        }

        //页码验证
        if (!is_numeric($page) || $page < 1) {
            $this->error('页码错误',url('search/search/searchauthor','page='.$page.'&userId='.$userId));
        }

        if (empty($page)) {
            $page = $this->request->get('page', '1', 'htmlspecialchars');
        }

        $blogArticle = new BlogArticle();
        //分页处理
        $article_count = $blogArticle->where('user_id', '=', $userId)->count();

        if ($article_count == 0) {
            return $this->fetch();
        }

        $PageCount     = ceil($article_count/$PageSize);

        if ($page > $PageCount) {
            $this->error('页码错误',url('search/search/searchauthor','page=1&userId='.$userId));
        }

        $user      = new User();
        $username  = $user->where('user_id', '=', $userId)->value('username');
        $PageStart = ($page - 1) * $PageSize;
        //分页函数
        $Navi = Navi($page,$PageCount,'search/search/searchauthor','&userId='.$userId);

        //查询字段
        $fields = 'article_id,article_title,add_date,u.username as author,b.user_id,article_tag1,article_tag2,article_tag3,article_img,content,comments_num';
        $article_list  = $blogArticle->alias('b') //别名
                                     ->join('user u','u.user_id = b.user_id') //内连接
                                     ->limit($PageStart, $PageSize)
                                     ->order('add_date','DESC')
                                     ->field($fields) //设置字段
                                     ->where('b.user_id', '=', $userId)
                                     ->select();
        //dump($article_list);exit;

        $this->assign('navi',$Navi);
        $this->assign('pagenow',$page);
        $this->assign('userId',$userId);
        $this->assign('username',$username);
        $this->assign('pagecount',$PageCount);
        $this->assign('article_list',$article_list);

        return $this->fetch();
    }

    public function searchAll ($PageSize = 5, $keyword = '') {

        if (empty($keyword)) {
            $keyword = $this->request->get('keyword', '', 'htmlspecialchars');
        }

        if (empty($page)) {
            $page = $this->request->get('page', '1', 'htmlspecialchars');
        }

        //页码验证
        if (!is_numeric($page) || $page < 1) {
            $this->error('页码错误',url('search/search/searchall','page=1&keyword='.$keyword));
        }

        $blogArticle = new BlogArticle();

        $resultCount = $blogArticle->alias('b') //别名
                                   ->join('user u','u.user_id = b.user_id')
                                   ->whereLike('concat(article_title,article_tag1,article_tag2,article_tag3,content,u.username)', '%'.$keyword.'%')
                                   ->count();

        if ($resultCount == 0) {
            return $this->fetch();
        }

        $PageCount   = ceil($resultCount / $PageSize);

        if ($page > $PageCount && $page != 1) {
            $this->error('超出范围',url('search/search/searchall','page=1&keyword='.$keyword));
        }

        $PageStart     = ($page - 1) * $PageSize;
        //分页函数
        $Navi = Navi($page,$PageCount,'search/search/searchall','&keyword='.$keyword);

        $fields = 'article_id,article_title,add_date,u.username as author,b.user_id,article_tag1,article_tag2,article_tag3,article_img,content,comments_num';
        $article_list  = $blogArticle->alias('b') //别名
                                     ->join('user u','u.user_id = b.user_id') //内连接
                                     ->limit($PageStart,$PageSize)
                                     ->order('add_date','DESC')
                                     ->field($fields) //设置字段
                                     ->whereLike('concat(article_title,article_tag1,article_tag2,article_tag3,content,u.username)', '%'.$keyword.'%')
                                     ->select();

        $this->assign('keyword',$keyword);
        $this->assign('navi',$Navi);
        $this->assign('pagenow',$page);
        $this->assign('pagecount',$PageCount);
        $this->assign('article_list',$article_list);

        return $this->fetch();
    }
}