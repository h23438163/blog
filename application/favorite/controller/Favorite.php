<?php
/**
 * Created by PhpStorm.
 * User: turonver
 * Date: 2018/5/8
 * Time: 12:18
 */

namespace app\favorite\controller;


use think\Controller;
use \app\favorite\model\Favorite as FavoriteModel;
use think\Session;

class Favorite extends Controller
{
    public function isFavorite($articleId, $favorite = '') {

        $userId     = Session::get('userId', 'user');
        if (!is_object($favorite)) {
            $favorite = new FavoriteModel();
        }

        $isfavorite = $favorite ->where('user_id',   '=', $userId)
                                ->where('article_id','=', $articleId)
                                ->find();
        if ($isfavorite === null) {
            return 0;
        } elseif(is_object($isfavorite)) {
            $isfavorite = $isfavorite->toArray();
            return $isfavorite['favorite_id'];
        }

    }

    public function addFavorite ($articleId) {

        $userId     = Session::get('userId', 'user');
        $favorite   = new FavoriteModel();
        $isfavorite = $this->isFavorite($articleId, $favorite);
        if ($isfavorite !== 0) {
            //删除收藏
            $affected_rows = $favorite->where('favorite_id', '=', $isfavorite)->delete();
            if ($affected_rows > 0) {
                return 1;
            }
        } else {
            //添加收藏
            $affected_rows = $favorite->save(['user_id' => $userId, 'article_id' => $articleId]);
            if ($affected_rows > 0) {
                return 0;
            }
        }


    }
}