$(document).ready(function(){

		//选中头像效果
		$('#head_img_select img').click(function(){

			if($(this).css('border-width') == '3px'){
				$(this).animate({'border-width':'1px','width':'30px'},function(){
					$(this).css({'border-color':''});
					$('#head_img').css({'display':''});	
					$('.hand_img_upload_style').fadeToggle();
					$('#head_img_dir').val('');
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
				$('#head_img_dir').val(this.title);
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
		$('#nickname').blur(function (){
								
			if($.trim($(this).val()) == ''){
				$('#name_error').text('请输入昵称');
				
			}else {
				$('#name_error').text('');
				
			}
		});

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
					url:'controllers/authcode.php',
					data:{'authcode':authcode,'p':'contact'},
					type : "POST",
					async : true,
					success:function(data){
						switch(data){
							case '2':
								$('#authcode_error').text('验证码过期');
								$('#captcha_img').attr({"src":'func/authcode.php?p=contact&r='+Math.random()});
								break;
							case '1':
								$('#authcode_error').text('');
								$('#authcode_error').append("<img src='/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
								break;
							case '0':
								$('#authcode_error').text('验证码错误或者未刷新');
								$('#captcha_img').attr({"src":'func/authcode.php?p=contact&r='+Math.random()});
								break;
						}
						
					}
					
				});
				//$('#authcode_error').text('');
				
			}
		});

		$('#sendmessage').submit(function(){

			var Head_Img = true;
			var Nikcname  = true;
			var content = true;

			if($('#head_img').val() == '' && $('#head_img_dir').val() == ''){

				$('#head_img_error').text('请选择或上传一个头像');

				$('#img_dir').text('');

				$('#head_img_error').css({'color':'red'});	

				Head_Img = false;
			}
			
			if($.trim($('#nickname').val()) == ''){

				$('#name_error').text('请输入昵称');			

				Nikcname = false;
			}

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
					url:'/controllers/authcode.php',
					data:{'authcode':authcode,'p':'contact'},
					type : "POST",
					async : false,
					success:function(data){
						switch(data){
							case '2':
								$('#authcode_error').text('验证码过期');
								$('#captcha_img').attr({"src":'func/authcode.php?p=contact&r='+Math.random()});
								authcode = false;
								break;
							case '1':
								$('#authcode_error').text('');
								$('#authcode_error').append("<img src='/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
								authcode = true;
								break;
							case '0':
								$('#authcode_error').text('验证码错误或者未刷新');
								$('#captcha_img').attr({"src":'func/authcode.php?p=contact&r='+Math.random()});
								authcode = false;
								break;
						}
					}
					
				});
			}

			if(Head_Img && Nikcname && content && authcode){
			
				return true;
			}

			return false;
		});
	});