<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 通用控制器
 * 主要用于验证是否登陆 以及 用户权限
 */
class CommonController extends Controller {
    
    public function __construct() {
        parent::__construct();
        
        
        //访问过滤
        //获取当前用户访问的'控制器/方法'信息
        $now_ac = CONTROLLER_NAME."-".ACTION_NAME;
        //获取当前用户允许访问的权限信息
        $admin_id = session('admin_id');
        $admin_name = session('admin_name');
        $manager_info = D('Manager')->find($admin_id);
        $roleid = $manager_info['role_id'];
        $roleinfo = D('Role')->find($roleid);
        $auth_ac = $roleinfo['role_auth_ac'];
        
        //默认允许访问的权限
        $allow_ac ="Index-index,Index-left,Index-head,Index-right,Index-drag,";
        //当前访问权限 与 允许访问权限 对比
        if(strpos($auth_ac,$now_ac)===false && strpos($allow_ac, $now_ac)===false && $admin_name !=='admin'){
            
            exit('没有访问权限!');
        }
    }
    /**
     * 自动执行
     */
    public function _initialize(){
        // 判断用户是否登录
        if(session('admin_id')) {
            return;
            exit;
        }else {
            $this->redirect('Manager/login','',2,'您还没有登录');
        }
    }
}
