$(function(){
        //投稿するボタンをおした時
        $(document).on("click","#toukou_button", function () {
            $('.submit_button').attr('disabled','true').text("投稿中..");
            count = $("#TweetMessage").val().length;
            if(count == 0){
                alert("内容が入力されていません");
                $('.submit_button').removeAttr('disabled').text("投稿する");
            }else if(count > 140){
                alert("140文字以下で入力してください");
                $('.submit_button').removeAttr('disabled').text("投稿する");
            }else{
                $.ajax({  
                    data: { username : session_username, 
                            message : $("#TweetMessage").val() 
                          },
                    dataType:"json", 
                    success:function (result, textStatus) {
                        if(result.error == true){
                            alert(result.message);
                        }
                        setTimeout(function() {
                            $('.submit_button').removeAttr('disabled').text("投稿する");
                            $("#TweetMessage").val('');
                        }, 1000);
                    },
                    error: function(xhr, textStatus, errorThrown){
                        alert("投稿に失敗しました"+xhr+textStatus);
                        $('.submit_button').removeAttr('disabled').text("投稿する");
                    },
                    type:"post",
                    url:"\/TwitterModoki\/tweets\/post"
                });
            }
        });       
});
