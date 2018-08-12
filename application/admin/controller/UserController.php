<?php
namespace app\admin\controller;
use app\admin\model\Role;
use think\Controller;
use app\admin\model\User;
class UserController extends CommonController{
    public function del(){
        //接收user_id值
        $user_id = input('user_id');
        //判断是否删除成功
        if( User::destroy($user_id) ){
            $this->success('删除成功',url('admin/user/index'));
        }else{
            $this->error('删除失败');
        }

    }

    public function index(){
        //获取数据
        $userModel = new User();
        $users = $userModel->alias('t1')
            ->field('t1.*,t2.role_name')
            ->join('sh_role t2','t1.role_id = t2.role_id','left')
            ->paginate(3);

        return $this->fetch('',[
            'users' => $users
        ]);
    }

    public function add(){
        //1.判断是否post请求
        if(request()->isPost()){
            $userModel = new User();
            //2.接收参数
            $postData = input('post.');
            //3.验证器验证
            $result = $this->validate($postData,'user.add',[],true);
            if($result!==true){ //验证失败
                $this->error(implode(',',$result));
            }
            //4.入库是否成功
            //给密码进行加密
            if( $userModel->allowField(true)->save($postData) ){
                $this->success('入库成功',url('admin/user/index'));
            }else{
                $this->error('入库失败');
            }
        }
        $roles = Role::select();
        return $this->fetch('',['roles'=>$roles]);
    }

    public function upd(){
        //判断是否post请求
        if( request()->isPost() ){
            $userModel = new User();
            //接收参数
            $postData = input('post.');
            //验证器验证
            //当前密码和确认密码都为空的时候,只验证username,保留原密码
            if($postData['password'] == '' && $postData['repassword'] == ''){
                $result = $this->validate($postData,'User.onlyUsername',[],true);
            }else{
                //说明其中有一个密码不为空,则进行UsernamePassword场景的验证
                $result = $this->validate($postData,'User.UsernamePassword',[],true);
                if($result!==true){
                    $this->error(implode(',', $result));
                }
            }
            //判断是否编辑成功
            if($userModel->allowField(true)->isUpdate(true)->save($postData)){
                $this->success('编辑成功',url('admin/user/index'));
            }else{
                $this->error('编辑失败');
            }

        }
        $user_id = input('user_id');
        $user = User::get($user_id);
        return $this->fetch('',['user'=>$user]);
    }


}