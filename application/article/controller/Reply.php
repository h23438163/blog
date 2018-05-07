<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/29
 * Time: 12:06
 */

namespace app\article\controller;

use app\article\model\ArticleComments;
use app\article\model\CommentsReply;
use app\index\model\BlogArticle;
use app\remind\controller\Remind;
use think\Controller;
use think\Session;

class Reply extends Controller
{
    public function _initialize()
    {
        //验证是否登陆
        if (!Session::has('username','user')) {
            $this->error('请登陆',url('index/index/login'));
        }
    }

    public function addReply(){

        //实例化对象
        $blogArticle   = new BlogArticle();
        $commentsReply = new CommentsReply();
        //获取提交的数据(htmlspecialchars过滤函数)
        $data          = $this->request->param('','','htmlspecialchars');
        //文章评论总数
        $commentsNum   = $blogArticle->where('article_id',$data['article_id'])->value('comments_num');
        //跳转URL
        $url           = url('article/article/article?articleId='.$data['article_id']);
        //验证结果
        $validate      = $this->validate($data,'AddReply');

        //判断验证结果
        if ($validate !== true) {
            $this->error($validate,$url);
        }

        //获取Session信息
        $data['user_id'] = Session::get('userId', 'user');
        //保存入数据库
        $affected_rows   = $commentsReply->allowField(true)->save($data);
        $replyId = $commentsReply->where('user_id', '=', $data['user_id'])->getLastInsID();

        //判断受影响的行数
        if ($affected_rows > 0) {
            //更新文章评论总数
            $blogArticle->where('article_id',$data['article_id'])->update(['comments_num' => ++$commentsNum]);
            //插入消息提醒数据
            Remind::insertRemind('reply', $replyId, $data['article_id']);
            $this->success('回复成功',$url);
        } else {
            $this->error('回复失败',$url);
        }


    }

    //编辑回复内容
    public function editReply($replyId) {

        //获取评论结果集
        $reply = CommentsReply::get($replyId);

        //判断是否为空
        if ($reply === null) {
            $this->error('回复不存在');
        }

        //传输给模板
        $this->assign('reply_content', $reply->reply_content);
        $this->assign('reply_id', $reply->reply_id);

        return $this->fetch();
    }

    //更新回复内容
    public function updateReply() {

        //获取提交的数据(htmlspecialchars过滤函数)
        $data  = $this->request->param('', '', 'htmlspecialchars');

        //验证器
        $validate = $this->validate($data, 'UpdateReply');
        if ($validate !== true) {
            $this->error($validate);
        }

        //获取回复ID对应的结果集
        $reply = CommentsReply::get($data['reply_id']);

        //判断是否为空
        if ($reply === null) {
            $this->error('修改失败');
        }

        //获取文章ID
        $articleId = ArticleComments::get([$reply->comment_id])->article_id;

        //判断内容是否一致
        if ($reply->reply_content == $data['reply_content']) {
            $this->success('修改成功',url('article/article/article?articleId='.$articleId));
        } else {
            //更新
            $reply->reply_content = $data['reply_content'];
            $affected_rows  = $reply->allowField(true)->save();
            if ($affected_rows > 0) {
                $this->success('修改成功',url('article/article/article?articleId='.$articleId));
            } else {
                $this->error('修改失败');
            }
        }
    }
}