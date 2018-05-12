//表单不能为空提示效果
$(document).ready(function () {

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

	$('#authcode').blur(function (){

		if($.trim($(this).val()) == ''){
			$('#authcode_error').text('请填写验证码');

		}else {
			var authcode = $.trim($(this).val());
			$.ajax({
				url:captcha_url,
				data:{'authcode':authcode,'id':'login'},
				type : "POST",
				async : true,
				success:function(data){
					switch(data){
						case '1':
							$('#authcode_error').text('');
							$('#authcode_error').append("<img src='/public/static/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
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

	$('#login').submit(function(){

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
		}



		if($.trim($('#authcode').val()) == ''){
			$('#authcode_error').text('请填写验证码');
			authcode = false;
		}else {
			authcode = $.trim($('#authcode').val());
			$.ajax({
				url:captcha_url,
				data:{'authcode':authcode,'id':'login'},
				type : "POST",
				async : false,
				success:function(data){
					switch(data){
						case '1':
							$('#authcode_error').text('');
							$('#authcode_error').append("<img src='/public/static/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
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
