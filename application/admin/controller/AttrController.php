<?php
namespace app\admin\controller;
use app\admin\model\Attribute;
use app\admin\model\Type;

class AttrController extends CommonController{

    public function del(){
        $attr_id = input('attr_id');
        $result = Attribute::destroy($attr_id);
        if($result){
            $this->success('删除成功',url('admin/attr/index'));
        }else{
            $this->error('删除失败');
        }

    }

    public function upd(){
        if(request()->isPost()){
            $postData = input('post.');
            //手工输入
            if($postData['attr_input_type'] == 0){
                $result = $this->validate($postData,"Attr.except_values",[],true);
            }else{
                // 列表选择
                $result = $this->validate($postData,"Attr.add",[],true);
            }
            if($result !== true){
                $this->error( implode(',',$result) );
            }
            //写入数据库
            $attributeModel = new Attribute();
            if($attributeModel->update($postData)){
                $this->success("编辑成功",url("/admin/attr/index"));
            }else{
                $this->error("编辑失败");
            }
        }
        $attr_id = input('attr_id');
        $attribute = Attribute::find($attr_id);
        //取出商品类型
        $types = Type::select();
        return $this->fetch('',[
            'attribute'=>$attribute,
            'types'=>$types
        ]);
    }

    public function index(){
        $arrtModel = new Attribute();
        $attrs = $arrtModel->alias('t1')
            ->field('t1.*,t2.type_name')
            ->join('sh_type t2','t1.type_id = t2.type_id','left')
            ->select();

        return $this->fetch('',['attrs'=>$attrs]);
    }

    public function add(){
        //判断是否是post请求
        if(request()->isPost()){
            //接收post参数
            $postData = input('post.');
            //验证器验证
            //如果录入方式为列表选择
            if($postData['attr_input_type'] == 1){
                $result = $this->validate($postData,'Attr.add',[],true);
            }else{
                $result = $this->validate($postData,'Attr.except_values',[],true);
            }

            if($result !== true){
                $this->error(implode(',',$result));
            }
            //实例化模型写入数据库
            $attrModel = new Attribute();
            if($attrModel->allowField(true)->save($postData)){
                $this->success("添加成功",url("/admin/attr/index"));
            }else{
                $this->error("添加失败");
            }
        }
        $types = Type::select();
        return $this->fetch('',[
            'types' => $types
        ]);
    }
}