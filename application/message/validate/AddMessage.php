<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/27
 * Time: 14:20
 */

namespace app\message\validate;


use think\Validate;

class AddMessage extends Validate
{
    protected $rule = [
        ['content','require|max:256','内容不能为空|内容最大为256字'],
    ];

}