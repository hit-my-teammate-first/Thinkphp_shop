<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Verify;

class ManagerController extends Controller{
    //登录控制
    public function login(){
        
        if(!empty($_POST)){
            //验证码验证
            $vry= new Verify();
            if($vry->check($_POST['captcha'])){
                //用户名和密码校验
                $user = new \Model\ManagerModel;
                $info=$user->checkNamePwd($_POST['username'],$_POST['password']);
                if($info){
                    //session持久化用户信息，跳转页面
                    session('admin_id',$info['mg_id']);
                    session('admin_name',$info['mg_name']);
                    $this->redirect("Index/index");
                }
                else{
                    echo "用户名或密码错误";
                }
            }else{
            echo "验证码错误";
            }
            //dump($_POST);
        }
        
        $this->display();
    }
    
    //退出
    public function logout(){
           session_destroy();
           $this->redirect('login');
    }
    
    //输出验证码
    public function verify(){
        $config =	array(
        'fontSize'  =>  30,              // 验证码字体大小(px)
        'useCurve'  =>  false,            // 是否画混淆曲线
        'useNoise'  =>  false,            // 是否添加杂点	
        'length'    =>  4,               // 验证码位数
        'fontttf'   =>  '',              // 验证码字体，不设置随机获取
        'bg'        =>  array(243, 251, 254),  // 背景颜色
        'reset'     =>  true,           // 验证成功后是否重置
        'imageH'    =>  45,               // 验证码图片高度
        'imageW'    =>  173,               // 验证码图片宽度
        );
        ob_clean();
        $vry = new Verify();
        $vry->entry();

    }
    
    //管理员列展示
    public function showlist(){
        $manager_info = D('Manager')->select();
        //dump($manager_info);
        //exit;
        $m = array();
        foreach ($manager_info as $k => $v){
            if($v['mg_role_id']==0){
                $v['role_name']= "超级管理员admin";
                $m[]=$v;
            }else{
                $roleinfo= D('Role')->find($v['mg_role_id']);
                $v['role_name']=$roleinfo['role_name'];
                $m[]=$v;
            }
        }
        $this->assign('m',$m);
        $this->display();
    }
    
    //修改角色
    public function rolemodify(){
        $mg_id = $_GET['mg_id'];
        $mg_role_id = $_GET['mg_role_id'];
        if(!empty($_POST)){
            $data['mg_id']=$mg_id;
            $data['mg_role_id']=$_POST['role_id'];
            $rs=D('Manager')->save($data);
            if($rs){
                $this->redirect('showlist','',2,'角色修改成功');
            }else{
                $this->redirect('rolemodify','',2,'角色修改失败');
            }
        }else{
        //获取所有角色信息
        $role_info = D('Role')->select();
        
        $this->assign('mg_role_id',$mg_role_id);
        $this->assign('roleinfo',$role_info);
        $this->display();
        }
    }
    
    //添加新管理员
    public function manageradd(){
        if(!empty($_POST)){
            $data = $_POST;
            $data['mg_pwd']=md5($_POST['mg_pwd']);
            $data['mg_time']= date("Y-m-d H:i:s");
            $rs=D('Manager')->add($data);
             if($rs){
                $this->redirect('showlist','',2,'管理员添加成功');
            }else{
                $this->redirect('manageradd','',2,'管理员添加失败');
            }
        }else{
        $role_info = D('Role')->select();
        
        $this->assign('roleinfo',$role_info);
        $this->display();
        }
    }
    //删除管理员
    public function del(){
        if($_GET['mg_role_id']!=0){
            $rs=D('Manager')->delete($_GET['mg_id']);
            if($rs){
                $this->redirect('showlist','',2,'管理员删除成功');
            }else{
                $this->redirect('showlist','',2,'管理员删除失败');
            }
        }else{
            $this->redirect('showlist','',2,'超级管理员无法删除');
        }
    }
        
}

