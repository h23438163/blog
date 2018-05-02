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
        $commentsNum = $blogArticle->where('article_id', $data['article_id'])->value('comments_num');

        $validate = $this->validate($data ,'AddComment');

        if ($validate !== true) {
            $this->error($validate, $url);
        }
        $data['user_id'] = Session::get('userId','user');
        $saveRes = $articleComments->allowField(true)->save($data);

        if ($saveRes > 0) {
            $blogArticle->where('article_id', $data['article_id'])->update(['comments_num' => ++$commentsNum]);
            $this->success('评论成功',$url);
        } else {
            $this->error('评论失败',$url);
        }
    }

    public function editComment($commentId = '') {

        $comment = ArticleComments::get($commentId);

        if ($comment === null) {
            $this->error('评论不存在');
        }

        $this->assign('comments_content', $comment->comments_content);
        $this->assign('comment_id', $commentId);
        
        return $this->fetch();
    }

    public function updateComment() {

       $data = $this->request->param('','','htmlspecialchars');

       $validate = $this->validate($data,'UpdateComment');
       if ($validate !== true) {
           $this->error($validate);
       }

       $articleComment = ArticleComments::get($data['comment_id']);
       if ($articleComment === null) {
           $this->error('修改失败');
       }

       $articleComment->comments_content = $data['comments_content'];
       $articleId = $articleComment->article_id;
       $res       = $articleComment->allowField(true)->save();

       if ($res > 0) {
           $this->success('修改成功',url('article/article/article?articleId='.$articleId));
       } else {
           $this->error('修改失败');
       }
    }

}