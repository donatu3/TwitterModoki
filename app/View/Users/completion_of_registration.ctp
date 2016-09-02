<?php $this->assign('title','登録完了'); ?>
<?php
echo $this->Html->script('jquery-2.1.4.min');
echo $this->Html->script('Login.js');
?>
<div id=regist_conp>
    <div id='title'>ついったーに参加しました。</div>
    <div>
        <?php print(h($user['username'])); ?>さんはついったーに参加されました。</div>
    <div>ログインをクリックしてつぶやいてください。</div>
    <div id=login_button_style>
        <button id='login_button' data-url='http://donatu33.sakura.ne.jp/TwitterModoki/users/login'>twitterにログイン</button>
    </div>
</div>