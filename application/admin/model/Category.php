<?php
namespace app\admin\model;
use think\Model;
class Category extends Model{
    //表的主字段
    protected $pk = 'cat_id';
    //时间戳自动写入
    protected  $autoWriteTimestamp = true;

    //无限极分类,递归
    public function getSonscat($data,$pid=0,$level=1){
        static $result = [];
        foreach($data as $k => $v){
            if($v['pid'] == $pid){
                $v['level'] = $level;
                $result[ $v['cat_id'] ] = $v;
                //移除已经判断过的元素
                unset($data[$k]);
                //递归调用
                $this->getSonscat($data,$v['cat_id'],$level+1);
            }
        }
        //返回递归后的结果
        return $result;
    }


}