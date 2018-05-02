<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/30
 * Time: 17:32
 */

namespace app\user\validate;


use think\Validate;

class Register extends Validate
{
    protected $rule = [
        ['username','require|max:10','用户名不能为空|用户名最大10个字'],
        ['password','require|min:6|max:14|regex:/^[a-zA-Z][\w~\^-]{6,14}$/','密码不能为空|密码长度不能少于6|密码长度不能大于14'],
        ['repassword','require|confirm:password','确认密码不能为空|2次密码不相同'],
        ['email','require|email','email不能为空|email格式不正确'],
    ];

    protected $message = [
        'password.regex'  =>  '密码只能由[a-z,A-Z,0-9]和[_,-,~,^]组成,且必须由字母开头',

    ];
}