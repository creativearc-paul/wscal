<?php /* Smarty version 2.6.18, created on 2010-12-01 16:48:41
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/edit.tpl', 1, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/edit.tpl', 6, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table cellpadding="0" cellspacing="0" width="100%" class="margin_bottom_large">
  <tr>
    <td width="45"><a href="./"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_forms.gif" border="0" width="34" height="34" /></a></td>
    <td class="title"><a href="./"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_forms'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a>: <?php echo ((is_array($_tmp=$this->_tpl_vars['form_info']['form_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
 (<span class="bold"><?php echo $this->_tpl_vars['form_id']; ?>
</span>)</td>
    <td align="right">
      <div style="float:right; padding-left: 6px;">
	      <a href="submissions.php?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/view_small.gif" border="0" alt="<?php echo $this->_tpl_vars['LANG']['phrase_view_submissions']; ?>
"
	        title="<?php echo $this->_tpl_vars['LANG']['phrase_view_submissions']; ?>
" /></a>
	    </div>
    </td>
  </tr>
  </table>

  <?php echo smarty_function_ft_include(array('file' => 'tabset_open.tpl'), $this);?>


  <?php if ($this->_tpl_vars['page'] == 'main'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_main.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'public_form_omit_list'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_public_form_omit_list.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'fields'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_fields.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'field_options'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_field_options.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'files'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_files.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'images'): ?>
    <?php echo smarty_function_ft_include(array('file' => '../../modules/image_manager/templates/tab_images.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'emails'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_emails.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'email_settings'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_email_settings.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'edit_email'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_edit_email.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'views'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_views.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'edit_view'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_edit_view.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'public_view_omit_list'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_public_view_omit_list.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'add_view'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_add_view.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'database'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_database.tpl'), $this);?>

  <?php elseif ($this->_tpl_vars['page'] == 'add_fields'): ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_add_fields.tpl'), $this);?>

  <?php else: ?>
    <?php echo smarty_function_ft_include(array('file' => 'admin/forms/tab_main.tpl'), $this);?>

  <?php endif; ?>

  <?php echo smarty_function_ft_include(array('file' => 'tabset_close.tpl'), $this);?>


<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>