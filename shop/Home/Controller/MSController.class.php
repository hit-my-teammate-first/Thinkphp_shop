<?php
namespace Home\Controller;
use Think\Controller;
class MSController extends Controller{
    private $redis;

    public function  __construct(){
        parent::__construct();
        $this->redis = new \Redis();
        $this->redis->connect('182.61.18.4',6379);
        $this->redis->auth('zzf@1357');
    }


    //秒杀商品入货
    public function ruhuo($goods_id = '21')
    {

        //获取商品库存
        $rs = D('Model/Goods')->where(array('goods_id' => $goods_id))->getField('stock');
        //echo $rs;
        $e = $this->redis->exists('goods_list');
        if(!$e){
            for ($i = 1; $i <= $rs; $i++) {
                $this->redis->lpush('goods_list', $i);
            }
            session_destroy();
            echo '进货成功';
            //dump(session('user'));
            echo $this->redis->llen('goods_list');
        }else{
            $this->redis->del('goods_list');
            $this->redis->del('bought_list');
            echo '秒杀商品已入货';
        }
    }

    //抢购商品
    public function redis_qianghuo(){
        //判断用户是否登录
        if(session('user')==null){
            $this->redirect('User/login');
        }
        //查询库存
        if($this->redis->lLen('goods_list') == 0) {
            $this->ajaxReturn('商品已售完...');
            /*echo "商品已售完..";
            exit;*/
        }
        $u_name = session('user.user_name');
        //echo $uid;
        //查询是否购买过
        if($this->redis->sIsMember('bought_list',$u_name)) {
            $this->ajaxReturn('你已经购买过了!');
            /*echo "你已经购买过了!";
            exit;*/
        }

        //抢购
        $goods_id = $this->redis->rpop('goods_list');
        $this->redis->sAdd('bought_list',$u_name);
        $value = array(
            'uid'   =>  $u_name,
            'goods_id'   =>  $goods_id,
            'time'  =>  time(),
        );
        $this->redis->hSet('order_info',$u_name,json_encode($value));
        $this->ajaxReturn('购买成功。');


       }
}