<?php $this->assign('title','ホーム'); ?>
<?php
echo $this->Html->script('jquery-2.1.4.min');
echo $this->Html->script('DeleteTweet.js');
echo $this->Html->script('submit.js');
?>

<?php
if($home_id == $userid && $only == null){
    //ログインしてるユーザのホーム画面(投稿フォームを作る)
    echo "<div id=login_user_form>";
    echo "<div id='tweet_massage_box'>";
    echo "いまなにしてる？";
    //エラー表示（140文字）
    foreach($valerror as $key1 => $val1){
        foreach($val1 as $key2 => $val2){
?>
    <div class='red'>
        <?php echo $val2; ?>
    </div>
<?php
        }
    }
    echo $this->Form->create('Tweet');

    echo $this->Form->input('message',array('label'=>false,'value' => '','placeholder'=>'140字以内で入力してください','error'=>false));
    echo "</div>";
    echo "<div class='tweet_area'>";
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
    echo $this->Form->submit('投稿する',array('id'=>'toukou_button','class'=>'submit_button'));
    echo $this->Form->end();
    echo "</div>";
    echo "</div>";
    
}
?>
<div id='mainarea'>
<?php 
    if(!empty($search_result)){
        //現在のページ番号を取得しておく
        $current_page = $this->Paginator->current();
        foreach($search_result as $val){
            echo "<div class='tweet_area'>";
            echo "<div class='content'>";
            echo "<span id='sr_username'>";
            //ユーザID
            echo $this->Html->link(h($val['Tweet']['username']),'/tweets/home/'.h($val['Tweet']['username']).'/only',array('class'=>'linkstyle'));
            echo "</span>";
            echo "<div id='sr_tweet'>";
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
            if($val['Tweet']['username'] == $this->Session->read('userid')){
                echo "<div id='sr_button'>";
                //ツイートが自分のものならば、削除ボタンを表示する
                //押した時の自画面遷移のためのURLを生成する
                if($this->Paginator->param('current') == 1 && $this->Paginator->param('page') != 1){
                    //表示件数が1 かつ ページが1じゃない時　1つ前のページに戻す
                    $current_page = $current_page - 1;
                }
                if(!empty($only)){
                    //自分のツイートだけのページ
                    $next_url = "http://donatu33.sakura.ne.jp/TwitterModoki/tweets/home/".$this->Session->read('userid').'/'.$only.'/'.'page:'.$current_page;
                }else{
                    //自分のホーム
                    $next_url = "http://donatu33.sakura.ne.jp/TwitterModoki/tweets/home/".$this->Session->read('userid').'/'.'page:'.$current_page;
                }

                ?>
                <button id='delete_button' data-deleteid="<?php echo $val['Tweet']['tweet_id']; ?>" data-url="<?php echo $next_url; ?>">delete</button>
                <?php
                echo "</div>";
            }
            echo "</div>";
        }
        //ページングの表示
        echo "<div id='page_link'>";
        if($this->Paginator->hasPrev()){
            echo $this->Paginator->prev('<< 前へ', array('tag' => false), null, array('class' => 'prev'));
        }
        //ページ番号のリスト表示　未使用
        //echo $this->Paginator->numbers($options = array('tag' => false,'separator' => ' '));
        if($this->Paginator->hasNext()){
            echo $this->Paginator->next('次へ >>', array('tag' => false), null, array('class' => 'next'));
        }
        echo "</div>";

    }else{
        echo "データがありません";
    }
?>

</div>

<div class='user_info'>
<?php
    echo "<div class='user_info_name'>";
    echo "名前：";
    echo $home_id;
    echo "</div>";
    echo "<div class='user_info_following'>";
    echo $user_info_following."<br>";
    echo "<a href='/TwitterModoki/friends/table/".$home_id."/following/' class='linkstyle'>フォロー<br>している</a>";
    echo "</div>";
    echo "<div class='user_info_followers'>";
    echo $user_info_followers."<br>";
    echo "<a href='/TwitterModoki/friends/table/".$home_id."/followers/' class='linkstyle'>フォロー<br>されている</a>";
    echo "</div>";
    echo "<div class='user_info_tweet'>";
    echo $user_info_tweet."<br>";
    echo $this->Html->link('投稿数','/tweets/home/'.$home_id.'/only/',array('class'=>'linkstyle'));
    echo "</div>";
?>
</div>