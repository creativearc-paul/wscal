<?php /* Smarty version 2.6.18, created on 2015-09-17 12:23:21
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/classicgrey/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/classicgrey/index.tpl', 3, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/classicgrey/index.tpl', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <div class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['login_heading'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

  <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


  <div class="margin_bottom_large" style="width: 540px">
    <?php echo $this->_tpl_vars['text_login']; ?>

  </div>

  <form name="login" action="<?php echo $this->_tpl_vars['same_page']; ?>
<?php echo $this->_tpl_vars['query_params']; ?>
" method="post">

    <?php if ($this->_tpl_vars['upgrade_notification']): ?>
      <div class="notify">
        <?php echo $this->_tpl_vars['upgrade_notification']; ?>

      </div>
      <br />
    <?php endif; ?>

    <table width="320" cellpadding="1" class="login_outer_table">
    <tr>
      <td colspan="1">

        <table width="100%" cellpadding="0" cellspacing="1" class="login_inner_table">
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td>

            <table width="200" cellpadding="0" cellspacing="1">
            <tr>
              <td class="login_table_text"><?php echo $this->_tpl_vars['LANG']['word_username']; ?>
</td>
              <td><input type="text" size="25" name="username" value="<?php echo $this->_tpl_vars['username']; ?>
"></td>
            </tr>
            <tr>
              <td class="login_table_text"><?php echo $this->_tpl_vars['LANG']['word_password']; ?>
</td>
              <td><input type="password" size="25" name="password" value=""></td>
            </tr>
            </table>

          </td>
          <td align="center" valign="center">

            <script type="text/javascript">
            document.write('<input type="submit" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_log_in'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
">&nbsp;');
            </script>

          </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        </table>

      </td>
    </tr>

    <?php if ($this->_tpl_vars['error']): ?>
    <tr>
      <td colspan="3">
        <div class="login_error pad_left"><?php echo $this->_tpl_vars['error']; ?>
</div>
      </td>
    </tr>
    <?php endif; ?>

    </table>

  </form>

  <noscript>
    <div class="error" style="padding:6px;">
    <?php echo $this->_tpl_vars['LANG']['text_js_required']; ?>

    </div>
  </noscript>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>