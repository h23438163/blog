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

    //发布文章
    public function addArticle(){
        //验证是否登陆
        if (!Session::has('username','user')) {
            $this->error('请登陆',url('index/index/login'));
        }

        //跳转URL
        $url_add  = url('index/index/addarticle');
        $data     = $this->request->param('','','htmlspecialchars');
        //验证器
        $validate = $this->validate($data,'AddArticle');
        if ($validate !== true) {
            $this->error($validate,$url_add);
        }

        //图片上传处理
        $img = $this->request->file('img_upload');
        if (is_object($img)) {
            //验证图片
            $fileValidate = $img->validate(['size' => 500000,'ext' => 'gif,jpg,jpeg,bmp,png'])->check();

            if ($fileValidate === false) {
                $this->error('图片上传错误',$url_add);
            }

            //移动保存图片
            $file = $img->move(ROOT_PATH . 'public' . DS . '/static/images/upload','',false);

            //判断上传的图片是否存在
            //设置图片路径
            if ($file !== false) {
                $data['article_img'] = '/images/upload/'.$file->getSaveName();
            } else {
                //图片信息
                $img_array = $img->getInfo();
                $img_name  = $img_array['name'];
                $data['article_img'] = '/images/upload/'.$img_name;
            }

            //处理中文名
            $data['article_img'] = iconv('GB18030','utf-8',$data['article_img']);
        } elseif ($img === null) {
            //如果没有上传,设置默认图片
            $data['article_img'] = '/images/img'.rand(1,2).'.jpg';
        }

        //设置Session
        $data['user_id']     = Session::get('userId', 'user');
        //实例化模型
        $blogArticle   = new BlogArticle();
        //返回受影响的行数
        $affected_rows = $blogArticle->allowField(true)->save($data);

        if ($affected_rows > 0) {
           $this->success('添加成功', url('index/index/showarticle'));
        } else {
            $this->error('信息填写错误', $url_add);
        }
    }

    //显示评论
    public function article($articleId = 1){

        //验证ID
        if (!is_numeric($articleId)) {
            $this->error('文章ID错误','index/index/showarticle');
        }

        $blogArticle = new BlogArticle();
        //字段
        $fields   = 'article_id,article_title,add_date,u.username as author,article_tag1,article_tag2,article_tag3,article_img,content,b.user_id';
        $article  = $blogArticle->alias('b') //表别名
                                ->join('user u', 'u.user_id = b.user_id') //内连接
                                ->where('article_id', $articleId)
                                ->field($fields) //设置查询字段
                                ->find(); //查询一条

        //判断返回类型
        if (is_object($article)) {
            $article = $article->toArray();
        } else {
            return $this->fetch();
        }

        //实例化
        $articleComments = new ArticleComments();
        $commentsReply   = new CommentsReply();

        $comment_list = $articleComments->alias('c') //表别名
                                        ->join('user u','c.user_id = u.user_id') //内连接
                                        ->where('article_id',$articleId)
                                        ->order('comment_id')
                                        ->field('c.*,u.username as comment_name') //设置查询字段
                                        ->select()
                                        ->toArray(); //返回数组

        if (!empty($comment_list)) {
            //将comment_id保存为数组
            $count = count($comment_list);

            for ($i = 0; $i < $count; $i++) {
                $comments_id[] = $comment_list[$i]['comment_id'];
            }

            $reply_list = $commentsReply->alias('r') //表别名
                                        ->join('user u','r.user_id = u.user_id') //内连接
                                        ->where('comment_id','in' ,$comments_id)
                                        ->field('r.*, u.username as reply_name') //设置查询字段
                                        ->order('reply_date')
                                        ->select();
            $this->assign('reply_list',$reply_list);
        }

        $this->assign('article',$article);
        $this->assign('comments_list',$comment_list);

        return $this->fetch();
    }

    //编辑文章
    public function editArticle($articleId) {

        //判断是否登陆
        if (Session::has('username','user') === false) {
            $this->error('请登陆',url('index/index/login'));
        }

        if (!is_numeric($articleId)) {
            $this->error('输入错误');
        }

       $blogArticle = new BlogArticle();

       $article = $blogArticle->where('article_id', '=', $articleId)
                              ->field('article_id,article_title,article_tag1,article_tag2,article_tag3,content')
                              ->find();

       if (is_object($article)) {
           $this->assign('article', $article);
       } else {
           $this->error('文章未找到');
       }

       return $this->fetch();
    }

    //更新文章
    public function updateArticle() {

        //判断是否登陆
        if (Session::has('username','user') === false) {
            $this->error('请登陆',url('index/index/login'));
        }

        $data = $this->request->param('','','htmlspecialchars');

        $validate = $this->validate($data,'UpdateArticle');
        if ($validate !== true) {
            $this->error($data);
        }

        //上传图片处理
        $head_img = $this->request->file('img_upload');
        if (is_object($head_img)) {
            //验证图片
            $fileValidate = $head_img->validate(['size' => 500000,'ext' => 'gif,jpg,jpeg,bmp,png'])->check();

            if ($fileValidate === false) {
                $this->error('图片上传错误');
            }

            //移动保存图片
            $file = $head_img->move(ROOT_PATH . 'public' . DS . '/static/images/upload','',false);

            //判断上传的图片是否存在
            //设置图片路径
            if ($file !== false) {
                $data['article_img'] = '/images/upload/'.$file->getSaveName();
            } else {
                //图片信息
                $img_array = $head_img->getInfo();
                $img_name  = $img_array['name'];
                $data['article_img'] = '/images/upload/'.$img_name;
            }

            //处理中文名
            $data['article_img'] = iconv('GB18030','utf-8',$data['article_img']);
            //字段有图片地址
            $field = 'article_title,article_tag1,article_tag2,article_tag3,article_img,content';
        } elseif ($head_img === null) {
            //字段无图片地址
            $field = 'article_title,article_tag1,article_tag2,article_tag3,content';
        }

        $articleId   = $data['article_id'];
        unset($data['article_id']);
        $blogArticle = new BlogArticle();
        $article     = $blogArticle->where('article_id', '=', $articleId)
                                   ->field($field)
                                   ->find();
        //验证结果集
        if (is_object($article)) {
            $article = $article->toArray();
        } else {
            $this->error('文章未找到');
        }

        //对比数据是否一致
        $isUpdate = false;
        foreach ($article as $key => $val) {
            if ($article[$key] !== $data[$key]) {
                $isUpdate = true;
            }
        }

        //dump($data);exit;
        if ($isUpdate === true) {
            //数据有修改
            $affected_rows = $blogArticle->allowField(true)->save($data,['article_id' => $articleId]);
            if ($affected_rows > 0) {
                $this->success('修改文章成功',url('article/article/article?articleId='.$articleId));
            } else {
                $this->error('修改文章失败');
            }
        } else {
            //数据有修改
            $this->success('修改文章成功',url('article/article/article?articleId='.$articleId));
        }

    }


}