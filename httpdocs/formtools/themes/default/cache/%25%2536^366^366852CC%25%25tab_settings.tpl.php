<?php /* Smarty version 2.6.18, created on 2010-12-15 09:46:00
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 1, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 7, false),array('function', 'themes_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 27, false),array('function', 'menus_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 32, false),array('function', 'pages_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 37, false),array('function', 'languages_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 48, false),array('function', 'timezone_offset_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 56, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 17, false),array('modifier', 'explode', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 114, false),array('modifier', 'in_array', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 117, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_settings.tpl', 158, false),)), $this); ?>
    <?php echo smarty_function_ft_include(array('file' => 'messages.tpl'), $this);?>


    <form method="post" id="settings_form" action="<?php echo $this->_tpl_vars['same_page']; ?>
" onsubmit="return rsv.validate(this, rules)">
      <input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['client_id']; ?>
" />
      <input type="hidden" name="page" value="settings" />

      <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_settings_top'), $this);?>


      <table class="list_table" cellpadding="0" cellspacing="1">
      <tr>
        <th><?php echo $this->_tpl_vars['LANG']['word_setting']; ?>
</th>
        <th><?php echo $this->_tpl_vars['LANG']['phrase_setting_value']; ?>
</th>
        <th><?php echo $this->_tpl_vars['LANG']['phrase_client_may_edit']; ?>
</th>
      </tr>
      <tr>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_page_titles']; ?>
</td>
        <td><input type="text" name="page_titles" id="page_titles" style="width:98%" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['client_info']['settings']['page_titles'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
        <td align="center"><input type="checkbox" name="may_edit_page_titles" <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_page_titles'] == 'yes'): ?>checked<?php endif; ?> /></td>
      </tr>
      <tr>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_footer_text']; ?>
</td>
        <td><input type="text" name="footer_text" style="width:98%" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['client_info']['settings']['footer_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
        <td align="center"><input type="checkbox" name="may_edit_footer_text" <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_footer_text'] == 'yes'): ?>checked<?php endif; ?> /></td>
      </tr>
      <tr>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['word_theme']; ?>
</td>
        <td><?php echo smarty_function_themes_dropdown(array('name_id' => 'theme','default' => $this->_tpl_vars['client_info']['theme']), $this);?>
</td>
        <td align="center"><input type="checkbox" name="may_edit_theme" <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_theme'] == 'yes'): ?>checked<?php endif; ?> /></td>
      </tr>
      <tr>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['word_menu']; ?>
</td>
        <td><?php echo smarty_function_menus_dropdown(array('type' => 'client','name_id' => 'menu_id','default' => $this->_tpl_vars['client_info']['menu_id']), $this);?>
</td>
        <td> </td>
      </tr>
      <tr>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_login_page']; ?>
</td>
        <td><?php echo smarty_function_pages_dropdown(array('menu_type' => 'client','name_id' => 'login_page','default' => $this->_tpl_vars['client_info']['login_page'],'omit_pages' => "logout,custom_url,client_form_submissions"), $this);?>
</td>
        <td align="center"> </td>
      </tr>
      <tr>
        <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_logout_url']; ?>
</td>
        <td><input type="text" name="logout_url" value="<?php echo $this->_tpl_vars['client_info']['logout_url']; ?>
" style="width: 300px" /></td>
        <td align="center"><input type="checkbox" name="may_edit_logout_url" <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_logout_url'] == 'yes'): ?>checked<?php endif; ?> /></td>
      </tr>
      <tr>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['word_language']; ?>
</td>
        <td>
          <?php echo smarty_function_languages_dropdown(array('name_id' => 'ui_language','default' => $this->_tpl_vars['client_info']['ui_language']), $this);?>

          <input type="button" value="<?php echo $this->_tpl_vars['LANG']['phrase_refresh_list']; ?>
" onclick="window.location='edit.php?client_id=<?php echo $this->_tpl_vars['client_id']; ?>
&page=settings&refresh_lang_list'" />
          <a href="http://www.formtools.org/translations/" target="_blank"><?php echo $this->_tpl_vars['LANG']['phrase_get_more']; ?>
</a>
        </td>
        <td align="center"><input type="checkbox" name="may_edit_language" <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_language'] == 'yes'): ?>checked<?php endif; ?> /></td>
      </tr>
      <tr>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_system_time_offset']; ?>
</td>
        <td><?php echo smarty_function_timezone_offset_dropdown(array('name_id' => 'timezone_offset','default' => $this->_tpl_vars['client_info']['timezone_offset']), $this);?>
</td>
        <td align="center"><input type="checkbox" name="may_edit_timezone_offset" <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_timezone_offset'] == 'yes'): ?>checked<?php endif; ?> /></td>
      </tr>
      <tr>
        <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_sessions_timeout']; ?>
</td>
        <td><input type="text" name="sessions_timeout" value="<?php echo $this->_tpl_vars['client_info']['sessions_timeout']; ?>
" style="width: 30px" /> <?php echo $this->_tpl_vars['LANG']['word_minutes']; ?>
</td>
        <td align="center"><input type="checkbox" name="may_edit_sessions_timeout" <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_sessions_timeout'] == 'yes'): ?>checked<?php endif; ?> /></td>
      </tr>
      <tr>
        <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_date_format']; ?>
</td>
        <td><input type="text" name="date_format" value="<?php echo $this->_tpl_vars['client_info']['date_format']; ?>
" style="width: 80px" /> <span class="medium_grey"><?php echo $this->_tpl_vars['text_date_formatting_link']; ?>
</span></td>
        <td align="center"><input type="checkbox" name="may_edit_date_format" <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_date_format'] == 'yes'): ?>checked<?php endif; ?> /></td>
      </tr>
      </table>

	    <p class="subtitle"><?php echo $this->_tpl_vars['LANG']['phrase_security_settings']; ?>
</p>

	    <table class="list_table" cellpadding="0" cellspacing="1">
	    <tr>
	      <th><?php echo $this->_tpl_vars['LANG']['word_setting']; ?>
</th>
	      <th><?php echo $this->_tpl_vars['LANG']['phrase_setting_value']; ?>
</th>
	      <th><?php echo $this->_tpl_vars['LANG']['phrase_client_may_edit']; ?>
</th>
	    </tr>
	    <tr>
	      <td width="290" class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_auto_disable_account']; ?>
</td>
	      <td>
	        <select name="max_failed_login_attempts">
	          <option value=""   <?php if ($this->_tpl_vars['client_info']['settings']['max_failed_login_attempts'] == ""): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_na']; ?>
</option>
	          <option value="3"  <?php if ($this->_tpl_vars['client_info']['settings']['max_failed_login_attempts'] == '3'): ?>selected<?php endif; ?>>3</option>
	          <option value="4"  <?php if ($this->_tpl_vars['client_info']['settings']['max_failed_login_attempts'] == '4'): ?>selected<?php endif; ?>>4</option>
	          <option value="5"  <?php if ($this->_tpl_vars['client_info']['settings']['max_failed_login_attempts'] == '5'): ?>selected<?php endif; ?>>5</option>
	          <option value="6"  <?php if ($this->_tpl_vars['client_info']['settings']['max_failed_login_attempts'] == '6'): ?>selected<?php endif; ?>>6</option>
	          <option value="10" <?php if ($this->_tpl_vars['client_info']['settings']['max_failed_login_attempts'] == '10'): ?>selected<?php endif; ?>>10</option>
	        </select>
	      </td>
	      <td align="center"><input type="checkbox" name="may_edit_max_failed_login_attempts"
	        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_max_failed_login_attempts'] == 'yes'): ?>checked<?php endif; ?> /></td>
	    </tr>
	    <tr>
	      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_min_password_length']; ?>
</td>
	      <td>
	        <select name="min_password_length">
	          <option value=""   <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == ""): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_na']; ?>
</option>
	          <option value="4"  <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == '4'): ?>selected<?php endif; ?>>4</option>
	          <option value="5"  <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == '5'): ?>selected<?php endif; ?>>5</option>
	          <option value="6"  <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == '6'): ?>selected<?php endif; ?>>6</option>
	          <option value="7"  <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == '7'): ?>selected<?php endif; ?>>7</option>
	          <option value="8"  <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == '8'): ?>selected<?php endif; ?>>8</option>
	          <option value="9"  <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == '9'): ?>selected<?php endif; ?>>9</option>
	          <option value="10" <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == '10'): ?>selected<?php endif; ?>>10</option>
	          <option value="12" <?php if ($this->_tpl_vars['client_info']['settings']['min_password_length'] == '12'): ?>selected<?php endif; ?>>12</option>
	        </select>
	      </td>
	      <td></td>
	    </tr>
	    <tr>
	      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_required_password_chars']; ?>
</td>
	      <td>
	        <?php $this->assign('required_password_chars_arr', ((is_array($_tmp=",")) ? $this->_run_mod_handler('explode', true, $_tmp, $this->_tpl_vars['client_info']['settings']['required_password_chars']) : explode($_tmp, $this->_tpl_vars['client_info']['settings']['required_password_chars']))); ?>
	        <div>
		        <input type="checkbox" name="required_password_chars[]" value="uppercase" id="rpc1"
		          <?php if (((is_array($_tmp='uppercase')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['required_password_chars_arr']) : in_array($_tmp, $this->_tpl_vars['required_password_chars_arr']))): ?>checked<?php endif; ?> />
		          <label for="rpc1"><?php echo $this->_tpl_vars['LANG']['phrase_one_char_upper']; ?>
</label>
		      </div>
	        <div>
		        <input type="checkbox" name="required_password_chars[]" value="number" id="rpc2"
		          <?php if (((is_array($_tmp='number')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['required_password_chars_arr']) : in_array($_tmp, $this->_tpl_vars['required_password_chars_arr']))): ?>checked<?php endif; ?> />
		          <label for="rpc2"><?php echo $this->_tpl_vars['LANG']['phrase_one_char_number']; ?>
</label>
		      </div>
	        <div>
		        <input type="checkbox" name="required_password_chars[]" value="special_char" id="rpc3"
		          <?php if (((is_array($_tmp='special_char')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['required_password_chars_arr']) : in_array($_tmp, $this->_tpl_vars['required_password_chars_arr']))): ?>checked<?php endif; ?> />
		          <label for="rpc3"><?php echo $this->_tpl_vars['phrase_one_special_char']; ?>
</label>
		      </div>
	      </td>
	      <td>
	      </td>
	    </tr>
	    <tr>
	      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_prevent_password_reuse']; ?>
</td>
	      <td>
	        <select name="num_password_history">
	          <option value=""   <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == ""): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_na']; ?>
</option>
	          <option value="1"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '1'): ?>selected<?php endif; ?>>1</option>
	          <option value="2"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '2'): ?>selected<?php endif; ?>>2</option>
	          <option value="3"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '3'): ?>selected<?php endif; ?>>3</option>
	          <option value="4"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '4'): ?>selected<?php endif; ?>>4</option>
	          <option value="5"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '5'): ?>selected<?php endif; ?>>5</option>
	          <option value="6"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '6'): ?>selected<?php endif; ?>>6</option>
	          <option value="7"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '7'): ?>selected<?php endif; ?>>7</option>
	          <option value="8"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '8'): ?>selected<?php endif; ?>>8</option>
	          <option value="9"  <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '9'): ?>selected<?php endif; ?>>9</option>
	          <option value="10" <?php if ($this->_tpl_vars['client_info']['settings']['num_password_history'] == '10'): ?>selected<?php endif; ?>>10</option>
	        </select>
	      </td>
	      <td></td>
	    </tr>
	    </table>

      <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_settings_bottom'), $this);?>


      <p>
        <input type="submit" name="update_client" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
      </p>

    </form>