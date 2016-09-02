<?php $this->assign('title','友達検索'); ?>
<div id='search'>
<div id='title'>友だちを見つけて、フォローしましょう！</div>
<div>ついったーに登録済みの友達を検索できます。</div>
誰を検索しますか？
<?php 
    echo $this->Form->create('Friend',array('div'=>false,'novalidate'=>true));
    echo $this->Form->input('search_id',array('div'=>false,'label'=>false,'type'=>'text'));
    echo $this->Form->end(array('label'=>'検索する','div'=>false,'id'=>'search_button'));
?>
ユーザー名や名前で検索
</div>
