<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/7
 * Time: 14:21
 */

namespace app\message\controller;


use app\message\model\MessageThumb;
use \app\message\model\Message;
use think\Controller;

class Thumb extends Controller
{
    public function thumb(){

        $data     = $this->request->param('','','htmlspecialchars');
        $thumb    = Message::get($data['message_id']);
        $thumbNum = $thumb->good_and_bad;

        switch ($data['type']) {
            case 'good':
                $thumb->good_and_bad = ++$thumbNum;
                $thumb->save();
                break;
            case 'bad':
                $thumb->good_and_bad = --$thumbNum;
                $thumb->save();
                break;
        }

        return $thumb->good_and_bad;
    }
}