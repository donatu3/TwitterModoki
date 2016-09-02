<?php $this->assign('title','友達検索結果'); ?>
<?php
echo $this->Html->script('jquery-2.1.4.min');
echo $this->Html->script('Follow.js');
?>
<div><?php print(h($search_text)); ?>の検索結果：</div>
<?php 
    echo $this->Form->create('Friend',array('div'=>false,'novalidate'=>true));
    echo $this->Form->input('search_id',array('div'=>false,'label'=>false,'type'=>'text'));
    echo $this->Form->end(array('label'=>'検索する','div'=>false,'id'=>'search_button'));
?>
<div>ユーザー名や名前で検索</div>
<div id='mainarea'>
<?php 
//検索結果の表示
if(!empty($search_result)){
    //現在のページ番号を取得しておく
    $current_page = $this->Paginator->current();
    //誰かいる
    foreach($search_result as $val){
        echo "<div class='tweet_area'>";
        echo "<div class='content'>";
        echo "<span id='sr_username'>";
        //ユーザID
        echo $this->Html->link(h($val['User']['username']),'/tweets/home/'.h($val['User']['username']).'/only',array('class'=>'linkstyle'));
        echo "</span>";
        echo "<span id='sr_name'>";
        //ユーザ名
        echo '：'.h($val['User']['name']);
        echo "</span>";
        echo "<div id='sr_tweet'>";
        //最新ツイート（リンクを反映させる）
        /*リンク*/
        $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
        // 置換後の文字列
        $replacement = '<a href="\1">\1</a>';
        // 置換
        $new_tweet= preg_replace($pattern,$replacement,h($val['User']['tweet']));
        print($new_tweet);
        echo "</div>";
        if($val['User']['tweet_time'] != null){
            echo "<div id='sr_time'>";
            //ツイート時間
            echo date("Y年m月d日 H時i分s秒", strtotime($val['User']['tweet_time']));
            echo "</div>";
        }
        echo "</div>";
        //フォロー、フォロー解除ボタン
        //押した時の自画面遷移のためのURLを生成する
        $next_url = "http://donatu33.sakura.ne.jp/TwitterModoki/friends/search_result/".$search_text.'/page:'.$current_page;
        echo "<div id='sr_button'>";
        if($val['User']['follow'] == 1){
            //echo $this->form->submit("フォロー解除",array('id'=>'remove_button','data-userid'=>h($val['User']['username'])));
?>
    <button id='remove_button' data-userid="<?php echo $val['User']['username']; ?>" data-url="<?php echo $next_url; ?>">フォロー解除</button>
<?php
        }else{
            //echo $this->form->submit("フォロー",array('id'=>'follow_button','data-userid'=>h($val['User']['username'])));
?>
    <button id='follow_button' data-userid="<?php echo $val['User']['username']; ?>" data-url="<?php echo $next_url; ?>">フォロー</button>
<?php
        }
        echo "</div>";
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
}else{
    //誰もいない
?>

<div class='user_not_found'>
<?php
    echo "対象のユーザはみつかりません";
}
?>
</div>
</div>