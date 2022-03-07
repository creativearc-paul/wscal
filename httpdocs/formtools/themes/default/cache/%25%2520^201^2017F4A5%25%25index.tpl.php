<?php /* Smarty version 2.6.18, created on 2010-12-13 14:33:13
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/settings/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/settings/index.tpl', 1, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/settings/index.tpl', 6, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table cellpadding="0" cellspacing="0" height="35" class="margin_bottom_large">
  <tr>
    <td width="45"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_settings.gif" width="34" height="34" /></td>
    <td class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_settings'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
  </tr>
  </table>

  <?php echo smarty_function_ft_include(array('file' => 'tabset_open.tpl'), $this);?>


    <?php if ($this->_tpl_vars['page'] == 'main'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_main.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'accounts'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_accounts.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'files'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_files.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'wysiwyg'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_wysiwyg.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'themes'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_themes.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'themes_about'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_themes_about.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'menus'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_menus.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'add_menu'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_add_menu.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'edit_client_menu'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_edit_client_menu.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'edit_admin_menu'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_edit_admin_menu.tpl'), $this);?>

    <?php else: ?>
      <?php echo smarty_function_ft_include(array('file' => 'admin/settings/tab_main.tpl'), $this);?>

    <?php endif; ?>

  <?php echo smarty_function_ft_include(array('file' => 'tabset_close.tpl'), $this);?>


<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>