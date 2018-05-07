<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/27
 * Time: 14:20
 */

namespace app\message\validate;


use think\Validate;

class UpdateMessage extends Validate
{
    protected $rule = [
        ['message_id', 'require|integer', '留言ID不能为空|留言ID错误'],
        ['content','require|max:256','内容不能为空|内容最大为256字'],
    ];

}