<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends CommonController{
   //后台首页面
    //头部
    function head(){
        $this->display();
    }
    
    function left(){
        //根据管理员获得其角色，进而获得去权限
        //根据管理员id获取信息
        $admin_id = session('admin_id');
        $manager_info = D('Model/Manager')->find($admin_id);
        $role_id = $manager_info['mg_role_id'];
        
        //获取角色信息
        $role_info = D('Model/Role')->find($role_id);
        $auth_id = $role_info['role_auth_ids'];
        
        //获取权限信息
        if($manager_info['mg_name'] == 'admin'){
            $auth_info1 = D('Model/Auth')->where("auth_level=0")->select();
            $auth_info2 = D('Model/Auth')->where("auth_level=1")->select();
        }else{
        $auth_info1 = D('Model/Auth')->where("auth_level=0 and auth_id in ($auth_id)")->select();
        $auth_info2 = D('Model/Auth')->where("auth_level=1 and auth_id in ($auth_id)")->select();
        }
        
        //dump($auth_info2);
        $this->assign('auth_info1',$auth_info1);
        $this->assign('auth_info2',$auth_info2);
        $this->display();
    }
    
    function right(){
        $this->display();
    }
    
    function index(){
        $this->display();
    }
    function drag(){
        $this->display();
    }
    
}

