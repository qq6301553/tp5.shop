<?php
namespace app\admin\controller;
use app\admin\model\Auth;
class AuthController extends CommonController{
    public function del(){
        //接收id值
        $auth_id = input('auth_id');
        $res = Auth::destroy($auth_id);
        if($res){
            $this->success('删除成功',url('admin/auth/index'));
        }else{
            $this->error('删除失败');
        }
    }

    public function upd(){
        if(request()->isPost()){
            //1.接收post参数
            $postData = input('post.');
            //2.验证器验证
            if($postData['pid'] == 0){
                $result = $this->validate($postData,'Auth.onlyAuthName',[],true);
            }else{
                $result = $this->validate($postData,'Auth.upd',[],true);
            }
            if($result !== true){
                $this->error( implode(',',$result) );
            }
            //3.判断是否写入（添加、编辑、删除）数据库是否成功
            $authModel = new Auth();
            if($authModel->update($postData)){
                $this->success("编辑成功",url("/admin/auth/index"));
            }else{
                $this->error("编辑失败");
            }
        }
        //回显数据
        $auth_id = input('auth_id');
        $auth = Auth::get($auth_id);
        $authModel = new Auth();
        $auths = $authModel->getSonsAuth($authModel->select());
        return $this->fetch('',
            ['auth'=>$auth,
            'auths'=>$auths]
        );
    }

    public function index(){
        //关联pid字段
        $authModel = new Auth();
        $auth = $authModel->field('t1.*,t2.auth_name p_name')
            ->alias('t1')
            ->join('sh_auth t2','t1.pid = t2.auth_id','left')
            ->select();
        $auths = $authModel->getSonsAuth($auth);
        return $this->fetch('',['auths'=>$auths]);
    }

    public function add(){
        $authModel = new Auth();
        if(request()->isPost()){
            //接收post参数
            $postData = input('post.');
            //验证器验证,如果是顶级权限pid=0,验证onlyAuthName
            if($postData['pid']==0){
                $result = $this->validate($postData,'Auth.onlyAuthName',[],true);
            }else{
                $result = $this->validate($postData,'Auth.add',[],true);
            }
            if(!$result==true){
                $this->error(implode(',',$result));
            }
            //判断是否添加成功
            if($authModel->save($postData)){
                $this->success('添加成功',url('admin/auth/index'));
            }else{
                $this->success('添加失败');
            }

        }
        //获取无限极分类
        $auths = $authModel->getSonsAuth($authModel->select());
        return $this->fetch('',['auths'=>$auths]);
    }
}