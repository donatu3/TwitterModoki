<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
        /*自分で作ったcss*/
		echo $this->Html->css('Main');
        /*twitter bootstrap用*/
        //echo $this->Html->css('bootstrap.min');
        //echo $this->Html->css('bootstrap-responsive.min');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
            <?php
                if($this->Session->check('userid')):
            ?>
                    <script type="text/javascript">
                     <!--
                    var session_username = "<?php echo $this->Session->read('userid'); ?>";
                    -->
                     </script>
            <?php
                    echo $this->Html->image('twitterlogo.png', array('alt' => 'TeitterModoki','url'=>'/tweets/home/'.h($this->Session->read('userid'))));
                else:
                    echo $this->Html->image('twitterlogo.png', array('alt' => 'TeitterModoki','url'=>'/users/'));
                endif;
            ?>
            <div id="header_menu">
                <?php
                    if($this->Session->check('userid')):
                        //ログインしてる場合のヘッダーメニュー
                        echo $this->Html->link('ホーム','/tweets/home/'.h($this->Session->read('userid')),array('class'=>'linkstyle'));
                        echo "　";
                        echo $this->Html->link('友達検索','/friends/search',array('class'=>'linkstyle'));
                        echo "　";
                        echo $this->Html->link('ログアウト', '/users/logout',array('class'=>'linkstyle'));
                    else:
                        //ログインしていない場合のヘッダーメニュー
                        echo $this->Html->link('ホーム','/users/login',array('class'=>'linkstyle'));
                        echo "　";
                        echo $this->Html->link('ユーザ登録', '/users/register',array('class'=>'linkstyle'));
                        echo "　";
                        echo $this->Html->link('ログイン', '/users/login',array('class'=>'linkstyle'));
                    endif;
                ?>
            </div>
		</div>
		<div id="content">
            <?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>
