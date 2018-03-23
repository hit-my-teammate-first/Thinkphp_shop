<?php


//cookie加密函数
function jm($a){
    return md5($a);
}

//检测cookie
function che(){
    return jm(cookie('username').cookie('userid').C('COO_KIE')) == cookie('key');
}

