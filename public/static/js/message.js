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

	});