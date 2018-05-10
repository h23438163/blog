$(document).ready(function () {
    $.ajax({
        url    :isfavorite_url,
        type   :'GET',
        async  : true,
        success:function (data) {
            if (data != 0) {
                $('.fav').text('★');
                $('.fav').attr({'title':'已收藏'});
            }
        }
    });

    $('.fav').click(function () {
        $.ajax({
            url    :addfavorite_url,
            type   :'GET',
            async  : true,
            success:function (data) {
                if (data == 0) {
                    $('.fav').text('★');
                    $('.fav').attr({'title':'已收藏'});
                } else if (data == 1) {
                    $('.fav').text('☆');
                    $('.fav').attr({'title':'点击收藏'});
                }
            }
        });
    });
});