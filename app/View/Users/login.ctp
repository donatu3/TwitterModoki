<?php $this->assign('title','ログイン'); ?>
<?php
    echo $this->Html->script('jquery-2.1.4.min');
    echo $this->Html->script('CheckInput.js');
    echo $this->Html->script('Regist.js');
?>
<div id='user_regist'>
    <div class='title'>ユーザ登録（無料）</div>
    <button id='regist_button' data-url="http://donatu33.sakura.ne.jp/TwitterModoki/users/register">ユーザ登録</button>
</div>
<div id='login_mainarea'>
    <div id='title'>ログイン</div>
    <?php echo $this->Form->create('User',array('id'=>'login_checkinput','novalidate'=>true)); ?>
    <div id='input_error_massage'>
    <?php
    /*エラー表示開始*/
    foreach($valerror as $key1 => $val1){
        foreach($val1 as $key2 => $val2){
            echo $val2."<br />";
        }
    }
    ?>
    </div>
    <div class = 'form_style'>
    <?php
    echo $this->Form->input('username',array(
            'div'=>false,
            'before'=>'<p>',
            'after'=>'</p>',
            'label'=>array('text'=>'ユーザID','class'=>'input_label'),
            'error'=>false
         ));
    echo $this->Form->input('password',array(
            'div'=>false,
            'before'=>'<p>',
            'after'=>'</p>',
            'label'=>array('text'=>'パスワード','class'=>'input_label'),
            'error'=>false
         ));
    ?>
    </div>
    <div class = 'form_button'>
    <?php echo $this->Form->end('ログイン'); ?>
    </div>
</div>
