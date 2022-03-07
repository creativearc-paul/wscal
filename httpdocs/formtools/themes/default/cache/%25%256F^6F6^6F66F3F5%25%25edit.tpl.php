<?php /* Smarty version 2.6.18, created on 2010-12-15 09:45:40
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/edit.tpl', 1, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/edit.tpl', 16, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/edit.tpl', 7, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table width="100%" cellpadding="0" cellspacing="0" class="margin_bottom_large">
  <tr>
    <td width="45"><a href="./"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_accounts.gif" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="./"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_clients'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a>: <?php echo ((is_array($_tmp=$this->_tpl_vars['client_info']['first_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['client_info']['last_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

      (<span class="bold"><?php echo $this->_tpl_vars['client_id']; ?>
</span>)
    </td>
    <td align="right">
      <a href="index.php?login=<?php echo $this->_tpl_vars['client_id']; ?>
"><?php echo $this->_tpl_vars['LANG']['word_login_link']; ?>
</a>
    </td>
  </tr>
  </table>

  <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_pages_top'), $this);?>


  <?php echo smarty_function_ft_include(array('file' => 'tabset_open.tpl'), $this);?>


    <?php if ($this->_tpl_vars['page'] == 'main'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/clients/tab_main.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'settings'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/clients/tab_settings.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'forms'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/clients/tab_forms.tpl'), $this);?>

    <?php else: ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/clients/tab_main.tpl'), $this);?>

    <?php endif; ?>

  <?php echo smarty_function_ft_include(array('file' => 'tabset_close.tpl'), $this);?>


  <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_pages_bottom'), $this);?>


<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>