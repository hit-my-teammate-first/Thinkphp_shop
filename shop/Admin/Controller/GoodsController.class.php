<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends CommonController{
    //显示商品列表
    public function showlist(){
        //商品信息分页展示
        $goodsmodel = new \Model\GoodsModel();
        //查询记录数
        $count = $goodsmodel->count();
        //实例化分页类
        $Page  = new \Think\Page($count,3);
        //分页显示输出
        $show = $Page->show();
        $goodsinfo = $goodsmodel->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);           //总记录数
        $this->assign('page',$show);             //赋值分页
        $this->assign('goodslist',$goodsinfo);   //赋值数据
        $this->display();
    
    }
    /*
     * 添加新商品
     */
    public function goodsadd(){
        //获取商品信息
        if(!empty($_POST)){
            if($_FILES['goods_img']['erro']<4){
            $cfg = array(
                'rootPath'      =>  'Public/Uploads/', //设置图片保存路径
            );
            $up = new \Think\Upload($cfg);         //实例化对象
            $z=$up->uploadOne($_FILES['goods_img']);    //上传图片并返回图片路径等信息
            $img_path = $up->rootPath.$z['savepath'].$z['savename'];
            $s_img= $up->rootPath.$z['savepath'].'s_'.$z['savename'];
            
            //对上传图片制作缩略图
            $img = new \Think\Image();  //实例化对象
            $img->open($img_path);
            $img->thumb(100,100);
            $img->save($s_img);        //存储缩略图
            
            $_POST['goods_img'] = $img_path;
            $_POST['goods_img_s'] = $s_img;
            }
            
            //收集表单并存储到数据库
            $_POST['status']=isset($_POST['status'])?implode(',',$_POST['status']):'';
            $goodsmodel = new \Model\GoodsModel();
            $data = $goodsmodel->create();
            $rs = $goodsmodel->add($data);
            if($rs){
                $this->redirect('goodslist',array('name'=>'tom','id'=>'2'),3,'添加商品成功');
            }
            else{
                $this->redirect('goodsadd',array('name'=>'tom','id'=>'2'),3,'添加商品失败');
            }
            
            
        }
        $catmodel = new \Model\CatModel();
        $catlist = $catmodel->getCategoryTree();
        $this->assign('catlist',$catlist);
        $this->display();
    }
    
    /*
     * 更新商品
     */
    public function goodsmodify(){
        $goodsid=(int)I('get.goods_id');
        //获取数据信息
        $goodsmodel = new \Model\GoodsModel();
        $goodsinfo=$goodsmodel->find($goodsid);
        //上传更新数据
        if(!empty($_POST)){
          $data['goods_name']=$_POST['goods_name'];
          $data['goods_price']=$_POST['shop_price'];
          $data['cat_id']=$_POST['cat_id'];
          $data['status']= isset($_POST['status'])? implode(',', $_POST['status']):'';
          $data['goods_brief']=$_POST['goods_desc'];
          
          //上传图片
                if($_FILES['goods_img']['name']!=''){
                $cfg = array(
                    'rootPath'      =>  'Public/Uploads/', //设置图片保存路径
                );
                $up = new \Think\Upload($cfg);         //实例化对象
                $z=$up->uploadOne($_FILES['goods_img']);    //上传图片并返回图片路径等信息
                $img_path = $up->rootPath.$z['savepath'].$z['savename'];
                $s_img= $up->rootPath.$z['savepath'].'s_'.$z['savename'];

                //对上传图片制作缩略图
                $img = new \Think\Image();  //实例化对象
                $img->open($img_path);
                $img->thumb(100,100);
                $img->save($s_img);        //存储缩略图

                $data['goods_img'] = $img_path;
                $data['goods_img_s'] = $s_img;
              }
          
          $this->delRubbishImages($goodsinfo);
          $data['goods_id']=$goodsid;
          //写入数据库
            if($goodsmodel->save($data)){
                $this->redirect('goodslist','',2,'更新成功');
            }else{
                $this->redirect('goodsmodify','',2,'更新失败');
            }
        }
          
        $catmodel= new \Model\CatModel();
        $catlist=$catmodel->getCategoryTree();
        $status = explode(',',$goodsinfo['status']);
        $this->assign('goodsinfo',$goodsinfo);
        $this->assign('status',$status);
        $this->assign('catlist',$catlist);
        $this->display();
    }
    /*
     * 删除商品
     */
    public function del(){
        $goodsid=(int)I('get.goods_id');
        $goodsmodel= new \Model\GoodsModel();
        $goods_info=$model->find($goodsid);
        if($goodsmodel->delete($goodsid)){
            $this->delRubbishImages($goods_info);
            $this->redirect('goodslist','',0,'删除成功');
        }else{
            $this->redirect('goodslist','',0,'删除失败');;
        }
    }
    
    /*
     * 删除垃圾图片
     */
    private function delRubbishImages($goods_info){
        //获取商品图片路径
        $img_path=$goods_info['goods_img'];
        $img_s_path=$goods_info['goods_img_s'];
        if(file_exists($img_path)){
            unlink($img_path);
        }
        if(file_exists($img_s_path)){
            unlink($img_s_path);
        }
    }
}

