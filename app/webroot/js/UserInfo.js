$(function(){
        //フォローをおした時
        $(document).on("click","#user_info_tweet_button", function () {
            location.href = $(this).data("url");
            return false;
        });     
    
});