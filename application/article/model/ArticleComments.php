<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 23:05
 */

namespace app\article\model;


use think\Model;

class ArticleComments extends Model
{
    protected $resultSetType = 'collection';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'comments_time';
    protected $insert = [
        'comment_id',
        'comments_time'
    ];

    public function getCommentsTimeAttr($commentTime){
        return date('Y年m月d日 g:i:s a',$commentTime);
    }

    public function setCommentIdAttr($commentId){

        return null;
    }



}