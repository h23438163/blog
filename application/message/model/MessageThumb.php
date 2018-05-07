<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 23:06
 */

namespace app\message\model;


use think\Model;

class MessageThumb extends Model
{
    protected $insert = [
        'id',
    ];

    public function setIdAttr () {
        return null;
    }
}