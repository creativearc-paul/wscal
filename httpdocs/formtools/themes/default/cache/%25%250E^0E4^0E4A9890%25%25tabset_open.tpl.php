<?php /* Smarty version 2.6.18, created on 2010-12-01 16:48:41
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/tabset_open.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'in_array', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/tabset_open.tpl', 16, false),)), $this); ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
  <td>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>

            <?php $_from = $this->_tpl_vars['tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['curr_tab_key'] => $this->_tpl_vars['curr_tab']):
?>

                <?php if ($this->_tpl_vars['curr_tab_key'] == $this->_tpl_vars['page'] || ( is_array ( $this->_tpl_vars['curr_tab']['pages'] ) && ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['curr_tab']['pages']) : in_array($_tmp, $this->_tpl_vars['curr_tab']['pages'])) ) || $this->_tpl_vars['tab_number'] == $this->_tpl_vars['curr_tab_key']): ?>
          <td width="10" height="26"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/left_tab_selected.gif" width="12" height="26" alt=""></td>
          <td class="tab_selected nowrap" width="80"><div class="pad_left pad_right nowrap"><a href="<?php echo $this->_tpl_vars['curr_tab']['tab_link']; ?>
" style="display:block"><?php echo $this->_tpl_vars['curr_tab']['tab_label']; ?>
</a></div></td>
          <td width="10" height="26"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/right_tab_selected.gif" width="10" height="26" alt=""></td>
        <?php else: ?>
          <td width="10" height="26"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/left_tab_not_selected.gif" width="12" height="26" alt=""></td>
          <td class="tab_not_selected" width="80"><div class="pad_left pad_right nowrap"><a href="<?php echo $this->_tpl_vars['curr_tab']['tab_link']; ?>
" style="display:block"><?php echo $this->_tpl_vars['curr_tab']['tab_label']; ?>
</a></div></td>
          <td width="10" height="26"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/right_tab_not_selected.gif" width="10" height="26" alt=""></td>
        <?php endif; ?>

        <td width="1" height="26" style="border-bottom: 1px solid #cfcfcf"> </td>
      <?php endforeach; endif; unset($_from); ?>

      <td height="26" style="border-bottom: 1px solid #cfcfcf;">&nbsp;</td>

    </tr>
    </table>

  </td>
</tr>
<tr>
  <td class="tab_content">