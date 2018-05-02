
$(document).ready(function(){
	$('.jump').click(function (e){
		$('.jump_page').css({'display':'block','left':e.pageX,'top':e.pageY});
	});

	$('.jump_close').click(function (){
		$('.jump_page').css({'display':'none'});
	});	
});