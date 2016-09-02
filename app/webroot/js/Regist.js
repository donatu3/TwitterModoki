$(function(){
        //ユーザ登録をおした時
        $(document).on("click","#regist_button", function () {
            location.href = $(this).data("url");
            return false;
        });     
});