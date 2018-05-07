<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/6
 * Time: 13:54
 */

namespace app\remind\controller;

use think\Controller;
use app\remind\model\Remind as RemindModel;
use think\Db;
use think\Session;

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
        //返回整数或者false
        return $remindModel->save();


    }


    public static function showRemind($userId = '',$page = 1, $PageSize = 5) {

        $remindModel = new RemindModel();

        $PageStart   = ($page - 1) * $PageSize;
        $remindList  = $remindModel ->alias('r')
                                    ->join('blog_article a','a.article_id = r.article_id')
                                    ->field('a.article_title,r.id')
                                    ->where('remind_user_id', '=', $userId)
                                    ->where('isremind', '=', '0')
                                    ->order('id','desc')
                                    ->limit($PageStart,$PageSize)
                                    ->select();
        //dump($remindList);exit();
        return $remindList;


    }


    public static function getRemindCount($userId) {

        $remind      = new RemindModel();
        $remindCount = $remind->where('remind_user_id', '=',$userId)
                              ->where('isremind', '=', '0')
                              ->count();

        return $remindCount;
    }


    public function isRemind ($id) {

        $remind     = RemindModel::get($id);
        $articleId  = $remind->article_id;
        $remindType = $remind->remind_type;
        $remindId   = $remind->remind_id;
        $remind->isremind = 1;
        $url = url('article/article/article','articleId='.$articleId);
        $url = $url.'#'.$remindType.$remindId;
        if ($remind->save()) {
            $this->redirect($url);
        }

    }


    public function allIsRemind () {
        $userId = Session::get('userId','user');
        $remind = new RemindModel();
        $affected_rows = $remind->save(['isremind' => '1'],['remind_user_id' => $userId]);
        if ($affected_rows > 0) {
            $this->success('设置成功',url('user/user/remindlist'));
        } else {
            $this->success('没有新消息',url('user/user/remindlist'));
        }

    }
}