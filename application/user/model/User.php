<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/30
 * Time: 17:26
 */

namespace app\user\model;


use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'login_time';
    protected $insert = [
        'status'
    ];

    public function setUserIdAttr($userid) {
        return null;
    }

    public function setStatusAttr($status) {
        return $status = 1;
    }

    public function setPasswordAttr($password) {
        return md5($password);
    }


}