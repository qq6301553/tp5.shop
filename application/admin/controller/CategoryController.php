<?php
namespace app\admin\controller;
use app\admin\model\Category;
class CategoryController extends CommonController{
    public function del(){
        $cat_id = input('cat_id');
        if(Category::destroy($cat_id)){
            $this->success('删除成功',url('admin/category/index'));
        }else{
            $this->error('删除失败');
        }
    }

    public function upd(){
        $cat_id = input('cat_id');
        $catModel = new Category();
        $cat = $catModel->find($cat_id);
        //获取无限极分类
        $categorys = $catModel->getSonscat($catModel->select());
        return $this->fetch('',[
            'cat' => $cat,
            'categorys' => $categorys
        ]);
    }

    public function index(){
        $catModel = new Category();
        //获取无限极分类
        $cats = $catModel->getSonscat($catModel->select()->toArray());
        return $this->fetch('',['cats'=>$cats]);
    }

    public function add(){
        //判断是否是post请求
        if(request()->isPost()){
            //接收post参数
            $postData = input('post.');
            //验证器验证
            $result = $this->validate($postData,'Category.add',[],true);
            if($result !== true){
                $this->error(implode(',',$result));
            }
            //实例化模型写入数据库
            $CategoryModel = new Category();
            if($CategoryModel->allowField(true)->save($postData)){
                $this->success("添加成功",url("/admin/Category/index"));
            }else{
                $this->error("添加失败");
            }
        }
        //取出所有的无限级分类的数据
        $categoryModel = new Category();
        $categorys = $categoryModel->getSonsCat( $categoryModel->select() );
        return $this->fetch('',['categorys'=>$categorys]);
    }
}