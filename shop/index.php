<?php
header("content-type:text/html;charset=utf-8");
//使用thinkphp开发shop商城


//框架的两种模式：
//开发模式（错误提示具体）
define('APP_DEBUG', true);
//生产模式（错误提示模糊）
//define('APP_DEBUG',false);

//不区分URL大小写
define('URL_CASE_INSENSITIVE',true);

define('BASE_PATH', $_SERVER['DOCUNMENT_ROOT']);
//定义域名
define('SITE', "www.php.com/tp/shop/");
//给静态资源设置绝对路径访问常量
//Home分组
define('HOME_URL',"/tp/shop/Public/Home");
define('HOME_IMAGES_URL',"/tp/shop/Public/Home/images");
define('HOME_js_URL',"/tp/shop/Public/Home/js");
//Admin分组
define('ADMIN_CSS_URL',"/tp/shop/Public/Admin/styles");
define('ADMIN_JS_URL',"/tp/shop/Public/Admin/js");
define('ADMIN_IMAGES_URL',"/tp/shop/Public/Admin/images");
define('PUB_URL',"/tp/shop/Public/");
//引入tp框架入口文件
include('../ThinkPHP/ThinkPHP.php');

?>