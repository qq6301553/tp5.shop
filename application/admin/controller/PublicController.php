<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
class PublicController extends Controller{
    public function login(){
        //判断是否post请求
        if( request()->isPost() ){
            //接收参数
            $postData = input('post.');
            //验证器验证
            $result = $this->validate($postData,'User.login',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            //判断是否登陆成功(把验证逻辑写到模型中)
            $userModel = new User();
            if( $userModel->checkUser($postData['username'],$postData['password']) ){
                $this->redirect('/houtai');
            }else{
                $this->error('用户或密码错误');
            }

        }
        return $this->fetch('');
    }

    public function logout(){
        //清楚session信息
        session('user_id',null);
        session('username',null);
        $this->redirect('/admin/public/login');
    }
}