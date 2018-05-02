<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/28
 * Time: 10:58
 */

namespace app\article\validate;


use think\Validate;

class AddReply extends Validate
{
    protected $rule = [
        ['comment_id','require|integer','评论ID不能为空|必须是数字'],
        ['article_id','require|integer','文章ID不能为空|必须是数字'],
        ['reply_content','require|max:256','评论内容不能为空|内容拆过256字']

    ];
}