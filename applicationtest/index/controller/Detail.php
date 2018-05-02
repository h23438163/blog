<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 13:26
 */

namespace app\index\controller;


use think\Controller;

class Detail extends Controller
{
    public function getInfo(){
        dump('getInfo');
    }
    public function postInfo(){
        dump('postInfo');
    }
    public function putInfo(){
        dump('putInfo');
    }
    public function deleteInfo(){
        dump('deleteInfo');
    }
}