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
    public static function check($code, $id) {
        $captcha = new \think\captcha\Captcha();
        if ($captcha->check($code, $id)) {
            return 1;
        } else {
            return 0;
        }
    }
}