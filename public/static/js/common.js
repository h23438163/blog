	
	$(document).ready(function(){	
			
		//菜单栏跟随屏幕效果代码
		$(document).scroll(function (){
			if($(document).scrollTop() >= 360 ){
				$('.sidebar').css({'position':'fixed','top':'25px','margin-left':'55.9em'});
			}else {
				$('.sidebar').css({'position':'static','margin-left':''});
			}
		});

		//选中表单文字消失效果
		$("input").focus(function(){

			if($(this).val() == this.defaultValue){
				$(this).val("")
			}
		});
		$("input").blur(function(){
			
			if($(this).val() == ""){
				$(this).val(this.defaultValue);
			}
		
		});
		//选中表单文字消失效果END

		//百度搜索
		$('#formsearch').submit(function (){
			if ($('#editbox_search').val() == '通过关键字搜索') {
				window.alert('请输入搜索内容');
				return false;
			} else {
				if ($('#select select').val() == 'baidu') {

					window.open("http://www.baidu.com/s?wd="+$('#editbox_search').val());
					return false;
				}
			}
		});
		//百度搜索END
		
		//返回顶部效果代码
		$('#backtop').click(function(){
			$("body,html").animate({scrollTop:0},500);
		});	
		//返回顶部效果代码END
	});