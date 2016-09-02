<?php
$this->assign('title','一覧表示');
echo $this->Html->script('jquery-2.1.4.min');
echo $this->Html->script('Follow.js');
echo $this->Html->script('UserInfo.js');
?>
<?php
if($menu == 'following'){
?>
<div><?php echo $target_id; ?>は<?php echo $user_info_following; ?>人をフォローしています</div>
<?php
}else{
?>
<div><?php echo $target_id; ?>は<?php echo $user_info_followers; ?>人にフォローされています</div>
<?php
}

echo "<div id='mainarea'>";
//検索結果の表示
if(!empty($search_result)){
    //現在のページ番号を取得しておく
    $current_page = $this->Paginator->current();

    foreach($search_result as $val){
        echo "<div class='tweet_area'>";
        echo "<div class='content'>";
        echo "<span id='sr_username'>";
        //ユーザID
        if($menu == 'following'){
            echo $this->Html->link(h($val['Friend']['followID']),'/tweets/home/'.h($val['Friend']['followID']).'/only',array('class'=>'linkstyle'));
        }else{
            echo $this->Html->link(h($val['Friend']['username']),'/tweets/home/'.h($val['Friend']['username']).'/only',array('class'=>'linkstyle'));
        }
        echo "</span>";
        echo "<span id='sr_name'>";
        //ユーザ名
        echo '：'.h($val['Friend']['name']);
        echo "</span>";
        //最新ツイート（改行を反映させる）
        echo "<div id='sr_tweet'>";
        //最新ツイート（リンクと改行を反映させる）
        /*リンク*/
        $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
        // 置換後の文字列
        $replacement = '<a href="\1">\1</a>';
        // 置換
        $new_tweet= preg_replace($pattern,$replacement,h($val['Friend']['tweet']));
        print($new_tweet);
        echo "</div>";
        if($val['Friend']['tweet_time'] != null){
            echo "<div id='sr_time'>";
            //ツイート時間
            echo date("Y年m月d日 H時i分s秒", strtotime($val['Friend']['tweet_time']));
            echo "</div>";
        }
        echo "</div>";
        //フォロー、フォロー解除ボタン（表示しているのが自分のもののときだけ）
        if($target_id == $this->Session->read('userid')){
            echo "<div id='sr_button'>";
            //押した時の自画面遷移のためのURLを生成する
            if($val['Friend']['follow'] == 1){
                if($this->Paginator->param('current') == 1 && $this->Paginator->param('page') != 1 && $menu == 'following'){
                    //表示件数が1 かつ ページが1じゃない　かつ　フォローを見ていて、それを解除する時　1つ前のページに戻す
                    $current_page = $current_page - 1;
                }
                $next_url = "http://donatu33.sakura.ne.jp/TwitterModoki/friends/table/".$this->Session->read('userid').'/'.$menu.'/'.'page:'.$current_page;
                if($menu == 'following'){
?>
    <button id='remove_button' data-userid="<?php echo $val['Friend']['followID']; ?>" data-url="<?php echo $next_url; ?>">フォロー解除</button>

<?php
                }else{
?>
    <button id='remove_button' data-userid="<?php echo $val['Friend']['username']; ?>" data-url="<?php echo $next_url; ?>">フォロー解除</button>
<?php
                }

            }else{
                $next_url = "http://donatu33.sakura.ne.jp/TwitterModoki/friends/table/".$this->Session->read('userid').'/'.$menu.'/'.'page:'.$current_page;
                if($menu == 'following'){
?>
    <button id='follow_button' data-userid="<?php echo $val['Friend']['followID']; ?>" data-url="<?php echo $next_url; ?>">フォロー</button>
<?php
                }else{
?>
    <button id='follow_button' data-userid="<?php echo $val['Friend']['username']; ?>" data-url="<?php echo $next_url; ?>">フォロー</button>
<?php
                }
            }
            echo "</div>";
        }
        echo "</div>";
    }

    echo "<div id='page_link'>";
    //ページングの表示
    if($this->Paginator->hasPrev()){
        echo $this->Paginator->prev('<< 前へ', array('tag' => false), null, array('class' => 'prev'));
    }
    //ページ番号のリスト表示　未使用
    //echo $this->Paginator->numbers($options = array('tag' => false,'separator' => ' '));
    if($this->Paginator->hasNext()){
        echo $this->Paginator->next('次へ >>', array('tag' => false), null, array('class' => 'next'));
    }
    echo "</div>";

}
echo "</div>";
?>

<div class='user_info'>
<?php
    echo "<div class='user_info_name'>";
    echo "名前：";
    echo $target_id;
    echo "</div>";
    echo "<div class='user_info_following'>";
    echo $user_info_following."<br>";
    echo "<a href='/TwitterModoki/friends/table/".$target_id."/following/' class='linkstyle'>フォロー<br>している</a>";
    echo "</div>";
    echo "<div class='user_info_followers'>";
    echo $user_info_followers."<br>";
    echo "<a href='/TwitterModoki/friends/table/".$target_id."/followers/' class='linkstyle'>フォロー<br>されている</a>";
    echo "</div>";
    echo "<div class='user_info_tweet'>";
    echo $user_info_tweet."<br>";
    echo $this->Html->link('投稿数','/tweets/home/'.$target_id.'/only/',array('class'=>'linkstyle'));
    echo "</div>";
?>
</div>