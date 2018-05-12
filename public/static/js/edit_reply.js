
$(document).ready(function(){

    $('#reply_content').blur(function (){

        if($.trim($(this).val()) == ''){

            $('#reply_content_error').text('请输入内容');
        }else {

            $('#reply_content_error').text('');
        }
    });

    $('#authcode_reply').blur(function (){

        if($.trim($(this).val()) == ''){
            $('#authcode_reply_error').text('请填写验证码');

        }else {
            authcode = $.trim($(this).val());
            $.ajax({
                url:captcha_url,
                data:{'authcode':authcode,'id':'updatereply'},
                type : "POST",
                async : true,
                success:function(data){
                    switch(data){
                        case '1':
                            $('#authcode_reply_error').text('');
                            $('#authcode_reply_error').append("<img src='/public/static/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
                            break;
                        case '0':
                            $('#authcode_reply_error').text('验证码错误');
                            $('#captcha_img').attr({"src":captcha_src});
                            break;
                    }
                }
            });
        }
    });


    $('#reply_submit').submit(function (){

        var reply_content  = true;
        var authcode_reply = true;

        if($.trim($('#reply_content').val()) == ''){
            $('#reply_content_error').text('请输入内容');
            reply_content = false;
        }

        if($.trim($('#authcode_reply').val()) == ''){
            $('#authcode_reply_error').text('请填写验证码');
            authcode_reply = false;
        }else {
            authcode = $.trim($('#authcode_reply').val());
            $.ajax({
                url   :captcha_url,
                data  :{'authcode':authcode,'id':'updatereply'},
                type  : "POST",
                async : false,
                success:function(data){
                    switch(data){
                        case '1':
                            $('#authcode_reply_error').text('');
                            $('#authcode_reply_error').append("<img src='/public/static/images/check_right.gif' style='border:0px;margin-top:-5px;'>");
                            authcode_reply = true;
                            break;
                        case '0':
                            $('#authcode_reply_error').text('验证码错误');
                            $('#captcha_img').attr({"src":captcha_src});
                            authcode_reply = false;
                            break;
                    }
                }
            });
        }

        if(reply_content && authcode_reply){
            return true;
        }
        return false;
    });
});