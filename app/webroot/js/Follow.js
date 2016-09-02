$(function(){
        //フォローをおした時
        $(document).on("click","#follow_button", function () {
            // 確認ダイアログを表示
            var res = confirm("フォローしますか？");
            // 選択結果で分岐
            if( res == true ) {
                var self=this;
                $.ajax({ 
                    //data:$("#button_2").closest("form").serialize(), 
                    data: { follow_id : $(this).data("userid")},
                    dataType:"json", 
                    success:function (result, textStatus) {
                        if(result.error){
                            alert("エラー：フォローに失敗しました")
                        }
                        //自画面遷移
                        location.href = $(self).data("url");
                    },
                    error: function(xhr, textStatus, errorThrown){
                        alert("エラー：もう一度試してください。" + textStatus + xhr + errorThrown);
                    },
                    type:"post",
                    url:"\/TwitterModoki\/friends\/follow"
                });

            }
            return false;
        });     

        //フォロ－解除をおした時
        $(document).on("click","#remove_button", function () {
            // 確認ダイアログを表示
            var res = confirm("フォロー解除しますか？");
            // 選択結果で分岐
            if( res == true ) {
                var self=this;
                $.ajax({ 
                    //data:$("#button_2").closest("form").serialize(), 
                    data: { remove_id : $(this).data("userid")},
                    dataType:"json", 
                    success:function (result, textStatus) {
                        if(result.error){
                            alert("エラー：リムーブに失敗しました")
                        }
                        //自画面遷移
                        location.href = $(self).data("url");
                    },
                    error: function(xhr, textStatus, errorThrown){
                        alert("エラー：もう一度試してください。");
                    },
                    type:"post",
                    url:"\/TwitterModoki\/friends\/remove"
                });

            }
            return false;
        }); 
    /*
        //友達検索結果で、フォローをおした時
        $(document).on("click","#follow_button", function () {
            // 確認ダイアログを表示
            var res = confirm("フォローしますか？");
            // 選択結果で分岐
            if( res == true ) {
                var self=this;
                $.ajax({ 
                    //data:$("#button_2").closest("form").serialize(), 
                    data: { follow_id : $(this).data("userid")},
                    dataType:"html", 
                    success:function (data, textStatus) {
                        $(self).attr("value","フォロー解除");
                        $(self).attr("id","remove_button");
                    },
                    error: function(xhr, textStatus, errorThrown){
                        alert("エラー：もう一度試してください。");
                    },
                    type:"post",
                    url:"\/TwitterModoki\/friends\/follow"
                });

            }
            return false;
        }); 
        
        //友達検索結果で、フォロ－解除をおした時
        $(document).on("click","#remove_button", function () {
            // 確認ダイアログを表示
            var res = confirm("フォロー解除しますか？");
            // 選択結果で分岐
            if( res == true ) {
                var self=this;
                $.ajax({ 
                    //data:$("#button_2").closest("form").serialize(), 
                    data: { remove_id : $(this).data("userid")},
                    dataType:"html", 
                    success:function (data, textStatus) {
                        $(self).attr("value","フォロー");
                        $(self).attr("id","follow_button");
                    },
                    error: function(xhr, textStatus, errorThrown){
                        alert("エラー：もう一度試してください。");
                    },
                    type:"post",
                    url:"\/TwitterModoki\/friends\/remove"
                });

            }
            return false;
        });
    */


    
});