$(document).ready(function(){

		//表单不能为空提示效果
		$('#send_message_content').blur(function (){

			if($.trim($(this).val()) == ''){
				$('#content_error').text('内容不能为空');
				
			}else {
				$('#content_error').text('');
				
			}
		});

		$('#authcode').blur(function (){

			if($.trim($(this).val()) == ''){
				$('#authcode_error').text('请填写验证码');
				
			}else {
				var authcode = $.trim($(this).val());
				$.ajax({
					url:captcha_url,
					data:{'authcode':authcode,'id':'addmessage'},
					type : "POST",
					async : true,
					success:function(data){
						switch(data){
							case '1':
								$('#authcode_error').text('');
								$('#authcode_error').append("<img src='/static/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
								break;
							case '0':
								$('#authcode_error').text('验证码错误或者未刷新');
								$('#captcha_img').attr({"src":captcha_src});
								break;
						}
						
					}
					
				});
				//$('#authcode_error').text('');
				
			}
		});

		$('#sendmessage').submit(function(){

			var content = true;
            var authcode = true;



			if($.trim($('#send_message_content').val()) == ''){

				$('#content_error').text('内容不能为空');

				content = false;
			}

			if($.trim($('#authcode').val()) == ''){

				$('#authcode_error').text('请填写验证码');
				
				authcode = false;
			}else {

				authcode = $.trim($('#authcode').val());
				$.ajax({
                    url: captcha_url,
                    data:{'authcode':authcode,'id':'addmessage'},
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

			if(content && authcode){
			
				return true;
			}

			return false;
		});
	});