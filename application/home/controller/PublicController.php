<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Member;
class PublicController extends Controller{
    public function setNewPassword($member_id,$hash,$time){
        //判断邮箱是否被篡改
        if( md5($member_id.$time.config('password_salt')) != $hash ){
            exit('你对地址做啥了');
        }
        //判断是否在有效期30分钟内
        if( time()>$time+1800 ){
            exit('早干嘛去了,现在晚了');
        }
        //接收参数
        if(request()->isPost()){
            $postData = input('post.');
            $result = $this->validate($postData,'Member.setNewPassword',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            //更新密码
            $data = [
                'member_id' => $member_id,
                'password' => md5($postData['password'].config('password_salt'))
            ];
            if( Member::update($data) ){
                $this->success('重置成功',url('/home/public/login'));
            }else{
                $this->error('重置失败');
            }
        }

        return $this->fetch('');
    }

    public function sendEmail(){
        if(request()->isAjax()){
            $email = input('email');
            $result = Member::where('email','=',$email)->find();
            //判断数据库中是否存在该邮箱
            if(!$result){
                //说明没有这个邮箱
                $response = ['code'=>-1,'message'=>'邮箱不存在'];
                echo json_encode($response);die;
            }
            //构造找回密码的连接地址
            $member_id = $result['member_id'];
            $time = time();
            $hash = md5($member_id.$time.config('password_salt'));
            //把用户的id和当前的时间和密码盐一起进行加密,防止用户篡改,后面验证邮箱地址的有效性
            $href = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/index.php/home/public/setNewPassword/".$member_id.'/'.$hash.'/'.$time;
            $content = "<a href='{$href}' target='_blank'>京西商城-找回密码</a>";
            //发送邮件
            if( sendEmail([$email],'找回密码',$content) ){
                $response = ['code'=>200,'message'=>'发送成功,请登陆邮箱查看'];
                echo json_encode($response);die;
            }else{
                $response = ['code'=>-2,'message'=>'发送失败,请稍后再试'];
                echo json_encode($response);die;
            }
        }
    }

    public function forgetPassword(){
        return $this->fetch('');
    }

    public function sendSms(){
        if(request()->isAjax()){
            //接收参数
            $phone = input('phone');
            //验证器验证该手机号有没有被注册过
            $result = $this->validate(['phone'=>$phone],"Member.sendSms",[],true);
            if($result!==true){
                //说明手机号已被注册过,不能注册
                $response = ['code'=>-1,'message'=>'手机号已占用,请更换一个'];
                echo json_encode($response);die;
            }
            //验证成功就发送短信
            $rand = mt_rand(1000,9999);
            $result = sendSms($phone,array($rand,'5'),'1');
            //判断是否发送成功.返回json数据
            if($result->statusCode == '000000'){
                //给手机验证码加盐处理,设置有效期5分钟
                cookie('phone',md5($rand.config('password_salt')),300);
                $response = ['code'=>200,'message'=>'发送短信成功'];
                echo json_encode($response);die;
            }else{
                $response = ['code'=>-2,'message'=>'网络异常请重试或'.$result->statusMsg];
                echo json_encode($response);die;
            }


        }
    }

    public function logout(){
        //清除session
        session('member_id',null);
        session('member_username',null);
        //重定向到登录页
        $this->redirect("/home/public/login");
    }

    public function login(){
        if(request()->isPost()){
            $postData = input('post.');
            $result = $this->validate($postData,"Member.login",[],true);
            if($result !== true){
                $this->error( implode(',',$result) );
            }
            //判断用户名和密码是否匹配
            $memModel = new Member();
            $flag = $memModel->checkUser($postData['username'],$postData['password']);
            if($flag){
                //判断是否有goods_id,如果有则返回到对应的商品详情页
                if(input('goods_id')){
                    $this->redirect("/home/goods/detail",['goods_id'=>input('goods_id')]);
                }
                $this->redirect("/");
            }else{
                $this->error("用户名或密码失败");
            }

        }
        return $this->fetch('');
    }

    public function register(){
        if(request()->isPost()){
            $postData = input('post.');
            $result = $this->validate($postData,"Member.register",[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            //写入数据库
            $memModel = new Member();
            if($memModel->allowField(true)->save($postData)){
                $this->success('注册成功',url('/'));
            }else{
                $this->error('注册失败');
            }
        }
        return $this->fetch('');
    }

}