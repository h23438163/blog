	$(document).ready(function(){	

		//点击评论博客效果代码
		$("#respond_article").click(function(){		
				if($('.respond').css('display') == 'none'){

					$(".respond").slideToggle(1000);
					$('#respond_article a').text('点击收起');	
					$("body,html").animate({scrollTop:$(document).height()},1000);
							
				}else{
					
					$(".respond").slideToggle(1000,function(){
						$('#respond_article a').text('点击评论');
					});
				}
		});
		//点击评论博客效果代码END


		$('#comments_content').blur(function (){

			if($.trim($(this).val()) == ''){

				$('#comments_content_error').text('请输入内容');
			}else {

				$('#comments_content_error').text('');
			}
		});

		$('#authcode').blur(function (){

			if($.trim($(this).val()) == ''){
				$('#authcode_error').text('请填写验证码');
				
			}else {
				authcode = $.trim($(this).val());
				$.ajax({
					url:captcha_url,
					data:{'authcode':authcode,'id':'updatecomment'},
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
			}
		});


		$('#submit').submit(function (){

			var comments_content = true;
			var comments_name = true;
			var authcode = true;


			if($.trim($('#comments_content').val()) == ''){

				$('#comments_content_error').text('请输入内容');

				comments_content = false;
			}

			if($.trim($('#authcode').val()) == ''){

				$('#authcode_error').text('请填写验证码');
				
				authcode = false;
			}else {

				authcode = $.trim($('#authcode').val());
				$.ajax({
					url:captcha_url,
					data:{'authcode':authcode,'id':'updatecomment'},
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

			if(comments_content && comments_name && authcode){

				return true;
			}

			return false;
			
		});

		
		//表单不能为空提示效果END
	});