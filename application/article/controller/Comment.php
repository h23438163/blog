<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/29
 * Time: 12:00
 */

namespace app\article\controller;


use app\article\model\ArticleComments;
use app\index\model\BlogArticle;
use think\Controller;
use think\Session;

class Comment extends Controller
{

    public function addComment(){

        $blogArticle     = new BlogArticle();
        $articleComments = new ArticleComments();

        $data        = $this->request->param('','','htmlspecialchars');
        $url         = url('article/article/article?articleId=' . $data['article_id']);
        //文章评论数
        $commentsNum = $blogArticle->where('article_id', $data['article_id'])->value('comments_num');

        //验证器
        $validate = $this->validate($data ,'AddComment');
        if ($validate !== true) {
            $this->error($validate, $url);
        }
        //Session
        $data['user_id'] = Session::get('userId','user');
        //过滤保存返回受影响的行数
        $affected_rows = $articleComments->allowField(true)->save($data);

        if ($affected_rows > 0) {
            //评论数+1
            $blogArticle->where('article_id', $data['article_id'])->update(['comments_num' => ++$commentsNum]);
            $this->success('评论成功',$url);
        } else {
            $this->error('评论失败',$url);
        }
    }

    //编辑评论内容
    public function editComment($commentId = '') {

        $comment = ArticleComments::get($commentId);

        if ($comment === null) {
            $this->error('评论不存在');
        }

        $this->assign('comments_content', $comment->comments_content);
        $this->assign('comment_id', $commentId);
        
        return $this->fetch();
    }

    //更新评论内容
    public function updateComment() {

        $data = $this->request->param('','','htmlspecialchars');
        //验证器
        $validate = $this->validate($data,'UpdateComment');
        if ($validate !== true) {
           $this->error($validate);
        }

        //获取结果集
        $articleComment = ArticleComments::get($data['comment_id']);
        if ($articleComment === null) {
           $this->error('修改失败');
        }

        //更新数据
        $articleComment->comments_content = $data['comments_content'];
        $articleId     = $articleComment->article_id;
        //返回受影响的行数
        $affected_rows = $articleComment->allowField(true)->save();

        if ($affected_rows > 0) {
           $this->success('修改成功',url('article/article/article?articleId='.$articleId));
        } else {
           $this->error('修改失败');
        }
    }

}