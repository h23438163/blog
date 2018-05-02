	$(document).ready(function(){

		$('#logininfo').blur(function (){
					
			if($.trim($(this).val()) == ''){

				$('#logininfo_error').text('请输入验证信息');		
			}else {

				$('#logininfo_error').text('');		
			}
		});	


		$('#authcode').blur(function (){

			if($.trim($(this).val()) == ''){
				$('#authcode_error').text('请填写验证码');
				
			}else {
				var authcode = $.trim($(this).val());
				$.ajax({
					url:'/controllers/authcode.php',
					data:{'authcode':authcode,'p':'login'},
					type : "POST",
					async : true,
					success:function(data){
						switch(data){
							case '2':
								$('#authcode_error').text('验证码过期');
								$('#captcha_img').attr({"src":'/func/authcode.php?p=login&r='+Math.random()});
								break;
							case '1':
								$('#authcode_error').text('');
								$('#authcode_error').append("<img src='/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
								break;
							case '0':
								$('#authcode_error').text('验证码错误');
								$('#captcha_img').attr({"src":'/func/authcode.php?p=login&r='+Math.random()});
								break;
						}
					}
					
				});
				//$('#authcode_error').text('');
				
			}
		});

		$('#submit').submit(function (){

			var logininfo = true;
			var authcode = true;
			
			if($.trim($('#logininfo').val()) == ''){

				$('#logininfo_error').text('请输入内容');

				logininfo = false;
			}

			if($.trim($('#authcode').val()) == ''){

				$('#authcode_error').text('请填写验证码');
				
				authcode = false;
			}else {

				authcode = $.trim($('#authcode').val());
				$.ajax({
					url:'/controllers/authcode.php',
					data:{'authcode':authcode,'p':'login'},
					type : "POST",
					async : false,
					success:function(data){
						switch(data){
							case '2':
								$('#authcode_error').text('验证码过期');
								$('#captcha_img').attr({"src":'/func/authcode.php?p=login&r='+Math.random()});
								authcode = false;
								break;
							case '1':
								$('#authcode_error').text('');
								$('#authcode_error').append("<img src='/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
								authcode = true;
								break;
							case '0':
								$('#authcode_error').text('验证码错误');
								$('#captcha_img').attr({"src":'func/authcode.php?p=login&r='+Math.random()});
								authcode = false;
								break;
						}
					
					}
					
				});
			}

			if(logininfo && authcode){

				return true;
			}
			return false;
			
		});

	});