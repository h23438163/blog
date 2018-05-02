<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/27
 * Time: 13:58
 */

namespace app\article\controller;

use app\article\model\ArticleComments;
use app\article\model\CommentsReply;
use app\index\model\BlogArticle;
use think\Controller;
use think\Session;

class Article extends Controller
{

    public function addArticle(){

        if (!Session::has('username','user')) {
            $this->error('请登陆',url('index/index/login'));
        }

        $url_add  = url('index/index/addarticle');
        $data     = $this->request->param('','','htmlspecialchars');
        $validate = $this->validate($data,'AddArticle');

        if ($validate !== true) {
            $this->error($validate,$url_add);
        }

        $img = $this->request->file('img_upload');

        if ($img) {
            $fileValidate = $img->validate(['size' => 500000,'ext' => 'gif,jpg,jpeg,bmp,png'])->check();

            if ($fileValidate === false) {
                $this->error('图片上传错误',$url_add);
            }

            $file = $img->move(ROOT_PATH . 'public' . DS . '/static/images/upload','',false);

            //判断上传的图片是否存在
            if ($file !== false) {
                $data['article_img'] = '/images/upload/'.$file->getSaveName();
            } else {
                $img_array = $img->getInfo();
                $img_name  = $img_array['name'];
                $data['article_img'] = '/images/upload/'.$img_name;
            }
        } elseif ($img === null) {
            $data['article_img'] = '/images/img'.rand(1,2).'.jpg';
        }

        $data['article_img'] = iconv('gbk','utf-8',$data['article_img']);
        $data['user_id']     = Session::get('userId', 'user');
        $blogArticle         = new BlogArticle();
        $saveRes             = $blogArticle->allowField(true)->save($data);

        if ($saveRes > 0) {
           $this->success('添加成功', url('index/index/showarticle'));
        } else {
            $this->error('信息填写错误', $url_add);
        }
    }

    public function article($articleId = 1){

        //articleId需验证
        if (!is_numeric($articleId)) {
            $this->error('文章ID错误','index/index/showarticle');
        }

        $blogArticle = new BlogArticle();

        $fields   = 'article_id,article_title,add_date,u.username as author,article_tag1,article_tag2,article_tag3,article_img,content,b.user_id';
        $article  = $blogArticle->alias('b')
                                ->join('user u', 'u.user_id = b.user_id')
                                ->where('article_id', $articleId)
                                ->field($fields)
                                ->find();

        if (is_object($article)) {
            $article = $article->toArray();
        } else {
            return $this->fetch();
        }

        $articleComments = new ArticleComments();
        $commentsReply   = new CommentsReply();

        $comment_list = $articleComments->alias('c')
                                        ->join('user u','c.user_id = u.user_id')
                                        ->where('article_id',$articleId)
                                        ->order('comment_id')
                                        ->field('c.*,u.username as comment_name')
                                        ->select()
                                        ->toArray();

        if (!empty($comment_list)) {
            for ($i=0;$i<count($comment_list);$i++) {
                $comments_id[] = $comment_list[$i]['comment_id'];
            }
            $reply_list = $commentsReply->alias('r')
                                        ->join('user u','r.user_id = u.user_id')
                                        ->where('comment_id','in' ,$comments_id)
                                        ->field('r.*, u.username as reply_name')
                                        ->order('reply_date')
                                        ->select();
            //dump($reply_list);exit;
            $this->assign('reply_list',$reply_list);
        }

        $this->assign('article',$article);
        $this->assign('comments_list',$comment_list);

        return $this->fetch();
    }

    public function editArticle($userId) {
        echo 'article';
        echo $userId;
    }


}