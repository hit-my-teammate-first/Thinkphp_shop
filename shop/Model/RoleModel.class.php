<?php
namespace Model;
use Think\Model;
class RoleModel extends Model{
    
    
    //制作数据
    function saveAuth($roleid,$authid){
        //制作role_auth_ids
        $authids = implode(',', $authid);
        //制作role_auth_ac
        $authinfo = D('Auth')->select($authids);
        $s='';
        foreach($authinfo as $k => $v){
            if(!empty($v['auth_c'])&&!empty($v['auth_a'])){
            $s.=$v['auth_c']."-".$v['auth_a'].",";
            }
        }
        $s = rtrim($s,',');
        $data['role_id']=$roleid;
        $data['role_auth_ids']=$authids;
        $data['role_auth_ac']=$s;
        return $this->save($data);
    }
}

