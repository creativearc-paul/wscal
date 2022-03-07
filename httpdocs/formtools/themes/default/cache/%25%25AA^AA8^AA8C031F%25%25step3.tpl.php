<?php /* Smarty version 2.6.18, created on 2010-12-01 16:31:34
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/install/templates/step3.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../install/templates/install_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <h1><?php echo $this->_tpl_vars['LANG']['phrase_create_database_tables']; ?>
</h1>

  <div>
    <?php echo $this->_tpl_vars['LANG']['text_install_create_database_tables']; ?>

  </div>

    <?php if ($this->_tpl_vars['error'] != ""): ?>

     <div class="error" style="padding: 5px; margin-top: 8px">
      <?php echo $this->_tpl_vars['LANG']['phrase_error_occurred_c']; ?>
<br />
      <br />
      <div class="red"><?php echo $this->_tpl_vars['error']; ?>
</div>
      <br/>
      <?php echo $this->_tpl_vars['LANG']['phrase_check_db_settings_try_again']; ?>

    </div>

    <p><b><?php echo $this->_tpl_vars['LANG']['word_tips']; ?>
</b></p>

    <ul class="tips">
      <li><div><?php echo $this->_tpl_vars['LANG']['text_install_db_tables_error_tip_1']; ?>
</div></li>
      <li><div><?php echo $this->_tpl_vars['LANG']['text_install_db_tables_error_tip_2']; ?>
</div></li>
      <li><div><?php echo $this->_tpl_vars['LANG']['text_install_db_tables_error_tip_3']; ?>
</div></li>
      <li><div><?php echo $this->_tpl_vars['LANG']['text_install_db_tables_error_tip_4']; ?>
</div></li>
    </ul>

  <?php endif; ?>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'messages.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <form name="db_settings_form" action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post" onsubmit="return rsv.validate(this, rules);">

    <p><b><?php echo $this->_tpl_vars['LANG']['phrase_database_settings']; ?>
</b></p>

    <table cellpadding="1" cellspacing="0">
    <tr>
      <td class="label" width="140"><?php echo $this->_tpl_vars['LANG']['phrase_database_hostname']; ?>
</td>
      <td><input type="text" size="20" name="g_db_hostname" value="<?php echo $this->_tpl_vars['g_db_hostname']; ?>
" /> <?php echo $this->_tpl_vars['LANG']['phrase_often_localhost']; ?>
</td>
    </tr>
    <tr>
      <td class="label"><?php echo $this->_tpl_vars['LANG']['phrase_database_name']; ?>
</td>
      <td><input type="text" size="20" name="g_db_name" value="<?php echo $this->_tpl_vars['g_db_name']; ?>
" /></td>
    </tr>
    <tr>
      <td class="label"><?php echo $this->_tpl_vars['LANG']['phrase_database_username']; ?>
</td>
      <td><input type="text" size="20" name="g_db_username" value="<?php echo $this->_tpl_vars['g_db_username']; ?>
" /></td>
    </tr>
    <tr>
      <td class="label"><?php echo $this->_tpl_vars['LANG']['phrase_database_password']; ?>
</td>
      <td><input type="text" size="20" name="g_db_password" value="<?php echo $this->_tpl_vars['g_db_password']; ?>
" /></td>
    </tr>
    <tr>
      <td class="label"><?php echo $this->_tpl_vars['LANG']['phrase_database_table_prefix']; ?>
</td>
      <td><input type="text" size="20" name="g_table_prefix" value="<?php echo $this->_tpl_vars['g_table_prefix']; ?>
" /></td>
    </tr>
    </table>

    <p>
      <input type="submit" name="create_database" value="<?php echo $this->_tpl_vars['LANG']['phrase_create_database_tables']; ?>
" />
    </p>
  </form>

  <script type="text/javascript">
  document.db_settings_form.g_db_hostname.focus();
  </script>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../install/templates/install_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>