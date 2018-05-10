$(document).ready(function(){

	//选中头像效果
	$('#head_img_select img').click(function(){

		if($(this).css('border-width') == '3px'){
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

        }else {
            $('#username_error').text('');

        }
    });
    $('#password').blur(function (){

        if($.trim($(this).val()) == ''){
            $('#password_error').text('密码不能为空');

        }else {
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
            $('#email_error').text('');

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
            //$('#authcode_error').text('');

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
