<?php
namespace app\admin\controller;
use app\admin\model\Attribute;
use app\admin\model\Goods;
use app\admin\model\Category;
use app\admin\model\Type;
class GoodsController extends CommonController{
    public function getContent(){
        if(request()->isAjax()){
            //接收参数
            $goods_id = input('goods_id');
            //获取content字段的内容
            $content = Goods::where('goods_id','=',$goods_id)->value('goods_desc');
            return json(['content'=>$content]);
        }
    }

    public function del(){
        $goods_id = input('goods_id');
        if(Goods::destroy($goods_id)){
            $this->success('删除成功',url('admin/goods/index'));
        }else{
            $this->error('删除失败');
        }
    }

    public function index(){
        $goods = Goods::alias('t1')
            ->field('t1.*,t2.cat_name')
            ->join("sh_category t2","t1.cat_id = t2.cat_id",'left')
            ->select();
        return $this->fetch('',['goods'=>$goods]);
    }

    public function getTypeAttr(){
        if(request()->isAjax()){
            $type_id = input('type_id');
            //取出属性表中对应条件的数据
            $attributes = Attribute::where(['type_id'=>$type_id])->select();
            echo json_encode($attributes);die;
        }
    }

    public function add(){
        //判断是否是post请求
        if(request()->isPost()){
            //接收post参数
            $postData = input('post.');
            //验证器验证
            $result = $this->validate($postData,'Goods.add',[],true);
            if($result !== true){
                $this->error(implode(',',$result));
            }
            //实例化模型写入数据库
            $goodsModel = new Goods();
            //开始上传文件
            $goods_img = $goodsModel->uploadImg();
            if($goods_img){
                //说明有原图上传成功过,对他进行缩略图处理
                $thumb = $goodsModel->thumb($goods_img);
                //把路径写入数据库(存json格式)
                $postData['goods_img'] = json_encode($goods_img);
                $postData['goods_middle'] = json_encode($thumb['goods_middle']);
                $postData['goods_thumb'] = json_encode($thumb['goods_thumb']);
            }
            if($goodsModel->allowField(true)->save($postData)){
                $this->success("添加成功",url("/admin/goods/index"));
            }else{
                $this->error("添加失败");
            }
        }
        //取出所有的无限级分类的数据
        $categoryModel = new Category();
        $typeModel = new Type();
        $categorys = $categoryModel->getSonsCat( $categoryModel->select() );
        $types = $typeModel->select();
        return $this->fetch('',[
            'categorys'=>$categorys,
            'types' => $types
        ]);
    }
}