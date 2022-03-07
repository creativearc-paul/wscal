<?php /* Smarty version 2.6.18, created on 2010-12-15 10:15:58
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/clients/account/tab_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/clients/account/tab_main.tpl', 1, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/clients/account/tab_main.tpl', 5, false),array('modifier', 'in_array', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/clients/account/tab_main.tpl', 39, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/clients/account/tab_main.tpl', 67, false),)), $this); ?>
  <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


  <form method="post" name="login_info" action="<?php echo $this->_tpl_vars['same_page']; ?>
" onsubmit="return rsv.validate(this, rules)">

    <?php echo smarty_function_template_hook(array('location' => 'edit_client_main_top'), $this);?>


    <table class="list_table" cellpadding="0" cellspacing="1">
    <tr>
      <td width="15" class="red" align="center">*</td>
      <td width="180" class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_first_name']; ?>
</td>
      <td><input type="text" name="first_name" value="<?php echo $this->_tpl_vars['client_info']['first_name']; ?>
" size="25" /></td>
    </tr>
    <tr>
      <td class="red" align="center">*</td>
      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_last_name']; ?>
</td>
      <td><input type="text" name="last_name" value="<?php echo $this->_tpl_vars['client_info']['last_name']; ?>
" size="25" /></td>
    </tr>
    <tr>
      <td class="red" align="center">*</td>
      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['word_email']; ?>
</td>
      <td><input type="text" name="email" value="<?php echo $this->_tpl_vars['client_info']['email']; ?>
" size="40" /></td>
    </tr>
    <tr>
      <td class="red" align="center"> </td>
      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_company_name']; ?>
</td>
      <td><input type="text" name="company_name" value="<?php echo $this->_tpl_vars['client_info']['settings']['company_name']; ?>
" size="40" /></td>
    </tr>
    </table>

    <?php echo smarty_function_template_hook(array('location' => 'edit_client_main_middle'), $this);?>


    <p class="subtitle"><?php echo $this->_tpl_vars['LANG']['phrase_change_login_info']; ?>
</p>

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

    <table class="list_table" cellpadding="0" cellspacing="1">
    <tr>
      <td width="15" class="red" align="center">*</td>
      <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['word_username']; ?>
</td>
      <td><input type="text" name="username" value="<?php echo $this->_tpl_vars['client_info']['username']; ?>
" size="25" /></td>
    </tr>
    <tr>
      <td class="red" align="center"> </td>
      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['word_password']; ?>
</td>
      <td><input type="password" name="password" value="" size="25" /></td>
    </tr>
    <tr>
      <td class="red" align="center"> </td>
      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_re_enter_password']; ?>
</td>
      <td><input type="password" name="password_2" value="" size="25" /></td>
    </tr>
    </table>

    <?php echo smarty_function_template_hook(array('location' => 'edit_client_main_bottom'), $this);?>


    <p>
      <input type="submit" name="update" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
    </p>

  </form>