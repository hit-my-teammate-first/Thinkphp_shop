<?php
namespace Home\Controller;
use Think\Controller;
class CatController extends Controller{
    public function cat(){
        $goodsModel = D('Model/goods');
        //查询记录数
        $count = $goodsModel->field('goods_id','goods_price','good_img','goods_name')->where('cat_id='.I('cat_id'))->count();
        //实例化分页类
        $Page  = new \Think\Page($count,1);
        //分页显示输出
        $show = $Page->show();
        $goodsinfo = $goodsModel->where('cat_id='.I('cat_id'))->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);           //总页数
        $this->assign('page',$show);             //赋值分页
        $this->assign('goodslist',$goodsinfo);   //赋值数据
        $this->display();
    }
   
}

