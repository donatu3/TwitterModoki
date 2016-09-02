$(function(){
        //ログインをおした時
        $(document).on("click","#login_button", function () {
            location.href = $(this).data("url");
            return false;
        });     
});