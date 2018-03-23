<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        //商城首页
        $catModel = new \Model\CatModel;
        $cattree=$catModel->getCategoryTree();
        $this->assign('cattree',$cattree);
        
        //热销商品
        $goodsModel = D('Model/Goods');
        $hot = $goodsModel->field(array('goods_id','goods_price','goods_img','goods_name'))->where('hot=1')->limit(0,3)->select();
        $this->assign('hot',$hot);
        $this->display();
    }
}