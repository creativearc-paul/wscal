<?php /* Smarty version 2.6.18, created on 2015-09-17 12:58:05
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/index.tpl', 1, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/account/index.tpl', 6, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table cellpadding="0" cellspacing="0" class="margin_bottom_large">
  <tr>
    <td width="45"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_login.gif" width="34" height="34" /></td>
    <td class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_account_info'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
  </tr>
  </table>

  <?php echo smarty_function_ft_include(array('file' => 'tabset_open.tpl'), $this);?>


    <?php if ($this->_tpl_vars['page'] == 'main'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'clients/account/tab_main.tpl'), $this);?>

    <?php elseif ($this->_tpl_vars['page'] == 'settings'): ?>
      <?php echo smarty_function_ft_include(array('file' => 'clients/account/tab_settings.tpl'), $this);?>

    <?php else: ?>
      <?php echo smarty_function_ft_include(array('file' => 'clients/account/tab_main.tpl'), $this);?>

    <?php endif; ?>

  <?php echo smarty_function_ft_include(array('file' => 'tabset_close.tpl'), $this);?>


<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>