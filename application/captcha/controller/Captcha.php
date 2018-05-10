<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/10
 * Time: 10:09
 */

namespace app\captcha\controller;


use think\Controller;

class Captcha extends Controller
{
    public static function check($code = '', $id = '', $reset = []) {

        if (empty($code)) {
            $data  = (new self())->request->param('', '', 'htmlspecialchars');
            $code  = $data['authcode'];
            $id    = $data['id'];
            $reset = ['reset' => false];
        }
        $captcha = new \think\captcha\Captcha($reset);
       if ($captcha->check($code, $id)) {
            return 1;
        } else {
            return 0;
        }

    }

}