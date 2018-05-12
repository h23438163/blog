<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/12
 * Time: 11:39
 */

namespace app\error\controller;


use think\Controller;

class Error extends Controller
{
    public function miss () {
        return $this->fetch('404');
    }
}