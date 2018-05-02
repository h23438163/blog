$(document).ready(function(){

		
		//表单不能为空提示效果
		$('#title').blur(function (){
					
			if($.trim($(this).val()) == ''){

				$('#title_error').text('请输入标题');		
			}else {

				$('#title_error').text('');		
			}
		});	
		
		
		$('#author').blur(function (){

			if($.trim($(this).val()) == ''){

				$('#author_error').text('请输入标题');	
			}else {

				$('#author_error').text('');
			}
		});

		$('#tag1,#tag2,#tag3').blur(function (){

			if($.trim($('#tag1').val()) == ''){
				
				$('#tag_error').text('请输入分类');
			}else {
				
				$('#tag_error').text('');
			}
		});

		$('#img_upload').change(function(){
			$('#img_name').text(this.value);
		});

		$('#content').blur(function (){

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
					data:{'authcode':authcode,'p':'add'},
					type : "POST",
					async : true,
					success:function(data){
						switch(data){
							case '2':
								$('#authcode_error').text('验证码过期');
								$('#captcha_img').attr({"src":'func/authcode.php?p=add&r='+Math.random()});
								break;
							case '1':
								
								$('#authcode_error').text('');
								$('#authcode_error').append("<img src='/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
								break;
							case '0':
								$('#authcode_error').text('验证码错误或者未刷新');
								$('#captcha_img').attr({"src":'func/authcode.php?p=add&r='+Math.random()});
								break;
						}
						
						
					}
					
				});
				//$('#authcode_error').text('');
				
			}
		});

		$('#submit').submit(function (){

			var title = true;
			var tag = true;
			var author = true;
			var content = true;
			var authcode = true;

			if($.trim($('#title').val()) == ''){

				$('#title_error').text('请输入标题');

				title = false;
			}

			if($.trim($('#tag1').val()) == ''){

				$('#tag_error').text('请输入分类');

				tag = false;
			}

			if($.trim($('#content').val()) == ''){

				$('#content_error').text('请输入内容');

				content = false;
			}
			
			if($('#author').val() ==''){
				$('#author_error').text('请输入内容');

				author = false;
			}
			
			

			if($.trim($('#authcode').val()) == ''){

				$('#authcode_error').text('请填写验证码');
				
				authcode = false;
			}else {

				authcode = $.trim($('#authcode').val());
				$.ajax({
					url:'controllers/authcode.php',
					data:{'authcode':authcode,'p':'add'},
					type : "POST",
					async : false,
					success:function(data){
						switch(data){
							case '2':
								$('#authcode_error').text('验证码过期');
								$('#captcha_img').attr({"src":'func/authcode.php?p=add&r='+Math.random()});
								authcode = false;
								break;
							case '1':
								$('#authcode_error').text('');
								$('#authcode_error').append("<img src='/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
								authcode = true;
								break;
							case '0':
								$('#authcode_error').text('验证码错误或者未刷新');
								$('#captcha_img').attr({"src":'func/authcode.php?p=add&r='+Math.random()});
								authcode = false;
								break;
						}
					}
					
				});
			}

			if(title && tag && content && author && authcode){

				return true;
			}
			return false;
			
		});
		//表单不能为空提示效果END

	});