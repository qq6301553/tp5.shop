<?php
namespace app\home\controller;
use app\home\model\Goods;
use app\home\model\Category;
use think\Controller;
class CategoryController extends Controller{
    public function index(){
        $cat_id = input('cat_id');
        //获取当前分类id的所有祖先分类
        $catModel = new Category();
        $cats = $catModel->select()->toArray();
        $familysCat = $catModel->getFamilysCat($cats,$cat_id);
        //halt($familysCat);
        //两个技巧
        //1.以每个每个cat_id作为数组的下标
        $catsData = [];
        foreach($cats as $v){
            $catsData[$v['cat_id']] = $v;
        }
        //2.通过pid进行分组
        $children = [];
        foreach($cats as $v){
            $children[ $v['pid'] ][] = $v['cat_id'];
        }

        //获取当前分类的子孙分类cat_id
        $sonsCatid = $catModel->getSonsCatId($cats,$cat_id);
        //把当前分类也要加上
        $sonsCatid[] = $cat_id;
        //halt($sonsCatid);
        //查询在子孙分类下面的所有商品即可
        $where = [
            'is_sale' => 1,
            'is_delete'=> 0,
            'cat_id' => ['in',$sonsCatid]
        ];
        $goodsData = Goods::where($where)->select()->toArray();

        return $this->fetch('',[
            'familysCat' => $familysCat,
            'catsData' => $catsData,
            'children' => $children,
            'goodsData' => $goodsData,
        ]);
    }
}