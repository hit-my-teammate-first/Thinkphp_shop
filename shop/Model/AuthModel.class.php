<?php
namespace Model;
use Think\Model;
class AuthModel extends Model{
    
    public function saveData($data){
        //根据data(name,pid,controller,action)数据生成新纪录并返回新纪录id值
        $newid = $this->add($data);
        //制作auth_path
            //1)顶级权限，auth_path==新纪录主键id
        if($data['auth_pid']==0){
            $path = $newid;
        }else{
            //2)非顶级权限，根据pid父级权限信息，获得全路径：父级全路径-新纪录主键id值
            $pinfo = $this->find($data['auth_pid']);
            $path = $pinfo['auth_path']."-".$newid;
        }
        //制作auth_level,即是计算‘-’的个数，使用substr_count();
        $level=substr_count($path, '-');
        $udata['auth_path']=$path;
        $udata['auth_level']=$level;
        return $this->where('auth_id='.$newid)->save($udata);
    }
}

