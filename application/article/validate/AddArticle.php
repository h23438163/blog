<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/4/27
 * Time: 14:20
 */

namespace app\article\validate;


use think\Validate;

class AddArticle extends Validate
{
    protected $rule = [
        ['article_title','require|max:64','标题不能为空|标题最大为64字'],
        ['article_tag1','require|max:10','分类不能为空|分类最大为10字'],
        ['article_tag2','max:10','分类最大为10字'],
        ['article_tag3','max:10','分类最大为10字'],
        ['content','require|max:256','内容不能为空|内容最大为256字'],
    ];

}