$(function(){
    //submitしたときに呼び出される関数を設定
    //ユーザー登録の必須チェックに使用する

    $("#regist_checkinput").submit(function(){
        var errormessage = "";
        var errorflag = false;
        if($("#UserName").val() == ""){
            errormessage+="名前は必須項目です\n";
            errorflag = true;
        }
        if($("#UserUsername").val() == ""){
            errormessage+="ユーザIDは必須項目です\n";
            errorflag = true;
        }
        if($("#UserPassword").val() == ""){
            errormessage+="パスワードは必須項目です\n";
            errorflag = true;
        }
        if($("#UserPasswordConfirmation").val() == ""){
            errormessage+="パスワード（確認）は必須項目です\n";
            errorflag = true;
        }
        if($("#UserMail").val() == ""){
            errormessage+="メールアドレスは必須項目です\n";
            errorflag = true;
        }
        if(errorflag == true){
            alert(errormessage);
            return false;
        }else{
            return true;
        }
    });
    
    //ログインの必須チェックに使用する
    $("#login_checkinput").submit(function(){
        var errormessage = "";
        var errorflag = false;
        if($("#UserUsername").val() == ""){
            errormessage+="ユーザIDは必須項目です\n";
            errorflag = true;
        }
        if($("#UserPassword").val() == ""){
            errormessage+="パスワードは必須項目です\n";
            errorflag = true;
        }
        if(errorflag == true){
            alert(errormessage);
            return false;
        }else{
            return true;
        }
    });
});