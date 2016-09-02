//参考　https://remotestance.com/blog/2701/
$(function(){
        //ツイートのdeleteボタンをおした時の処理
        $(document).on("click", "#delete_button", function() {
            // 確認ダイアログを表示
            var res = confirm("本当に削除しますか？");
            // 選択結果で分岐
            if( res == true ) {
                var self=this;
                $.ajax({  
                    data: { tweet_id : $(this).data("deleteid")},
                    dataType:"json", 
                    success:function (result, textStatus) {
                        if(result.zero){
                            alert("エラー：不正な削除です。")
                        }
                        if(!result.delete){
                            alert("エラー：ツイートの削除に失敗しました。")
                        }
                        //自画面遷移
                        location.href = $(self).data("url");
                    },
                    error: function(xhr, textStatus, errorThrown){
                        alert("エラー：もう一度試してください。");
                    },
                    type:"post",
                    url:"\/TwitterModoki\/tweets\/delete"
                });

            }
            return false;
        });        
});