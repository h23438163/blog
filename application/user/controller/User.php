<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/30
 * Time: 14:59
 */

namespace app\user\controller;


use think\Controller;
use app\user\model\User as UserModel;
use think\Session;

class User extends Controller
{
    //登陆
    public function login(){

        $data        = $this->request->param('','','htmlspecialchars');
        //实例化User模型
        $user        = new UserModel();
        //判断
        $hasUsername = $this->hasUsername($data['username'], $user);
        if ($hasUsername == 0) {
            $this->error('用户名或密码错误');
        }

        //验证器
        $validate = $this->validate($data,'Login');
        if ($validate !== true) {
            $this->error($validate, url('index/index/login'));
        }

        //匹配密码
        $password = $user->where('username','=',$data['username'])->value('password');
        if (md5($data['password'])  === $password) {
            $userId    = $user->where('username', '=', $data['username'])->value('user_id');
            $loginTime = $user->where('username', '=', $data['username'])->value('login_time');
            $loginTime = date('Y年m月d日 g:i:s a',$loginTime);
            //设置Session
            Session::set('username', $data['username'], 'user');
            Session::set('userId', $userId, 'user');
            Session::set('loginTime', $loginTime, 'user');
            $user->save(['login_time' => $_SERVER['REQUEST_TIME']],['username' => $data['username']]);
            $this->success('登陆成功',url('index/index/showarticle'));
        }else {
            $this->error('用户名或密码错误');
        }

        $user->save(['status' => 1],['username' => $data['username']]);

    }

    //注册
    public function register(){

        $data     = $this->request->param();
        //验证器
        $validate = $this->validate($data, 'Register');
        if ($validate !== true) {
            $this->error($validate,url('index/index/register'));
        }

        //实例化模型
        $user        = new UserModel();
        //判断唯一
        $hasUserName = $this->hasUsername($data['username'],$user);
        $hasEmail    = $this->hasEmail($data['email'],$user);
        if ($hasUserName > 0 ) {
            $this->error('用户名已存在',url('index/index/register'));
        } elseif ($hasEmail > 0 ){
            $this->error('Email已被注册',url('index/index/register'));
        }

        //头像图片上传
        $head_img = $this->request->file('head_img');
        if (is_object($head_img)) {
            //上传图片
            //验证
            $head_img_validate = $head_img->validate(['size' => 200000,'ext' => 'gif,jpg,jpeg,bmp,png'])->check();
            if($head_img_validate === false) {
                $this->error('图片上传错误',url('index/index/register'));
            }

            //保存
            $file = $head_img->move(ROOT_PATH.'public'.DS.'/static/images/'.$data['username'],'',false);

            //设置路径
            if ($file !== false) {
                $data['head_img'] = '/images/'.$data['username'] .'/'. $file->getSaveName();
            } else {
                $img_array = $head_img->getInfo();
                $img_name  = $img_array['name'];
                $data['head_img'] = '/images/'.$data['username'] .'/'. $img_name;
            }
            //处理中文名
            $data['head_img'] = iconv('GB18030','utf-8',$data['head_img']);
        } elseif ($head_img === null) {
            //未上传
            if (empty($data['img_num'])) {
                //未选择
                $data['head_img'] =  '/images/pix'.rand(1,6).'.jpg';
            } elseif (is_numeric($data['img_num'])) {
                //已选择
                $imgNum = $data['img_num'];
                //验证
                $imgNum = ceil($imgNum);
                if ($imgNum <= 6 && $imgNum >= 1) {
                    $data['head_img'] = '/images/pix'.$imgNum.'.jpg';
                } else {
                    $data['head_img'] =  '/images/pix'.rand(1,6).'.jpg';
                }
            } else {
                //错误
                $this->error('图片上传错误',url('index/index/register'));
            }
        }

        //保存数据
        $affected_rows = $user->allowField(true)->save($data);
        if ($affected_rows > 0) {
            $this->success('注册成功',url('index/index/showarticle'));
        } else {
            $this->error('注册失败',url('index/index/register'));
        }
    }

    //用户信息
    public function userInfo() {
        //判断是否登陆
        if (Session::has('username','user') === false) {
            $this->error('请登陆',url('index/index/login'));
        }

        $fields = 'email,head_img,create_time';

        $user = new UserModel();
        $userInfo = $user->where('user_id', '=', Session::get('userId','user'))
                         ->field($fields)
                         ->find();

        if ($userInfo !== null) {
            $userInfo = $userInfo->toArray();
            $userInfo['user_id']    = Session::get('userId','user');
            $userInfo['username']   = Session::get('username','user');
            $userInfo['login_time'] = Session::get('loginTime','user');
        }
        //dump($userInfo);exit;
        $this->assign('userInfo', $userInfo);
        return $this->fetch();
    }

    public function articleList() {
        return $this->fetch();
    }
    public function commentList() {
        return $this->fetch();
    }
    public function replyList() {
        return $this->fetch();
    }
    public function favorite() {
        return $this->fetch();
    }

    //判断username唯一,提供AJAX使用
    public function hasUsername($username = '',$UserModel = '') {

        if (empty($username)) {
            $username = $this->request->param('username');
        }
        if (!is_object($UserModel)) {
            $UserModel = new UserModel();
        }
        $hasUserName = $UserModel->where('username','=', $username)->count();
        return $hasUserName;
    }

    //判断email唯一,提供AJAX使用
    public function hasEmail($email = '',$UserModel = '') {

        if (empty($email)) {
            $email = $this->request->param('email');
        }
        if (!is_object($UserModel)) {
            $UserModel = new UserModel();
        }
        $hasEmail = $UserModel->where('email','=', $email)->count();
        return $hasEmail;
    }


}