<?php $this->assign('title','ユーザ登録'); ?>
<?php
    echo $this->Html->script('jquery-2.1.4.min');
    echo $this->Html->script('CheckInput.js');
?>
<div id='regist_mainarea'>
    <div id='title'>ついったーに参加しましょう</div>
    <div id='already'>もうついったーに登録していますか？
    <?php echo $this->Html->link('ログイン', '/users/login',array('class'=>'linkstyle')); ?>
    </div>
    <?php echo $this->Form->create('User',array('id'=>'regist_checkinput','novalidate'=>true)); ?>

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
    echo $this->Form->input('name',array(
            'div'=>false,
            'before'=>'<p>',
            'after'=>'</p>',
            'label'=>array('text'=>'名前','class'=>'input_label'),
            'error'=>false
         ));
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
    echo $this->Form->input('password_confirmation',array(
            'type'=>'password',
            'div'=>false,
            'before'=>'<p>',
            'after'=>'</p>',
            'label'=>array('text'=>'パスワード（確認）','class'=>'input_label'),
            'error'=>false
         ));
    echo $this->Form->input('mail',array(
            'div'=>false,
            'before'=>'<p>',
            'after'=>'</p>',
            'label'=>array('text'=>'メールアドレス','class'=>'input_label'),
            'error'=>false
         ));
    echo $this->Form->input('private',array(
            'type'=>'checkbox',
            'label'=>array('text'=>'つぶやきを非公開にする','class'=>'checkbox-inline')
         ));
    ?>
    </div>
    <div class = 'form_button'>
    <?php echo $this->Form->end('アカウントを作成する'); ?>
    </div>
</div>
