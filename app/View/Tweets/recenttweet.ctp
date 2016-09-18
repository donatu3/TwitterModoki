<?php
    echo "<div class='content'>";
    echo "<div id='sr_tweet'>";
    //最新ツイート（リンクを反映させる）
    /*リンク*/
    $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
    // 置換後の文字列
    $replacement = '<a href="\1">\1</a>';
    // 置換
    $new_tweet= preg_replace($pattern,$replacement,h($recent['tweet']));
    print("最新のつぶやき：<br>".$new_tweet);
    echo "</div>";
    if($recent['tweet_time'] != null){
        echo "<div id='sr_time'>";
        //ツイート時間
        echo date("Y年m月d日 H時i分s秒", strtotime($recent['tweet_time']));
        echo "</div>";
    }
    echo "</div>";