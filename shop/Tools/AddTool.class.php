<?php
namespace Tools;
abstract class ACartTools {
    /*
     * 向购物车添加一个商品
     * @param $goods_id int 商品id
     * @param $goods_name string 商品名称
     * @param $goods_price floa 商品价格
     * return Boolean
     */
    
    abstract public function add($goods_id,$goods_name,$goods_price,$goods_img_s);
    
    /*
     * 减少购物车一个商品数量
     * @param $goods_id int 商品id
     */
    abstract public function decr($goods_id);

    /*
     * 删除商品
     * @param $goods_id int 商品id
     */
    abstract public function del($goods_id);
    
    /*
     * 列出购物车所有商品
     * return array
     */
    abstract public function items();
    
    /*
     * 返回购物车有几种商品
     * return int
     */
    abstract public function calcType();
    
    /*
     * 返回商品个数
     * @rerurn int
     */
    abstract public function calcCnt();
    
    /*
     * 返回商品总价
     * @return float
     */
    abstract public function calcMoney();
    
    /*
     * 清空购物车
     * @return void
     */
    abstract public function clear();
    
}
    

class AddTool extends ACartTools{
    public $item = array();
    private static $ins = null;
    public static function getIns(){
        if(self::$ins == null){
            self::$ins = new self();
        }
        return self::$ins;
    }
    private function __construct() {
        $this->item = session('?car')?session('car'):array();
    }

    //防止克隆
    private function __clone() {
        
    }
    /*;
     * 向购物车添加一个商品
     * @param $goods_id int 商品id
     * @param $goods_name string 商品名称
     * @param $goods_price floa 商品价格
     * return Boolean
     */
    public function add($goods_id,$goods_name,$goods_price,$goods_img_s){
        if($this->item[$goods_id]){
            $this->item[$goods_id]['num'] +=1;
        }
        else{
            $goods['goods_id'] = $goods_id;
            $goods['goods_name'] = $goods_name;
            $goods['goods_price'] = $goods_price;
            $goods['goods_img_s'] = $goods_img_s;
            $goods['num'] = 1;
            $this->item[$goods_id] = $goods;
        }
    }
    
    /*
     * 减少购物车一个商品数量
     * @param $goods_id int 商品id
     */
    public function decr($goods_id){
        if($this->item[$goods_id]){
            $this->item[$goods_id]['num'] -=1;
        }
        if($this->item[$goods_id]['num']<=0){
            $this->del($goods_id);
        }
    }

    /*
     * 删除商品
     * @param $goods_id int 商品id
     */
    public function del($goods_id){
        unset($this->item[$goods_id]);
    }
    
    /*
     * 列出购物车所有商品
     * return array
     */
    public function items(){
        return $this->item;
    }
    
    /*
     * 返回购物车有几种商品
     * return int
     */
    public function calcType(){
        return count($this->item);
    }
    
    /*
     * 返回商品个数
     * @rerurn int
     */
    public function calcCnt(){
        $n = 0;
        foreach ($this->item as $k => $v){
            $n +=$v['num'];
        }
        return $n;
    }
    
    /*
     * 返回商品总价
     * @return float
     */
    public function calcMoney(){
        $n = 0;
        foreach ($this->item as $k => $v){
            $n +=$v['num']* $v['goods_price'];
        }
        return $n;
    }
    
    /*
     * 清空购物车
     * @return void
     */
    public function clear(){
        $this->item = null;
    }
    
    public function __destruct() {
        session('car',$this->item);
    }
}
    

