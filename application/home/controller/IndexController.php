<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;
class IndexController extends Controller{
    public function index(){
        $catModel = new Category();
        $navDatas = $catModel->getNavData(5);
        //取出首页的三级分类筛选的数据
        $oldCat = $catModel->select();
        //两个技巧
        $cats = [];
        foreach($oldCat as $v){
            $cats[ $v['cat_id'] ] = $v;
        }
        $children = [];
        foreach($oldCat as $v){
            $children[ $v['pid'] ][] = $v['cat_id'];
        }
        //取出前台推荐位的商品
        $goodsModel = new Goods();
        $crazyDatas = $goodsModel->getGoods('is_crazy',5);
        $hotDatas = $goodsModel->getGoods('is_hot',5);
        $bestDatas = $goodsModel->getGoods('is_best',5);
        $newDatas = $goodsModel->getGoods('is_new',5);

        return $this->fetch('',[
            'navDatas'=>$navDatas,
            'cats'=>$cats,
            'children'=>$children,
            'crazyDatas'=>$crazyDatas,
            'hotDatas'=>$hotDatas,
            'bestDatas'=>$bestDatas,
            'newDatas'=>$newDatas
        ]);
    }

}