<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function Navi($pageNow,$PageCount,$path,$other=''){

    $navi="";
    //页码显示
    //如果当前页不在首页 则显示首页和上一页
    if($pageNow>1){
        if($other == ''){
            $navi.= "<a href=".url($path).">首页</a>&nbsp";   //首页
        }else{
            $navi.= "<a href=".url($path,$other).">首页</a>&nbsp";   //首页
        }
        $Previous = $pageNow - 1;
        $navi.= "&nbsp<a href=".url($path,'page='.$Previous.''.$other.'').">上一页</a>&nbsp;";
    }

    //计算多页翻页
    $pageWhole = 5;
    $start     = floor(($pageNow - 1) / $pageWhole) * $pageWhole + 1;

    if ($pageNow > $pageWhole) {
        $start1 = $start - 1;
        $navi.= "&nbsp<a href=".url($path,'page='.$start1.''.$other.'').">前".$pageWhole."页</a>&nbsp";
    }

    //循环得到页码
    for (; $start < floor(($pageNow - 1) / $pageWhole) * $pageWhole + 1 + $pageWhole && $start <= $PageCount; $start++) {
        if ($pageNow != $start) {
            $navi.= "<a href=".url($path,'page='.$start.''.$other.'').">$start</a>&nbsp";
        } else {
            $navi.= "<span>$start</span>&nbsp";
        }
    }

    if ($PageCount - $start > 0) {
        $navi.= "&nbsp<a href=".url($path,'page='.$start.''.$other.'').">后".$pageWhole."页</a>&nbsp";
    }

    if ($pageNow<$PageCount) {  //判断是否为最后一页,不是最后一页显示下一页和末页
        //下一页代码
        $Next = $pageNow+1;
        $navi.= "&nbsp<a href=".url($path,'page='.$Next.''.$other.'').">下一页</a>";
        //末页代码
        $navi.= "&nbsp<a href=".url($path,'page='.$PageCount.''.$other.'').">末页</a>";
    }

    return $navi;
}