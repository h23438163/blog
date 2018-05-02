<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/25
 * Time: 14:23
 */

namespace app\database\controller;

use think\Db;
use think\Controller;
define('APP_PA','这是常量的值');

class View extends Controller
{
    public function index (){
        /*$name  = '今天天气不错';
        $email = 'hehan234386163@163.com';
        $this->assign('name',$name);
        $this->assign('email',$email);*/
        //return $this->fetch();

        $name = 'hello world';
        $time = time();
        $this->assign('name',$name);
        $this->assign('time',$time);
        return $this->fetch();
    }

    public function extend(){

        return $this->fetch();
    }

    public function listRes(){

        $table  = Db::table('tp_user');
        $result = $table->select();
        $this->assign('result',$result);
        return $this->fetch();

    }
}