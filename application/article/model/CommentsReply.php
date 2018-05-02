<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 23:05
 */

namespace app\article\model;


use think\Model;

class CommentsReply extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'reply_date';
    protected $insert = [
        'reply_id',
        'reply_date',
    ];

    public function getReplyDateAttr($replydate){
        return date('Y年m月d日 g:i:s a',$replydate);
    }

    public function setReplyIdAttr(){
        return null;
    }


}