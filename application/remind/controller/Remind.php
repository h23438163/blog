<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/6
 * Time: 13:54
 */

namespace app\remind\controller;


use app\article\model\CommentsReply;
use think\Controller;
use app\remind\model\Remind as RemindModel;
use think\Db;

class Remind extends Controller
{
    public static function insertRemind($remindType = '', $remindId = '', $articleId = '')
    {
        $remindModel = new RemindModel();
        $remindModel->remind_type = $remindType;
        $remindModel->remind_id = $remindId;
        $remindModel->article_id = $articleId;

        if ($remindType == 'reply') {
            $remind_userId = Db::query('SELECT 
                                                c.user_id 
                                            FROM 
                                                yu_comments_reply r 
                                            inner join 
                                                yu_article_comments c 
                                            ON 
                                                r.comment_id = c.comment_id 
                                            WHERE r.reply_id = ?', [$remindId]);
        } elseif ($remindType == 'comment') {
            $remind_userId = Db::query('SELECT 
                                                a.user_id 
                                            FROM 
                                                yu_article_comments c 
                                            inner join 
                                                yu_blog_article a 
                                            ON 
                                                c.article_id = a.article_id 
                                            WHERE c.comment_id = ?', [$remindId]);
        }

        $remindModel->remind_user_id  = $remind_userId[0]['user_id'];
        $remindModel->save();

    }

    public function isRemind () {

    }
}