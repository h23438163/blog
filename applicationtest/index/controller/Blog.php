<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 13:15
 */

namespace app\index\controller;


use think\Controller;

class Blog extends Controller
{
    public function index(){

        dump('index');
    }

    public function update($id){

        dump('update  $id');
    }

    public function delete($id){

        dump('delete $id');
    }

}