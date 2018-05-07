<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 23:06
 */

namespace app\message\model;


use think\Model;

class Message extends Model
{
    protected $insert = [
        'message_id'   => null,
        'good_and_bad' => 0,
        'send_date'
    ];

    public function setSendDateAttr ($senddate) {
        return time();
    }

    public function getSendDateAttr ($senddate) {
        $date = date('Y年m月d日',$senddate);
        $time = date('g:i:s a',$senddate);
        return array('date' => $date, 'time' => $time);
    }

    public function getContentAttr ($content) {
        return htmlspecialchars($content);
    }
}