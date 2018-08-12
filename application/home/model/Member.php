<?php
namespace app\home\model;
use think\Model;
class Member extends Model{
    protected $pk = 'member_id';
    protected $autoWriteTimestamp = true;

    protected static function init(){
        //入库前事件
        Member::event('before_insert',function($member){
            $member['password'] = md5($member['password'].config('password_salt'));
        });
    }

    public function checkUser($username,$password){
        $where = [
            'username' => $username,
            'password' => md5($password.config('password_salt'))
        ];
        $userInfo = $this->where($where)->find();
        if($userInfo){
            //将用户信息写入到session
            session('member_id',$userInfo['member_id']);
            session('member_username',$userInfo['username']);
            return true;
        }else{
            return false;
        }
    }
}
