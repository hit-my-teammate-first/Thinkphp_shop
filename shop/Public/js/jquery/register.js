$(function(){

		//刷新验证码
		function changeCaptcha(){
			 $("#Captcha-img").attr('src',function(i,v){
				  return v+'?'+Math.random();
			  });
		}

		//点击刷新验证码
		$("#Captcha-img").click(function(){
				  //验证码的更换
				  changeCaptcha();
			});


//设置错误和正确的样式
//function flagError(Phone){
       // $('#'+Phone).removeClass('valid1');
        ////$('#'+Phone+'flag').removeClass('valid2');
        //$('#'+Phone).addClass('error1');
        //$('#'+Phone+'flag').addClass('error2');
//}
//function flagValid(Phone){
       // $('#'+Phone).removeClass('error1');
       // $('#'+Phone+'flag').removeClass('error2');
       // $('#'+Phone).addClass('valid1');
       // $('#'+Phone+'flag').addClass('valid2');
//}

//验证账户
//设置状态
		var Phoneflag=false;
		function checkPhonejs(oo){
			var patten=/^[1][0-9]{10}/;
			if(!patten.test(oo.value)){
				$('#Phoneflag').html("手机号码格式不正确");
				return false;
			}else{        
				//利用Ajax实现用户名查询是否存在
				$.ajax({
                                        url:checkPhone,
                                        data:{'Phone':oo.value},
                                        dataType:'json',
                                        type:'post',
                                        success:function(msg){
                                                        if(msg){
                                                                //flagError('Phone');
                                                                $('#Phoneflag').html("手机号已注册");
                                                                return false;
                                                        }else{
                                                                //flagValid('Phone');
                                                                $('#Phoneflag').html('号码可用');
                                                                //Phoneflag=true;
                                                        }
                                        }
				});
			}
		}

$('#Phone').blur(function(){
        //查询用户名是否合法
        checkPhonejs(this);
});



		//密码验证
		//设置状态
		var passwordflag=false;
		function  checkPassword(oo){
			var patt = /^\w{6,16}$/;
				if(!patt.test(oo.value)){
					//flagError('password');
					$('#passwordflag').html('密码必须为6~16位只包含字母,数字,下划线');
					return false;
				}else{
					//flagValid('password');
					$('#passwordflag').html('');
					//passwordflag=true;
				}
				
		}

		

$('#password').keyup(function(){
        //查询密码是否合法
        checkPassword(this);
});

		//确认密码一致
		//设置状态
		var repasswordflag=false;
		function checkRepassword(oo){
			var password = $('#password').val();
				if(password != oo.value){
					if(oo.value.length>0){
					//flagError('repassword');
					$('#repasswordflag').html('两次密码输入不一致');
					return false;
					}

				}else{
					if(oo.value.length>0){
					//flagValid('repassword');
					$('#repasswordflag').html('');
					//repasswordflag=true;
					}

				}
		}

$('#repassword').keyup(function(){
        if (this.value.length < 6 || this.value.length > 16){
            return false;
             }
        checkRepassword(this);
});


		//验证昵称
		//设置状态
		var usernameflag=false;
		function checkusernamejs(oo){
			var patten=/^[\u4e00-\u9fa5_\w]{3,10}$/;
			if(!patten.test(oo.value)){
				//usernameflag=false;
				//flagError('username');
				$('#usernameflag').html("昵称必须为3~10位只包含字母,数字,下划线,中文");
				return false;
			}else{        
				//利用Ajax实现用户名查询是否存在
                                $.ajax({
                                        url:checkusername,
                                        data:{'username':oo.value},
                                        dataType:'json',
                                        type:'post',
                                        success:function(msg){
                                                        if(msg){
                                                                //flagError('Phone');
                                                                $('#usernameflag').html("昵称已被注册");
                                                                return false;
                                                        }else{
                                                                //flagValid('Phone');
                                                                $('#usernameflag').html('昵称可用');
                                                                //Phoneflag=true;
                                                        }
                                        }
				});
			}
		}

$('#username').blur(function(){
        //查询用户名是否合法
        checkusernamejs(this);
});

		//验证码
		//设置状态
		var Captchaflag=false;
		function checkCaptcha(oo){
			if(oo.value.length!=4){
				//flagError('Captcha');
				$('#Captchaflag').html("验证码格式不对");
				return false;
			}else{
			if (oo.value.length==4) {
				$.ajax({
                                    url:checkCaptcha,
                                    data:{'Captcha':oo.value},
                                    dataType:'json',
                                    type:'post',
                                    success:function(msg){
						if(msg){
							$('#Captchaflag').html("验证码错误,请重新输入");
							return false;
						}else{
							$('#Captchaflag').html('');
							Captchaflag=true;
						}
                                    }
                                });
                            }
			}
		}

$('#Captcha').blur(function(){
        checkCaptcha(this);
});





		$('#regis').click(function(){
			if (Phoneflag&&passwordflag&&repasswordflag&&usernameflag&&Captchaflag) {
				return true;
			}else{
				checkPhonejs($('#Phone')[0]);
				checkPassword($('#password')[0]);
				qiangdu($('#password')[0]);
				checkRepassword($('#repassword')[0]);
				checkusernamejs($('#username')[0]);
				checkCaptcha($('#Captcha')[0]);
				return Phoneflag&&passwordflag&&repasswordflag&&usernameflag&&Captchaflag;
			}
			return false;
			
		});

});