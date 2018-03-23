<?php
namespace Model;
use Think\Model;

class CatModel extends Model{
    /*
     * 将数组创建成树形结构
     * @param 
     */
    private function CreateTree($list,$parent_id=0,$deep=0){
		static $tree=array();
		foreach($list as $rows){
			if($rows['parent_id']==$parent_id){
				$rows['deep']=$deep;
				$tree[]=$rows;
				$this->CreateTree($list,$rows['cat_id'],$deep+1);
			}
		}
		return $tree;
    }
    /*
     * 获得商品类别的树形结构
     */
    public function getCategoryTree($parent_id=0){
        $list=$this->select();
        return $this->CreateTree($list,$parent_id);
    }
}
