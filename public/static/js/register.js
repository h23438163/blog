$(document).ready(function(){

	//选中头像效果
    $('#head_img_select img').click(function(){
		if($(this).css('width') == '60px'){
			$(this).animate({'border-width':'1px','width':'30px'},function(){
				$(this).css({'border-color':''});
				$('#head_img').css({'display':''});
				$('.hand_img_upload_style').fadeToggle();
				$('#img_num').val('');
			});
			$('#img_dir').text('');
		}else {
			$('#head_img_select img').animate({'border-width':'1px','width':'30px'},500,function(){
				$('#head_img_select img').css({'border-color':''});
			});
			$(this).animate({'border-width':'3px','border-style':'solid','width':'60px'},function(){
				$(this).css({'border-color':'#67c9f7'});
			});
			$('#img_dir').animate({'color':''});
			$('#img_num').val(this.title);
			$('#head_img_error').text('');
			$('#head_img').val('');
			$('#head_img').css({'display':'none'});
			$('.hand_img_upload_style').css({'display':'none'});
		}
	});

	$('#head_img').change(function(){
		$('#img_dir').text(this.value);
		$('#head_img_error').text('');
	});
	//选中头像效果END

    //表单不能为空提示效果
    $('#username').blur(function (){
        if($.trim($(this).val()) == ''){
            $('#username_error').text('用户名不能为空');
        } else {
            var username = $.trim($(this).val());
            $.ajax({
                url:has_username_url,
                data:{'username':username},
                type : "POST",
                async : true,
                success:function(data){
                    if (data > 0) {
                        $('#username_error').text('用户名已被注册');
                    } else {
                        $('#username_error').text('');
                    }
                }
            });
        }
    });
    $('#password').blur(function () {
        if ($.trim($(this).val()) == '') {
            $('#password_error').text('密码不能为空');
        } else if ($.trim($(this).val()) != $.trim($('#repassword').val())) {
            $('#password_error').text('两次密码不一致');
        } else {
            $('#password_error').text('');
        }
    });
    $('#repassword').blur(function (){
        if($.trim($(this).val()) != $.trim($('#password').val())) {
            $('#password_error').text('两次密码不一致');
        }else {
            $('#password_error').text('');
        }
    });
    $('#email').blur(function (){
        if($.trim($(this).val()) == ''){
            $('#email_error').text('email不能为空');
        }else {
            var email = $.trim($(this).val());
            $.ajax({
                url:has_email_url,
                data:{'email':email},
                type : "POST",
                async : true,
                success:function(data){
                    if (data > 0) {
                        $('#email_error').text('email已被注册');
                    } else {
                        $('#email_error').text('');
                    }
                }
            });
        }
    });
   $('#authcode').blur(function (){
        if($.trim($(this).val()) == ''){
            $('#authcode_error').text('请填写验证码');
        }else {
            var authcode = $.trim($(this).val());
            $.ajax({
                url:captcha_url,
                data:{'authcode':authcode,'id':'register'},
                type : "POST",
                async : true,
                success:function(data){
                    switch(data){
                        case '1':
                            $('#authcode_error').text('');
                            $('#authcode_error').append("<img src='/static/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
                            break;
                        case '0':
                            $('#authcode_error').text('验证码错误');
                            $('#captcha_img').attr({"src":captcha_src});
                            break;
                    }
                }
            });
        }
   });

    $('#register').submit(function(){

        var username = true;
        var password = true;
        var email    = true;
        var authcode = true;

        if($.trim($('#username').val()) == ''){
            $('#username_error').text('用户名不能为空');
            username = false;
        } else {
            var username = $.trim($('#username').val());
            $.ajax({
                url   :has_username_url,
                data  :{'username':username},
                type  : "POST",
                async : false,
                success:function(data){
                    if (data > 0) {
                        $('#username_error').text('用户名已被注册');
                        username = false;
                    } else {
                        $('#username_error').text('');
                        username = true;
                    }
                }
            });
        }

        if($.trim($('#password').val()) == ''){
            $('#password_error').text('密码不能为空');
            password = false;
        } else if ($.trim($('#password').val()) != $.trim($('#repassword').val())) {
            $('#password_error').text('两次密码不一致');
            password = false;
		}

        if($.trim($('#email').val()) == ''){
            $('#email_error').text('请输入内容');
            email = false;
        } else {
            var email = $.trim($('#email').val());
            $.ajax({
                url:has_email_url,
                data:{'email':email},
                type : "POST",
                async : false,
                success:function(data){
                    if (data > 0) {
                        $('#email_error').text('email已被注册');
                        email    = false;
                    } else {
                        $('#email_error').text('');
                        email    = true;
                    }
                }
            });
        }

        if($.trim($('#authcode').val()) == ''){
            $('#authcode_error').text('请填写验证码');
            authcode = false;
        }else {
            authcode = $.trim($('#authcode').val());
            $.ajax({
                url:captcha_url,
                data:{'authcode':authcode,'id':'register'},
                type : "POST",
                async : false,
                success:function(data){
                    switch(data){
                        case '1':
                            $('#authcode_error').text('');
                            $('#authcode_error').append("<img src='/static/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
                            authcode = true;
                            break;
                        case '0':
                            $('#authcode_error').text('验证码错误');
                            $('#captcha_img').attr({"src":captcha_src});
                            authcode = false;
                            break;
                    }
                }

            });
        }

        if(username && password && email && authcode){

            return true;
        }

        return false;
    });
});
