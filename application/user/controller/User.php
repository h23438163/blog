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
    public function login(){

        $data        = $this->request->param('','','htmlspecialchars');
        $user        = new UserModel();
        $hasUsername = $this->hasUsername($data['username'], $user);

        $validate = $this->validate($data,'Login');

        if ($validate !== true) {
            $this->error($validate, url('index/index/login'));
        }

        if ($hasUsername == 0) {
            $this->error('用户不存在,请注册',url('index/index/register'));
        }

        $password = $user->where('username','=',$data['username'])->value('password');

        if (md5($data['password'])  === $password ) {
            $userId = $user->where('username', '=', $data['username'])->value('user_id');
            Session::set('username', $data['username'], 'user');
            Session::set('userId', $userId, 'user');
            $this->success('登陆成功',url('index/index/showarticle'));
        }else {
            $this->error('密码错误',url('index/index/login'));
        }

        $user->save(['status' => 1],['username' => $data['username']]);

    }

    public function register(){

        $data     = $this->request->param();
        $validate = $this->validate($data, 'Register');

        if ($validate !== true) {
            $this->error($validate,url('index/index/register'));
        }

        $head_img = $this->request->file('head_img');

        if (is_object($head_img)) {
            $head_img_validate = $head_img->validate(['size' => 200000,'ext' => 'gif,jpg,jpeg,bmp,png'])->check();

            if($head_img_validate === false) {
                $this->error('图片上传错误',url('index/index/register'));
            }

            $file = $head_img->move(ROOT_PATH.'public'.DS.'/static/images/'.$data['username'],'',false);

            if ($file !== false) {
                $data['head_img'] = '/images/'.$data['username'] .'/'. $file->getSaveName();
            } else {
                $img_array = $head_img->getInfo();
                $img_name  = $img_array['name'];
                $data['head_img'] = '/images/'.$data['username'] .'/'. $img_name;
            }
        } elseif ($head_img === null) {
            if (empty($data['img_num'])) {
                $data['head_img'] =  '/images/pix'.rand(1,6).'.jpg';
            } elseif (is_numeric($data['img_num'])) {
                $imgNum = $data['img_num'];
                $imgNum = ceil($imgNum);
                if ($imgNum <= 6 && $imgNum >= 1) {
                    $data['head_img'] = '/images/pix'.$imgNum.'.jpg';
                } else {
                    $data['head_img'] =  '/images/pix'.rand(1,6).'.jpg';
                }
            } else {
                $this->error('图片上传错误',url('index/index/register'));
            }
        }

        $user        = new UserModel();
        $hasUserName = $this->hasUsername($data['username'],$user);
        $hasEmail    = $this->hasEmail($data['email'],$user);

        if ($hasUserName > 0 ) {
            $this->error('用户名已存在',url('index/index/register'));
        } elseif ($hasEmail > 0 ){
            $this->error('Email已被注册',url('index/index/register'));
        }

        $saveRes = $user->allowField(true)->save($data);

        if ($saveRes > 0) {
            $this->success('注册成功',url('index/index/showarticle'));
        } else {
            $this->error('注册失败',url('index/index/register'));
        }
    }

    public function userInfo() {

        return $this->fetch();
    }

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