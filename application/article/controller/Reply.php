<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/29
 * Time: 12:06
 */

namespace app\article\controller;

use app\article\model\CommentsReply;
use app\index\model\BlogArticle;
use think\Controller;
use think\Session;

class Reply extends Controller
{
    public function addReply(){

        $blogArticle   = new BlogArticle();
        $commentsReply = new CommentsReply();

        $data          = $this->request->param('','','htmlspecialchars');
        $commentsNum   = $blogArticle->where('article_id',$data['article_id'])->value('comments_num');
        $url           = url('article/article/article?articleId='.$data['article_id']);

        $validate      = $this->validate($data,'AddReply');

        if ($validate !== true) {
            $this->error($validate,$url);
        }

        $data['user_id'] = Session::get('userId', 'user');
        $saveRes = $commentsReply->allowField(true)->save($data);
        if ($saveRes > 0) {
            $blogArticle->where('article_id',$data['article_id'])->update(['comments_num' => ++$commentsNum]);
            $this->success('回复成功',$url);
        } else {
            $this->error('回复失败',$url);
        }


    }

    public function editReply($replyId) {
        echo 'reply';
        echo $replyId;
    }
}