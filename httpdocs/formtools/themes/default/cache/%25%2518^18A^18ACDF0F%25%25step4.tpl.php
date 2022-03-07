<?php /* Smarty version 2.6.18, created on 2010-12-01 16:31:54
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/install/templates/step4.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../install/templates/install_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <h1><?php echo $this->_tpl_vars['LANG']['phrase_create_config_file']; ?>
</h1>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'messages.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <?php if ($this->_tpl_vars['config_file_generated'] === ""): ?>

    <div class="margin_bottom_large">
      <?php echo $this->_tpl_vars['LANG']['text_install_create_config_file']; ?>

    </div>

    <textarea name="content" style="width:100%; height:240px;" readonly><?php echo $this->_tpl_vars['config_file']; ?>
</textarea>

    <form name="display_config_content_form" action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post">
      <p>
        <input type="submit" name="generate_file" value="<?php echo $this->_tpl_vars['LANG']['phrase_create_file']; ?>
" />
      </p>
    </form>

  <?php elseif ($this->_tpl_vars['config_file_generated'] === true): ?>

    <div class="margin_bottom_large notify">
      <?php echo $this->_tpl_vars['LANG']['text_config_file_created']; ?>

    </div>

    <form action="step5.php" method="post">
      <p>
        <input type="submit" name="next" value="<?php echo $this->_tpl_vars['LANG']['word_continue_rightarrow']; ?>
" />
      </p>
    </form>

  <?php elseif ($this->_tpl_vars['config_file_generated'] === false): ?>

  <div class="margin_bottom_large notify">
      <?php echo $this->_tpl_vars['LANG']['text_config_file_not_created']; ?>

    </div>
    <p>
      <?php echo $this->_tpl_vars['LANG']['text_config_file_not_created_instructions']; ?>

    </p>

    <form name="display_config_content_form" action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post">
      <textarea name="content" style="width:100%; height:240px;"><?php echo $this->_tpl_vars['config_file']; ?>
</textarea>

      <p>
        <input type="submit" name="check_config_contents" value="<?php echo $this->_tpl_vars['LANG']['word_continue_rightarrow']; ?>
" />
      </p>
    </form>

  <?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../install/templates/install_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>