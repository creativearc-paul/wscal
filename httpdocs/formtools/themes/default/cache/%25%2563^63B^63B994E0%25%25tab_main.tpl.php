<?php /* Smarty version 2.6.18, created on 2010-12-15 09:45:40
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_main.tpl', 1, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_main.tpl', 7, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_main.tpl', 25, false),array('modifier', 'in_array', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_main.tpl', 58, false),)), $this); ?>
    <?php echo smarty_function_ft_include(array('file' => 'messages.tpl'), $this);?>


    <form method="post" name="add_client" id="add_client" action="<?php echo $this->_tpl_vars['same_page']; ?>
" onsubmit="return rsv.validate(this, rules)">
      <input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['client_id']; ?>
" />
      <input type="hidden" name="update_client" value="1" />

      <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_main_top'), $this);?>


      <table class="list_table" cellpadding="0" cellspacing="1">
      <tr>
        <td width="15" align="center" class="red">*</td>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['word_status']; ?>
</td>
        <td>
          <input type="radio" name="account_status" id="as1" value="active" <?php if ($this->_tpl_vars['client_info']['account_status'] == 'active'): ?>checked<?php endif; ?> />
            <label for="as1" class="light_green"><?php echo $this->_tpl_vars['LANG']['word_active']; ?>
</label>
          <input type="radio" name="account_status" id="as2" value="pending" <?php if ($this->_tpl_vars['client_info']['account_status'] == 'pending'): ?>checked<?php endif; ?> />
            <label for="as2" class="orange"><?php echo $this->_tpl_vars['LANG']['word_pending']; ?>
</label>
          <input type="radio" name="account_status" id="as3" value="disabled" <?php if ($this->_tpl_vars['client_info']['account_status'] == 'disabled'): ?>checked<?php endif; ?> />
            <label for="as3" class="red"><?php echo $this->_tpl_vars['LANG']['word_disabled']; ?>
</label>
        </td>
      </tr>
      <tr>
        <td width="15" align="center" class="red">*</td>
        <td width="180" class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_first_name']; ?>
</td>
        <td><input type="text" name="first_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['client_info']['first_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size="20" /></td>
      </tr>
      <tr>
        <td align="center" class="red">*</td>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_last_name']; ?>
</td>
        <td><input type="text" name="last_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['client_info']['last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size="20" /></td>
      </tr>
      <tr>
        <td align="center" class="red">*</td>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['word_email']; ?>
</td>
        <td><input type="text" name="email" value="<?php echo $this->_tpl_vars['client_info']['email']; ?>
" size="50" /></td>
      </tr>
      <tr>
        <td class="red"> </td>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_company_name']; ?>
</td>
        <td><input type="text" name="company_name" value="<?php echo $this->_tpl_vars['client_info']['settings']['company_name']; ?>
" style="width: 98%;" /></td>
      </tr>
      <tr>
        <td class="red"></td>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['word_notes']; ?>
 <?php echo $this->_tpl_vars['LANG']['phrase_not_visible_to_client']; ?>
</td>
        <td><textarea name="client_notes" style="width:98%; height: 80px;"><?php echo $this->_tpl_vars['client_info']['settings']['client_notes']; ?>
</textarea></td>
      </tr>
      </table>

      <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_main_middle'), $this);?>


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
        <td width="15" align="center" class="red">*</td>
        <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['word_username']; ?>
</td>
        <td><input type="text" name="username" value="<?php echo $this->_tpl_vars['client_info']['username']; ?>
" size="20" /></td>
      </tr>
      <tr>
        <td> </td>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_new_password']; ?>
</td>
        <td><input type="password" name="password" value="" size="20" autocomplete="off" /></td>
      </tr>
      <tr>
        <td> </td>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_new_password_reenter']; ?>
</td>
        <td><input type="password" name="password_2" value="" size="20" autocomplete="off" /></td>
      </tr>
      </table>

      <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_main_bottom'), $this);?>


      <p>
        <input type="submit" name="submit" value="<?php echo $this->_tpl_vars['LANG']['word_update']; ?>
" />
      </p>

    </form>