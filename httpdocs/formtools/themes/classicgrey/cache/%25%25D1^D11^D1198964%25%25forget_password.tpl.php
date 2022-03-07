<?php /* Smarty version 2.6.18, created on 2016-02-10 14:45:45
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/classicgrey/forget_password.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/classicgrey/forget_password.tpl', 3, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/classicgrey/forget_password.tpl', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <div class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_forgot_password'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

  <?php echo smarty_function_ft_include(array('file' => 'messages.tpl'), $this);?>


  <div class="margin_bottom_large" style="width: 540px">
    <?php echo $this->_tpl_vars['text_forgot_password']; ?>

  </div>

  <form name="forget_password" action="<?php echo $this->_tpl_vars['same_page']; ?>
<?php echo $this->_tpl_vars['g_query_params']; ?>
" method="post"
    onsubmit="return rsv.validate(this, rules)">

  <table width="320" cellpadding="1" class="login_outer_table">
  <tr>
    <td colspan="1">

      <table width="100%" cellpadding="0" cellspacing="1" class="login_inner_table">
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class="login_table_text"><?php echo $this->_tpl_vars['LANG']['word_username']; ?>
</td>
        <td><input type="textbox" size="25" name="username" value="<?php echo $this->_tpl_vars['username']; ?>
"></td>
        <td align='center'><input type="submit" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_email'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      </table>

    </td>
  </tr>
  </table>

  </form>

  <p>
    <a href="index.php<?php echo $this->_tpl_vars['query_params']; ?>
"><?php echo $this->_tpl_vars['LANG']['phrase_login_panel_leftarrows']; ?>
</a>
  </p>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>