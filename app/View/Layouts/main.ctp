<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title><?php echo $title_for_layout; ?> / ログインするだけのサイト</title>
        <?php echo $this->Html->css('main'); ?> 
        <?php echo $this->fetch('script'); ?>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="header_menu">
                    <?php
                        if(isset($user)):
                            echo $this->Html->link('ログアウト', '/users/logout');
                        else:
                            echo $this->Html->link('ログイン　', '/users/login');
                            echo $this->Html->link('新規登録', '/users/register');
                        endif;
                    ?>
                </div>
                <div id="header_logo">
                    <h1>ログインするだけのサイト</h1>
                </div>
                <div id="content">
                    <?php echo $this->fetch('content'); ?>
                    
                </div>
            </div>
        </div>
    </body>
</html>
