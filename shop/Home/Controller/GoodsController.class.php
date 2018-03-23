<?php
namespace Home\Controller;
use Think\Controller;
class GoodsController extends Controller{
    //商品详情
    public function goods(){
 
        $goodsModel=D('Model/Goods');
        //获取商品信息;
        $goodsinfo = $goodsModel->find(I('goods_id'));
        //获取商品评论并分页
        $count = $goodsModel->where(array('goods_id'=>I('goods_id')))->count();
        $Page  = new \Think\Page($count,1); 
        $show = $Page->show();
        $commentlist = D('Model/Comment')->where(array('goods_id'=>I('goods_id')))->limit($Page->firstRow.','.$Page->listRows)->select();
        //面包屑条
        $mbx = $this->mbx($goodsinfo['cat_id']);
        
        //赋值
        $this->assign('count',$count);           //总页数
        $this->assign('page',$show);             //赋值分页
        $this->assign('commentlist',$commentlist);   //赋值数据
        $this->assign('mbx',$this->mbx($goodsinfo['cat_id']));
        $this->assign('goodsinfo',$goodsinfo);
        $this->display();
    }
    
    //添加购物车
    public function gwc(){
        $id=I('post.goods_id');
        $goodsinfo = D('Model/Goods')->find(I('post.goods_id'));
        $tools = \Tools\AddTool::getIns();
        $tools->add($goodsinfo['goods_id'],$goodsinfo['goods_name'],$goodsinfo['goods_price'],$goodsinfo['goods_img_s']);
        //dump(session('car'));
        echo 'true';
    }
    
    //面包屑
    public function mbx($cat_id){
        $catModel = new \Model\CatModel();
        $fm = array();
        while($cat_id > 0){
            foreach ($catModel->select() as $k => $v){
                if($cat_id == $v['cat_id']){
                    $fm[]=$v;
                    $cat_id = $v['parent_id'];
                    break;
                }
            }
        }
        return (array_reverse($fm));
    }
}

