<?php
if($error == false){
    foreach($search_result as $val){
        echo "<div class='tweet_area'>";
        echo "<div class='content'>";
        echo "<span id='sr_username'>";
        //ユーザID
        echo $this->Html->link(h($val['Tweet']['username']),'/tweets/home/'.h($val['Tweet']['username']).'/only',array('class'=>'linkstyle'));
        echo "</span>";
        echo "<div id='sr_tweet' data-tweetid='".$val['Tweet']['tweet_id']."'>";
        //ツイート（リンクと改行を反映させる）
        /*リンク*/
        $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
        // 置換後の文字列
        $replacement = '<a href="\1">\1</a>';
        // 置換
        $new_tweet= preg_replace($pattern,$replacement,h($val['Tweet']['message']));
        print($new_tweet);
        echo "</div>";
        echo "<div id='sr_time'>";
        //ツイート時間
        echo date("Y年m月d日 H時i分s秒", strtotime($val['Tweet']['created']));
        echo "</div>";
        echo "</div>";
        if($val['Tweet']['username'] == $userid){
            echo "<div id='sr_button'>";
            //ツイートが自分のものならば、削除ボタンを表示する
            //押した時の自画面遷移のためのURLを生成する
            //自分のホーム
            $next_url = "http://donatu33.sakura.ne.jp/TwitterModoki/tweets/home/".$userid;
            ?>
            <button id='delete_button' data-deleteid="<?php echo $val['Tweet']['tweet_id']; ?>" data-url="<?php echo $next_url; ?>">delete</button>
            <?php
            echo "</div>";
        }
        echo "</div>";
    }
}