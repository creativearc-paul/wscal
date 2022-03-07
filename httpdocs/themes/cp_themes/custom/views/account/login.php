<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <?=$this->view->head_title($cp_page_title)?>
    <?=$this->view->head_link('css/login.css'); ?>
    <?=$this->view->script_tag('jquery/jquery.js')?>

</head>
<body>

    <div id="content">
    
        <?php if ($message != ''):?>
            <div class="error">
                <p><?=$message?></p>
            </div>
        <?php endif;?>
        <img src="/assets/graphics/logo.png" alt="" style="margin: 0 0 20px 0;">
        <h2 style="color: #ffffff;margin: 0 0 20px 0;font-weight: normal;">Please login to continue.</h2>
        <?=form_open('C=login'.AMP.'M=authenticate', array(), array('return_path' => $return_path))?>

        
        <dl>
            <dt><?=lang('username', 'username')?>:</dt>
            <dd>
                <?=form_input(array('style' => 'width:100%', 'size' => '35', 'dir' => 'ltr', 'name' => "username", 'id' => "username", 'value' => $username, 'maxlength' => 50))?>
            </dd>
            <dt><?=lang('password', 'password')?>:</dt>
            <dd>
                <?=form_password(array('style' => 'width:100%', 'size' => '32', 'dir' => 'ltr', 'name' => "password", 'id' => "password", 'maxlength' => 40, 'autocomplete' => 'off'))?> 
            </dd>
        </dl>

        <script>
            document.getElementById('<?=$focus_field?>').focus();
        </script>

        <p><?=form_submit('submit', lang('login'), 'class="submit"')?> <span><a href='<?=BASE.AMP?>C=login&amp;M=forgotten_password_form'><?=lang('forgot_password')?></a></span></p>

        <?=form_close()?>

    </div>
    <?php
    if (isset($script_foot)){
        echo $script_foot;
    }
    ?>

</body>
</html>
