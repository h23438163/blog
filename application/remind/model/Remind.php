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
        'isremind' => '0',
        'id'
    ];

    public function setIdAttr($id) {
        return null;
    }

}