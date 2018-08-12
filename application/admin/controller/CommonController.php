<?php
namespace app\admin\controller;
use think\Controller;
class CommonController extends Controller{
    public function _initialize(){
        if(!session('user_id')){
            //没有session信息提示先登陆
            $this->error('请先登陆后操作',url('admin/public/login'));
        }else{
            //登陆没有翻墙,可能权限翻墙
            //获取session中的权限
            $visitorAuth = session('visitorAuth');
            //1.拼接获取到当前访问的控制器名和方法名,转为小写
            $now_ca = strtolower(request()->controller().'/'.request()->action());
            //2.判断访问的权限是否在session所记录的权限中,如果是超级管理员或者在index控制器中则放行
            if($visitorAuth == '*' || strtolower( request()->controller() ) == 'index'){
                return; //不在判断之列
            }
            // 3.非超级管理员,判断访问的权限是否在session所记录的权限中存在
            if(!in_array($now_ca,$visitorAuth)){
                exit('访问错误');
            }

        }
    }

}