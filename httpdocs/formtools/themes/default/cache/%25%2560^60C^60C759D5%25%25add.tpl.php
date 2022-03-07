<?php /* Smarty version 2.6.18, created on 2010-12-15 09:43:58
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/add.tpl', 1, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/add.tpl', 32, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/add.tpl', 7, false),array('modifier', 'in_array', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/add.tpl', 23, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/add.tpl', 37, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="./"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_accounts.gif" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_add_client'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

    </td>
  </tr>
  </table>

  <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


  <div class="pad_bottom_large">
    <?php echo $this->_tpl_vars['LANG']['text_create_new_client_account']; ?>

  </div>

  <?php if ($this->_tpl_vars['has_extra_password_requirements']): ?>
  <div class="grey_box margin_bottom_large">
    <?php echo $this->_tpl_vars['LANG']['phrase_password_requirements_c']; ?>

    <ul class="margin_bottom_small margin_top_small">
      <?php if ($this->_tpl_vars['has_min_password_length']): ?><li><?php echo $this->_tpl_vars['phrase_password_min']; ?>
</li><?php endif; ?>
      <?php if (((is_array($_tmp='uppercase')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['required_password_chars']) : in_array($_tmp, $this->_tpl_vars['required_password_chars']))): ?><li><?php echo $this->_tpl_vars['LANG']['phrase_password_one_uppercase']; ?>
</li><?php endif; ?>
      <?php if (((is_array($_tmp='number')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['required_password_chars']) : in_array($_tmp, $this->_tpl_vars['required_password_chars']))): ?><li><?php echo $this->_tpl_vars['LANG']['phrase_password_one_number']; ?>
</li><?php endif; ?>
      <?php if (((is_array($_tmp='special_char')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['required_password_chars']) : in_array($_tmp, $this->_tpl_vars['required_password_chars']))): ?><li><?php echo $this->_tpl_vars['password_special_char']; ?>
</li><?php endif; ?>
    </ul>
  </div>
  <?php endif; ?>

  <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" id="add_client_form" method="post" autocomplete="off" onsubmit="return rsv.validate(this, rules)">

    <?php echo smarty_function_template_hook(array('location' => 'admin_add_client_top'), $this);?>


    <table cellpadding="0" cellspacing="1">
    <tr>
      <td class="medium_grey" width="130"><?php echo $this->_tpl_vars['LANG']['phrase_first_name']; ?>
</td>
      <td><input type="text" name="first_name" id="first_name" style="width: 150px" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['vals']['first_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
    </tr>
    <tr>
      <td class="medium_grey"><?php echo $this->_tpl_vars['LANG']['phrase_last_name']; ?>
</td>
      <td><input type="text" name="last_name" style="width: 150px" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['vals']['last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
    </tr>
    <tr>
      <td class="medium_grey"><?php echo $this->_tpl_vars['LANG']['word_email']; ?>
</td>
      <td><input type="text" name="email" style="width: 150px" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['vals']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
    </tr>
    <tr>
      <td class="medium_grey"><?php echo $this->_tpl_vars['LANG']['word_username']; ?>
</td>
      <td><input type="text" name="username" style="width: 100px" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['vals']['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
    </tr>
    <tr>
      <td valign="top" class="medium_grey"><?php echo $this->_tpl_vars['LANG']['word_password']; ?>
</td>
      <td>
        <input type="password" name="password" style="width: 100px" value="<?php echo $this->_tpl_vars['vals']['password']; ?>
" />
      </td>
    </tr>
    <tr>
      <td class="medium_grey"><?php echo $this->_tpl_vars['LANG']['phrase_re_enter_password']; ?>
</td>
      <td><input type="password" name="password_2" style="width: 100px" value="<?php echo $this->_tpl_vars['vals']['password_2']; ?>
"/></td>
    </tr>
    </table>

    <?php echo smarty_function_template_hook(array('location' => 'admin_add_client_bottom'), $this);?>


    <p>
      <input type="submit" name="add_client" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_add_client'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
    </p>

  </form>

<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>