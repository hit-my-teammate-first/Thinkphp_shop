<?php
namespace Admin\Controller;
use Think\Controller;
class CatController extends Controller{
    //商品分类展示
    public function catlist(){
        $cat= new \Model\CatModel();
        $catlist=$cat->getCategoryTree();
        $this->assign('catlist',$catlist);
        $this->display();
    }
    
    //添加新分类
    public function catadd(){
        $cat=new \Model\CatModel();
        if(!empty($_POST)){
            if(!empty($_POST['goods_name'])){
            $data=$cat->create();     //收集表单信息
                if($cat->add($data)){
                     $this->redirect('catlist','',2,'添加分类成功');
                }
                else{
                    $this->redirect('catadd','',2,'添加分类失败');
                }
            }
            $this->redirect('catadd','',2,'商品名称不能为空');
            exit;
        }
        $catlist=$cat->getCategoryTree();
        $this->assign('catlist',$catlist);
        $this->display();
    }
    
     
    // 更新分类
    public function catupdate(){
        $catmodel=new \Model\CatModel();
        $catid=(int)I('get.cat_id');
        if(!empty($_POST)){
            $_POST['cat_id']=$catid;
            
            //自己不能是自己的子集
            if($_POST['parent_id']==$catid){
                $this->redirect('catlist','',3,'自己不能是自己的子集');
                exit;
            }
            
            //指定的父级不能是自己的后代
            $sublist=$catmodel->getCategoryTree($catid);     //当前节点下的所有子元素
            foreach($sublist as $rows){
                if($_POST['parent_id']==$rows['cat_id']){
                   $this->redirect('catlist','',3, '指定的父级不能是自己的后代');  
                }
               
            }
            
            $data= $catmodel->create();
            if($catmodel->save($data)){
                $this->redirect('catlist','',2,'更新成功');
            }else{
                $this->redirect('catupdate','',2,'更新失败');
            }
            exit;
        }
        $catinfo=$catmodel->find($catid);
        $catlist=$catmodel->getCategoryTree();
        $this->assign('catinfo',$catinfo);
        $this->assign('catlist',$catlist);
        $this->display();
    }
    
        /*
     * 删除商品
     */
    public function catdel(){
        $catid=(int)I('get.cat_id');
        $catmodel= new \Model\CatModel();
        $catinfo=$catmodel->find($catid);
        if($catmodel->delete($catid)){  //删除该分类
            $catmodel->where('parent_id ='.$catid)->delete();     //删除该分类下的子分类
            $this->redirect('catlist','',2,'删除商品类别及其子类成功');
        }
    }
}
