<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/25
 * Time: 11:09
 */

namespace app\database\controller;


use think\Controller;
use app\database\model\User as UserModel;

class User extends Controller
{
    public function add()
    {
        //新增数据的方法1
        /*$user = new UserModel();
        $user->name     = '贺涵';
        $user->email    = '234386163@qq.com';
        $user->birthday = strtotime('1991-05-30');
        if($user->save()){

            return '用户新增成功';
        }else {

            return '用户新增失败';
        }*/

        //新增数据的方法2(create方法)
        $user['name']     = 'Turnover';
        $user['email']    = 'hehan234386163@163.com';
        $user['birthday'] = strtotime('1991-5-30');

        if(UserModel::create($user)){

            return '新增OK';
        }else {

            return '失败';
        }
    }

    //批量新增数据
    public function addlist()
    {
        $user = new UserModel();

        $list = [
            ['name' => 'Dreamer', 'email' => '234386163@163.com', 'birthday' => strtotime('1991-05-30')],
            ['name' => 'irony', 'email' => 'irony234386163@163.com', 'birthday' => strtotime('1991-05-30')]
        ];

        //saveAll是批量插入
        if($user->saveAll($list)){

            return '批量插入OK';
        }else {

            return '批量失败';
        }

    }

    //更新数据
    public function update(){

        //单条数据更新
        /*$user = UserModel::get(1);
        $user->name = 'yuudachi';
        $user->email = 'yuudachi@fun.com';
        $user->birthday = strtotime('2013-4-11');
        if($user->save()){

            return '更新成功';
        }else {

            return '更新失败';
        }*/

        /*$rows = $user->save(['name' => 'shigure','birthday' => strtotime('2013-05-30'),'email' => 'shigure@fun.com']);
        if ($rows > 0 ){
            return '更新成功update';
        }else {
            return 'update失败';
        }*/

        //批量更新数据
        $user = new UserModel();
        $list = [
            ['id' => 6,'name' => 'YOYO', 'email' => 'yoyo@qq.com' , 'birthday' => strtotime('1991-05-30')],
            ['id' => 7,'name' => '[]', 'email' => 'kongbai@qq.com' , 'birthday' => strtotime('1991-05-30')]
        ];
        $user->saveAll($list);
    }

    //查询
    public function select(){

        //查询一条数据
        /*$user = UserModel::get(1);
        echo $user->name;
        echo $user->email;
        echo date('Y-m-d',$user->birthday);*/

        /*$user = UserModel::get(['name'=>'Turnover']);
        echo $user->email;*/

        /*$user = new UserModel();
        $result = $user->where('name','YOYO')->find();
        echo $result->email;*/

        //获取多个数据
        /*$list = UserModel::all([1,2,3]);
        foreach ($list as $key => $val) {
            echo $val->name.'<br>';
            echo $val->email.'<br>';
            echo date('Y-m-d',$val->birthday).'<br>';
            echo '-------------------------------------------<br>';
        }*/

        /*$user = new UserModel();
        $result = $user->where('birthday',strtotime('1991-05-30'))->limit('2')->order('id','asc')->select();
        foreach ($result as $key => $val){
            echo $val['name'].'<br>';
            echo $val['email'].'<br>';
        }*/

        //聚合函数使用
        $user = new UserModel();
        //echo $user->count('id');
        echo $user->Max('birthday');


    }

    public function delete(){

        //删除一条数据
        /*$user = UserModel::get(1);
        if($user->delete()){
            return 'deleteOK';
        }else {
            return 'daleteFalse';
        }*/

        /*if(UserModel::destroy(2)){
            return '删除OK';
        }else {
            return '删除失败';
        }*/

        //删除多条数据
        if (UserModel::destroy([4,5])){

            return '删除多条数据成功';
        }else {
            return '删除多条数据失败';
        }
    }


}