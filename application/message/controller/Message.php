<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/7
 * Time: 12:34
 */

namespace app\message\controller;


use think\Controller;
use app\message\model\Message as MessageModel;

class Message extends Controller
{
    public static function showMessage ($PageStart, $PageSize) {
        $messageMode = new MessageModel();

        $messageList = $messageMode ->alias('m')
                                    ->join('user u', 'u.user_id = m.user_id')
                                    ->field('m.*,u.username,u.head_img')
                                    ->limit($PageStart,$PageSize)
                                    ->select();

        return $messageList;
    }

    public static function getMessageCount () {
        $messageModel = new MessageModel();
        $messageCount = $messageModel->count();

        return $messageCount;
    }

    public static function getMessageThumb($messageId) {
        $message = MessageModel::get($messageId);
        $thumbNum = $message->good_and_bad;
        return $thumbNum;
    }


}