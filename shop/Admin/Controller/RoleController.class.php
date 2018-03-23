<?php
namespace Admin\Controller;
use Think\Controller;

class RoleController extends CommonController{
    //显示角色列表
    public function showlist(){
        
        $role_info = D('Model/Role')->select();
        $this->assign('roleinfo',$role_info);
        $this->display();
    
    }
    
    //分配权限
    public function distribute($role_id){
        
        $role = new \Model\RoleModel();
        if(!empty($_POST)){
            $rs=$role->saveAuth($_POST['role_id'],$_POST['auth_id']);
            if($rs){
                $this->redirect('showlist','',2,'分配权限成功');
            }else{
                $this->redirect('distribute', array('role_id'=>$role_id),2,'分配权限失败');
            }
        }else{
        //查询被分配权限角色信息
        $role_info = D('Model/Role')->find($role_id);
        //查询当前角色已经拥有的权限
        $have_authids = $role_info['role_auth_ids'];
        $have_authids= explode(',', $have_authids);   //转为数组
        
        //获取可供选择权限
        $auth_info1 = D('Model/Auth')->where("auth_level=0")->select();
        $auth_info2 = D('Model/Auth')->where("auth_level=1")->select();
        
        $this->assign('have_authids',$have_authids);
        $this->assign('auth_info1',$auth_info1);
        $this->assign('auth_info2',$auth_info2);
        $this->assign('roleinfo',$role_info);
        $this->display();
        }
    }
    
    //添加新管理员
    public function roleadd(){
        if(!empty($_POST['role_name'])){
            $data['role_name']=$_POST['role_name'];
            $rs=D('Role')->add($data);
             if($rs){
                $this->redirect('showlist','',2,'角色添加成功');
            }else{
                $this->redirect('roleadd','',2,'角色添加失败');
            }
        }else{
            
        $this->display();
        }
    }
    
    //删除角色
    public function del(){
        if(!empty($_GET['role_id'])){
            $rs=D('Role')->delete($_GET['role_id']);
            if($rs){
                $this->redirect('showlist','',2,'角色删除成功');
            }else{
                $this->redirect('showlist','',2,'角色删除失败');
            }
        }else{
            $this->redirect('showlist','',2,'角色无法删除');
        }
    }
    
}

