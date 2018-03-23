<?php
namespace Model;
use Think\Model;
class ManagerModel extends Model{
    //用户验证方法
    function checkNamePwd($name,$pwd){
        //根据name判断用户是否存在
        if(!empty($name)){
            $info=$this->where(array('mg_name'=>$name))->find();
            if($info){
                if($info['mg_pwd'] == md5($pwd)){
                    return $info;
                }
            }
            return false;
        }else{
            return false;
        }
    }
}
