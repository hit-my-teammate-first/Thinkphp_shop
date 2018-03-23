<?php
namespace Admin\Controller;
use Think\Controller;

class AuthController extends CommonController{
    //显示权限列表
    public function showlist(){
        $authinfo = D('Model/Auth')->order('auth_path')->select();
        $this->assign('authinfo',$authinfo);
        $this->display();
    }
    
    //添加权限
    public function authadd(){
        $auth = new \Model\AuthModel();
        if(!empty($_POST)){
            //通过算法制作auth_path和auth_level,完善添加信息
            $rs=$auth->saveData($_POST);
            if($rs){
                $this->redirect('showlist','',2,'权限添加成功');
            }else{
                $this->redirect('authadd','',2,'权限添加失败');
            }
            
        }else{
            //获取上级权限
            $auth_info1 = $auth->where('auth_level=0')->select();
            
            $this->assign('authinfo',$auth_info1);
            $this->display();
        }
    }
    
    //修改权限信息
    public function authmodify(){
        $auth_id = $_GET['auth_id'];
        if(!empty($_POST)){
            $_POST['auth_id']=$auth_id;
            $rs=D('Auth')->save($_POST);
            if($rs){
                $this->redirect('showlist','',2,'权限信息修改成功');
            }else{
                $this->redirect('rolemodify','',2,'权限信息修改失败');
            }
        }else{
        //获取所有角色信息
        $auth_info = D('Auth')->where('auth_id='.$auth_id)->field('auth_name,auth_c,auth_a')->find();
        $this->assign('auth',$auth_info);
        $this->display();
        }
    }
    
    //删除权限
    public function del(){
        $auth_id =$_GET['auth_id'];
        if(!empty($auth_id)){
            $rs=D('Auth')->delete($auth_id);
            if($rs){
                //同时删除其子权限
                $rs=D('Auth')->where('auth_pid='.$auth_id)->delete();
                $this->redirect('showlist','',2,'权限及其子权限删除成功');
            }else{
                $this->redirect('showlist','',2,'权限及其子权限删除失败');
            }
        }else{
            $this->redirect('showlist','',2,'权限删除错误');
        }
    }
    
}

