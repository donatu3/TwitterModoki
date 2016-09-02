$(function(){
    //ダブルクリックによる多重submit防止　    
    $('#TweetHomeForm').submit(function(){
        $('.submit_button').attr('disabled','true').val("投稿中..");
        setTimeout(function() {
            $('.submit_button').removeAttr('disabled').val("投稿する");
        }, 10000);
    })
});