<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/26
 * Time: 23:02
 */

namespace app\index\model;


use think\Model;

class BlogArticle extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_date';

    protected $insert = [
        'add_date',
        'article_id'
    ];
    public function getAddDateAttr($addtime){

        $year = date('Y',$addtime);
        $month = date('M',$addtime);
        $day = date('d',$addtime);

        return  array('year' => $year,'month' => $month,'day' => $day);
    }

    public function setArticleIdAttr($articleId){

        return null;
    }






}