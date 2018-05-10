<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/7
 * Time: 12:34
 */

namespace app\message\controller;

use app\captcha\controller\Captcha;
use think\Controller;
use app\message\model\Message as MessageModel;
use think\Session;

class Message extends Controller
{
    public function _initialize()
    {
        //验证是否登陆
        if (!Session::has('username','user')) {
            $this->error('请登陆',url('index/index/login'));
        }
    }

    public static function showMessage ($PageStart, $PageSize) {

        $messageMode = new MessageModel();
        $messageList = $messageMode ->alias('m')
                                    ->join('user u', 'u.user_id = m.user_id')
                                    ->field('m.*,u.username,u.head_img')
                                    ->order('send_date', 'desc')
                                    ->limit($PageStart,$PageSize)
                                    ->select();

        return $messageList;
    }

    public static function getMessageCount ($userId = '') {
        $messageModel = new MessageModel();
        if (empty($userId)) {
            $messageCount = $messageModel->count();
        } else {
            $messageCount = $messageModel->where('user_id', '=', $userId)->count();
        }
        return $messageCount;
    }

    public function addMessage () {

        $data = $this->request->param('','','htmlspecialchars');

        if (Captcha::check($data['authcode'], $this->request->action()) === 0 ) {
            $this->error('验证码错误');
        }

        $validate = $this->validate($data,'AddMessage');
        if ($validate !== true) {
            $this->error($validate,url('message/message/addmessage'));
        }

        $userId = Session::get('userId', 'user');
        $data['user_id'] = $userId;

        $messageModel  = new MessageModel();
        $affected_rows = $messageModel->allowField(true)
                                      ->save($data);

        if ($affected_rows > 0) {
            $this->success('留言成功',url('index/index/showmessage'));
        } else {
            $this->error('留言失败',url('message/message/addmessag'));
        }

    }

    public function editMessage($messageId = 1, $pageNow = 1) {

        $messageModel  = new MessageModel();
        $message = $messageModel->where('message_id','=', $messageId)
                                ->field('message_id,content')
                                ->find();
        if (is_object($message)) {
            $message = $message->toArray();
        } else {
            $this->error('留言不存在',url('index/index/showmessage'));
        }

        $this->assign('message',$message);
        $this->assign('pagenow',$pageNow);
        return $this->fetch();
    }

    public static function userMessageList ($userId, $PageStart, $PageSize) {
        $message = new MessageModel();
        $messageList = $message ->alias('m')
                                ->join('user u', 'm.user_id = u.user_id')
                                ->where('m.user_id', '=', $userId)
                                ->limit($PageStart,$PageSize)
                                ->select();
        return $messageList;
    }

    public function updateMessage(){
        
        $data = $this->request->param('','','htmlspecialchars');

        if (Captcha::check($data['authcode'], $this->request->action()) === 0 ) {
            $this->error('验证码错误');
        }

        $validate = $this->validate($data, 'UpdateMessage');
        if ($validate !== true) {
            $this->error($validate);
        }

        $message = MessageModel::get($data['message_id']);
        if ($message === null) {
            $this->error('修改错误');
        }

        if ($message->content == $data['content']) {
            $this->success('改修成功', url('index/index/showmessage','page='.$data['pagenow']));
        }

        $message->content = $data['content'];
        $affected_rows = $message->allowField(true)->save();
        if ($affected_rows > 0) {
            $this->success('改修成功', url('index/index/showmessage','page='.$data['pagenow']).'#'.$data['message_id']);
        } else {
            $this->error('修改失败');
        }

    }


}