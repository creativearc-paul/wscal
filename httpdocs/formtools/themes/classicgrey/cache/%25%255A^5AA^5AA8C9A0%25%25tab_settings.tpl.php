<?php /* Smarty version 2.6.18, created on 2015-09-17 12:59:09
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/tab_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/tab_settings.tpl', 1, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/tab_settings.tpl', 27, false),array('function', 'themes_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/tab_settings.tpl', 48, false),array('function', 'languages_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/tab_settings.tpl', 62, false),array('function', 'timezone_offset_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/tab_settings.tpl', 69, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/tab_settings.tpl', 34, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/tab_settings.tpl', 107, false),)), $this); ?>
    <?php echo smarty_function_ft_include(array('file' => 'messages.tpl'), $this);?>


        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_page_titles'] == 'no' && $this->_tpl_vars['client_info']['settings']['may_edit_footer_text'] == 'no' && $this->_tpl_vars['client_info']['settings']['may_edit_theme'] == 'no' && $this->_tpl_vars['client_info']['settings']['may_edit_logout_url'] == 'no' && $this->_tpl_vars['client_info']['settings']['may_edit_language'] == 'no' && $this->_tpl_vars['client_info']['settings']['may_edit_timezone_offset'] == 'no' && $this->_tpl_vars['client_info']['settings']['may_edit_sessions_timeout'] == 'no' && $this->_tpl_vars['client_info']['settings']['may_edit_date_format'] == 'no' && $this->_tpl_vars['client_info']['settings']['may_edit_max_failed_login_attempts'] == 'no'): ?>

      <div class="notify yellow_bg">
        <div style="padding:8px">
          <?php echo $this->_tpl_vars['LANG']['notify_no_client_permissions']; ?>

        </div>
      </div>
      <br />

    <?php else: ?>

      <form method="post" action="<?php echo $this->_tpl_vars['same_page']; ?>
" onsubmit="return rsv.validate(this, rules)">
        <input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['client_id']; ?>
" />
        <input type="hidden" name="page" value="settings" />

        <?php echo smarty_function_template_hook(array('location' => 'edit_client_settings_top'), $this);?>


        <table class="list_table" cellpadding="0" cellspacing="1">
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_page_titles'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center">*</td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_page_titles']; ?>
</td>
            <td><input type="text" name="page_titles" id="page_titles" style="width:98%" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['client_info']['settings']['page_titles'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_footer_text'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center"> </td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_footer_text']; ?>
</td>
            <td><input type="text" name="footer_text" style="width:98%" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['client_info']['settings']['footer_text'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_theme'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center">*</td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['word_theme']; ?>
</td>
            <td><?php echo smarty_function_themes_dropdown(array('name_id' => 'theme','default' => $this->_tpl_vars['client_info']['theme']), $this);?>
</td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_logout_url'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center">*</td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_logout_url']; ?>
</td>
            <td><input type="text" name="logout_url" value="<?php echo $this->_tpl_vars['client_info']['logout_url']; ?>
" style="width: 300px" /></td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_language'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center">*</td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['word_language']; ?>
</td>
            <td><?php echo smarty_function_languages_dropdown(array('name_id' => 'ui_language','default' => $this->_tpl_vars['client_info']['ui_language']), $this);?>
</td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_timezone_offset'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center">*</td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_system_time_offset']; ?>
</td>
            <td><?php echo smarty_function_timezone_offset_dropdown(array('name_id' => 'timezone_offset','default' => $this->_tpl_vars['client_info']['timezone_offset']), $this);?>
</td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_sessions_timeout'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center">*</td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_sessions_timeout']; ?>
</td>
            <td><input type="text" name="sessions_timeout" value="<?php echo $this->_tpl_vars['client_info']['sessions_timeout']; ?>
" style="width: 30px" /> <?php echo $this->_tpl_vars['LANG']['word_minutes']; ?>
</td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_date_format'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center">*</td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_date_format']; ?>
</td>
            <td><input type="text" name="date_format" value="<?php echo $this->_tpl_vars['client_info']['date_format']; ?>
" style="width: 80px" /> <span class="medium_grey"><?php echo $this->_tpl_vars['text_date_formatting_link']; ?>
</span></td>
          </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['client_info']['settings']['may_edit_max_failed_login_attempts'] == 'yes'): ?>
          <tr>
            <td width="15" class="red" align="center">*</td>
            <td class="pad_left_small" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_auto_disable_account']; ?>
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
          </tr>
        <?php endif; ?>
        </table>

        <?php echo smarty_function_template_hook(array('location' => 'edit_client_settings_bottom'), $this);?>


        <p>
          <input type="submit" name="update_account_settings" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
        </p>

      </form>

    <?php endif; ?>