<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/1
 * Time: 11:06
 */

namespace app\user\validate;


use think\Validate;

class Login extends Validate
{
    protected $rule = [
        ['username', 'require', '用户名不能为空'],
        ['password', 'require', '密码不能为空'],
    ];
}