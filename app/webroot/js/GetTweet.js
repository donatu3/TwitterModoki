$(function(){
    setInterval(function(){
        var new_id;
        var flag = 0;
        new_id = $("#mainarea #sr_tweet").first().data("tweetid");
        if(new_id === undefined){
            flag = 1;
            new_id = 0;
        }else{
            flag = 0;
        }
        $.ajax({ 
            data: { username : session_username,
                    id : new_id
                  },
            dataType:"html", 
            success:function (result, textStatus) {
                if(flag == 1 && result !== ""){
                    $("#mainarea").text('');
                }
                $(result).prependTo("#mainarea").hide().fadeIn("slow");
            },
            error: function(xhr, textStatus, errorThrown){
                alert("エラー" + textStatus + xhr + errorThrown);
            },
            type:"post",
            url:"\/TwitterModoki\/tweets\/newtweet"
        });
    },10000);
    
    setInterval(function(){
        $.ajax({ 
            data: { username : session_username,
                  },
            dataType:"html", 
            success:function (result, textStatus) {
                $("#recent_tweet").html(result).hide().fadeIn("slow");
            },
            error: function(xhr, textStatus, errorThrown){
                alert("エラー" + textStatus + xhr + errorThrown);
            },
            type:"post",
            url:"\/TwitterModoki\/tweets\/recenttweet"
        });
    },10000);    

    $(window).on("scroll", function() {
        var scrollHeight = $(document).height();
        var scrollPosition = $(window).height() + $(window).scrollTop();
        var new_id;
        var flag = 0;
        new_id = $("#mainarea #sr_tweet").last().data("tweetid");
        if(new_id === undefined){
            flag = 1;
            new_id = 0;
        }else{
            flag = 0;
        }
        if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
            $.ajax({ 
                data: { username : session_username,
                        id : $("#mainarea #sr_tweet").last().data("tweetid")
                      },
                dataType:"html", 
                success:function (result, textStatus) {
                    if(flag == 1 && result !== ""){
                        $("#mainarea").text('');
                    }
                    $(result).appendTo("#mainarea").hide().fadeIn("slow");
                },
                error: function(xhr, textStatus, errorThrown){
                    alert("エラー" + textStatus + xhr + errorThrown);
                },
                type:"post",
                url:"\/TwitterModoki\/tweets\/pasttweet"
            });
        }
    });
    

});