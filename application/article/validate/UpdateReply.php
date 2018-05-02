<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/28
 * Time: 10:58
 */

namespace app\article\validate;


use think\Validate;

class UpdateReply extends Validate
{
    protected $rule = [
        ['reply_id','require|integer','回复ID不能为空|必须是数字'],
        ['reply_content','require|max:256','评论内容不能为空|内容拆过256字']
    ];
}