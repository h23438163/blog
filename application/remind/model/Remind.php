<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/6
 * Time: 13:53
 */

namespace app\remind\model;


use think\Model;

class Remind extends Model
{
    protected $insert = [
        'isremind',
        'id'
    ];

    public function setIsRemindAttr($isremind) {
        return 0;
    }

    public function setIdAttr($id) {
        return null;
    }

}