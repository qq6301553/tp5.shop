<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//后台首页
Route::get('houtai','admin/index/index');
//前台首页
Route::get('/','home/index/index');

//后台分组路由
Route::group('admin',function(){
    //后台路由首页
    Route::get('index/left','admin/index/left');
    Route::get('index/top','admin/index/top');
    Route::get('index/main','admin/index/main');
    //后台用户列表
    Route::get('user/index','admin/user/index');
    Route::any('user/add','admin/user/add');
    Route::any('user/upd','admin/user/upd');
    Route::get('user/del','admin/user/del');
    //后台登陆退出
    Route::any('public/login','admin/public/login');
    Route::get('public/logout','admin/public/logout');
    //后台权限管理
    Route::any('auth/add','admin/auth/add');
    Route::any('auth/upd','admin/auth/upd');
    Route::get('auth/index','admin/auth/index');
    Route::get('auth/del','admin/auth/del');
    //后台角色管理
    Route::any('role/add','admin/role/add');
    Route::any('role/upd','admin/role/upd');
    Route::get('role/index','admin/role/index');
    Route::get('role/del','admin/role/del');
    //后台商品类型管理
    Route::get('type/getattr','admin/type/getattr');//查看商品类型的属性列表
    Route::get('type/index','admin/type/index');//类型列表
    Route::any('type/upd','admin/type/upd');//编辑类型
    Route::any('type/add','admin/type/add');//添加类型
    Route::get('type/del','admin/type/del');//删除类型
    //后台商品属性管理
    Route::get('attr/index','admin/attr/index');//属性列表
    Route::any('attr/upd','admin/attr/upd');//编辑类型
    Route::any('attr/add','admin/attr/add');//添加类型
    Route::get('attr/del','admin/attr/del');//删除类型
    //后台商品分类管理
    Route::get('category/index','admin/category/index');//属性列表
    Route::any('category/upd','admin/category/upd');//编辑类型
    Route::any('category/add','admin/category/add');//添加类型
    Route::get('category/del','admin/category/del');//删除类型
    //后台商品管理
    Route::get('goods/index','admin/goods/index');//商品列表
    Route::any('goods/upd','admin/goods/upd');//编辑类型
    Route::any('goods/add','admin/goods/add');//添加类型
    Route::get('goods/del','admin/goods/del');//删除类型
    Route::get('goods/getTypeAttr','admin/goods/getTypeAttr');//ajax获取商品类型的属性路由
    Route::get('goods/getContent','admin/goods/getContent');//ajax获取商品类型的详情页
});

//前台分组路由
Route::group('home',function(){
    Route::any('public/register','home/public/register'); //注册页面
    Route::any('public/login','home/public/login'); //登陆页面
    Route::any('public/logout','home/public/logout');   //退出
    Route::any('public/sendsms','home/public/sendsms'); //发送短信
    Route::get('public/forgetpassword','home/public/forgetpassword'); //忘记密码页面
    Route::get('public/sendemail','home/public/sendemail'); //发送邮件
    Route::any('public/setnewpassword/:member_id/:hash/:time','home/public/setnewpassword'); //重置新密码的路由
    Route::any('category/index','home/category/index'); //前台分类列表页
    Route::any('goods/detail','home/goods/detail'); //商品详情页
    Route::any('cart/addgoodstocart','home/cart/addgoodstocart'); //添加商品到购物车
    Route::any('cart/cartlist','home/cart/cartlist'); //购物车列表车
    Route::any('cart/delCartGood','home/cart/delCartGood'); //购物车商品删除
    Route::any('cart/clearCartGood','home/cart/clearCartGood'); //清空购物车商品
    Route::any('cart/updateCartGood','home/cart/updateCartGood'); //更新[+][-]购物车数量
    Route::any('cart/orderAccount','home/cart/orderAccount'); //结算购物车
    //个人订单付款路由
    Route::any('order/payMoney','home/order/payMoney');
    //展示个人订单路由
    Route::any('order/selforder','home/order/selforder');
    //支付成功的页面展示
    Route::any('order/orderdone','home/order/orderdone');
    //支付宝同步通知路由
    Route::any('order/returnurl','home/order/returnurl');
    //支付宝异步通知路由
    Route::any('order/notifyurl','home/order/notifyurl');
    //订单入库进行付款的路由
    Route::any('order/orderpay','home/order/orderpay');
});