<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/8
 * Time: 12:34
 */

namespace app\favorite\model;


use think\Model;

class Favorite extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = false;
    protected $insert = [
        'favorite_id' => null,
    ];

}