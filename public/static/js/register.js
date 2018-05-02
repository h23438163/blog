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

		
	});