<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
/**
 * 注册与登录控制器
 */
Class UserController extends Controller {
    private $user;
    private $verify;
    /*
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->user = new \Model\UserModel();
        $this->verify = new Verify();
    }
    /**
     * 登录页面
     */
    public function login(){
        if(!empty($_POST)){;
            if($this->verify->check($_POST['captcha'])){
                //用户名和密码校验
                $userinfo=$this->user->checkNamePwd($_POST['username'],$_POST['password']);
                if($userinfo){
                    //设置cookie
                    cookie('userid',$userinfo['password']);
                    cookie('username',$userinfo['user_name']);
                    $coo_kie = jm($useinfo['user_name'].$useinfo['password'].C('COO_KIE'));
                    cookie('key',$coo_kie);
                    //session持久化用户信息，跳转页面
                    session('user',$userinfo);
                   $this->redirect("Index/index");
                }
                else{
                    $this->redirect("login",'',2,"用户名或密码错误");
                    exit;
                }
            }else{
                $this->redirect("login",'',2,"验证码错误");
                exit;
            }
        }
        
        //如果有cookie,通过cookie登录
        if($info = $this->user->loginByCookie()){
            session('user',$info);  //用户信息存放到sess中
            $this->redirect("Index/index");
            exit;
        }
        
        $this->display();
    }
    
 /*
 * 退出登录
 */
        public function logout(){
           cookie('userid',null);
           cookie('username',null);
           cookie('key',null);
           session_destroy();
           $this->redirect('login');
        }


    /**
     * 注册页面
     */
    Public function register () {
        $this->display();
    }

    /**
     * 注册表单处理
     */
    Public function CheckRegister() {
        //dump($_POST);
        //exit;
        //验证码判断
        $captcha = $_POST['Captcha'];
        if (!$this->verify->check($captcha)){
            $this->redirect('register','',2,'验证码错误');
        }

        //验证账户
        $patten='/^[1][0-9]{10}/';
        if(!preg_match($patten, $_POST['Phone'])){
            $this->redirect('register','',2,'手机格式不对');
        }
        if ($this->user->where('Phone='.$_POST['Phone'])->find()) {
            $this->redirect('register','',2,'号码已被注册');
        }

        //验证密码格式
        $patten='/^\w{6,16}$/';
        if(!preg_match($patten, $_POST['pwd'])){
            $this->redirect('register','',2,'密码格式不对');
        }
        if ($_POST['pwd'] != $_POST['pwded']) {
            $this->redirect('register','',2,'两次密码不一致');
        }

        //验证昵称
        $patten='/^[\x{4e00}-\x{9fa5}\w]{3,10}$/u';
        echo $_POST['username'];
        if(!preg_match($patten, $_POST['username'])){
            $this->redirect('register','',2,'昵称格式不对');
        }
        if ($this->user->where(array('user_name'=>$_POST['username']))->find()) {
            $this->redirect('register','',2,'昵称已被占用');
        }


        //提取POST数据
        $data = array(
            'phone' =>I('post.Phone'),
            'password' =>md5(I('post.pwd')) ,
            'registime' => $_SERVER['REQUEST_TIME'],
            'user_name' =>I('post.username')
            );
		//添加进数据库并返回主键id值
        $id = $this->user->add($data);
        if ($id) {//插入数据成功后把用户ID写SESSION
            //设置cookie
            $data['user_id']=$id;
            cookie('userid',$data['password']);
            cookie('username',$data['user_name']);
            $coo_kie = jm($data['user_name'].$data['password'].C('COO_KIE'));
            cookie('key',$coo_kie);
            //session持久化用户信息，跳转页面
            session('user',$data);
            $this->redirect("Index/index",'',2,'注册成功，正在为您跳转...');
        } else {
            $this->redirect('register','',2,'注册失败，请重试...');
        }
    }

    /**
     * 获取验证码
     */
    public function Captcha(){
        
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
        $this->verify->entry();
    }

    /**
     * 异步验证手机是否已被注册
     */
    Public function checkPhone () {
        if (!IS_POST) {
            $this->error('页面不存在');
        }
        $Phone = I('post.Phone');
        if($this->user->where('Phone='.$Phone)->find()) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    /**
     * 异步验证昵称是否已存在
     */
    Public function checkusername (){
       
        $username=$_POST['username'];
        $rs = $this->user->where('user_name='.'$username')->find();
        if($rs){
            echo 'true';
        }else{
            echo 'false';
        }
    }

    /**
     * 异步验证验证码
     */
    Public function checkCaptcha() {
        $captcha = I('post.Captcha');
        if ($this->verify->check($captcha)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
}

