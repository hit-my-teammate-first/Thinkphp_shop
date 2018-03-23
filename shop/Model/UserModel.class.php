<?php
namespace Model;
use Think\Model;
class UserModel extends Model{
    
    //自动验证定义
    protected $_validate  =  array(
        //array(字段，验证规则，错误提示【，验证条件，附加规则，验证时间】）
        
        //用户名验证，不能为空，唯一
        array('username','require','用户名不能为空'),
        array('username','','用户名已存在',0,'unique'),
        
        //密码验证，不能为空，两次密码一致
        array('password','require','密码不能为空'),
        array('confirm_password','require','密码不能为空'),
        array('confirm_password','password','两次密码必须一致',0,'confirm'),
        
        //邮箱验证
        array('email','require','邮箱不能为空'),
        array('email','email','邮箱格式不正确'),
        
        //qq验证
        array('qq','number','qq号码必须为数字'),
        array('qq','6,12','qq号码长度必须在6-12之间',0,'length'),
        
        //手机验证
        array('phonenumber','require','手机号不能为空'),
        array('phonenumber','number','qq号码必须为数字'),
        array('phonenumber','10,11','手机号长度必须为11位',0,'length'),
        
        //密码问题验证
        array('sel_question','require','密码问题不能为空'),
        array('passwd_answer','require','密码问题不能为空'),
    );
    
    //用户验证方法
    function checkNamePwd($name,$pwd){
        //根据name判断用户是否存在
        if(!empty($name)){
            $info=$this->where(array('user_name'=>$name))->find();
            if($info){
                if($info['password'] == md5($pwd)){
                    return $info;
                }
            }
            return false;
        }else{
            return false;
        }
    }
    
    /*
     * 通过cookie登录
     */
    public function loginByCookie(){
        if(cookie('userid')==null|| cookie('username')==null){
           return false;
        }else{
        $name = cookie('username');
        $pwd = cookie('userid');
        $info = $this->checkNamePwd($name, $pwd);
            if($info){
                return $info;
            }
        return false;
        }
    }
}

