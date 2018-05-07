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
use think\Session;

class Thumb extends Controller
{
    public function _initialize()
    {
        if (!Session::has('userId','user')) {

            exit('false');

        }
    }

    public function thumb(){

        $data     = $this->request->param('','','htmlspecialchars');
        $message  = Message::get($data['message_id']);
        $userId   = Session::get('userId','user');
        $data['user_id'] = $userId;
        $messageThumb    = new MessageThumb();
        $thumbNum        = $message->good_and_bad;

        $thumbRes = $messageThumb->where('user_id', '=', $userId)
                                    ->where('message_id', '=', $data['message_id'])
                                    ->find();

        if ($thumbRes === null) {
            switch ($data['thumb']) {
                case 'good':
                    $message->good_and_bad = ++$thumbNum;
                    break;
                case 'bad':
                    $message->good_and_bad = --$thumbNum;
                    break;
            }
            if ($message->save() > 0) {
                $messageThumb->save($data);
            }
        } elseif (is_object($thumbRes)) {
            $thumbRes = $thumbRes->toArray();
            if ($data['thumb'] === $thumbRes['thumb']) {
               switch ($data['thumb']) {
                   case 'good':
                       $message->good_and_bad = --$thumbNum;
                       break;
                   case 'bad':
                       $message->good_and_bad = ++$thumbNum;
                       break;
               }
               $messageThumb::destroy($thumbRes['id']);
            } elseif ($data['thumb'] !== $thumbRes['thumb']){
                switch ($data['thumb']) {
                    case 'good':
                        $message->good_and_bad = $thumbNum+2;
                        break;
                    case 'bad':
                        $message->good_and_bad = $thumbNum-2;
                        break;
                }
                $messageThumb->save($data,['id' => $thumbRes['id']]);
            }
            $message->save();
        }

        return $message->good_and_bad;
    }
}