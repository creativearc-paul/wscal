<?php /* Smarty version 2.6.18, created on 2015-09-17 12:39:36
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/index.tpl', 1, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/clients/index.tpl', 6, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_forms.gif" width="34" height="34" /></td>
    <td class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_forms'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
  </tr>
  </table>

  <p>
    <?php echo $this->_tpl_vars['LANG']['text_client_welcome']; ?>

  </p>

    <?php if (count ( $this->_tpl_vars['forms'] ) == 0): ?>
    <b><?php echo $this->_tpl_vars['LANG']['text_client_no_forms']; ?>
</b>
  <?php else: ?>

    <table class="list_table" cellpadding="1" cellspacing="1" style="width:600px">
    <tr style="height: 20px;">
      <th><?php echo $this->_tpl_vars['LANG']['word_form']; ?>
</th>
      <th width="80"><?php echo $this->_tpl_vars['LANG']['word_status']; ?>
</th>
      <th width="80"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_submissions'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
    </tr>

        <?php $_from = $this->_tpl_vars['forms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['form_info']):
?>
      <?php $this->assign('form_id', $this->_tpl_vars['form_info']['form_id']); ?>

      <tr style="height: 20px;">
        <td><a href="<?php echo $this->_tpl_vars['form_info']['form_url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['form_info']['form_name']; ?>
</a></td>
        <td align="center">
          <?php if ($this->_tpl_vars['form_info']['is_active'] == 'no'): ?>
            <span class="red"><?php echo $this->_tpl_vars['LANG']['word_offline']; ?>
</span>
          <?php else: ?>
            <span class="light_green"><?php echo $this->_tpl_vars['LANG']['word_online']; ?>
</span>
          <?php endif; ?>
        </td>
        <td align="center">
          <?php $this->assign('form_num_submissions_key', "form_".($this->_tpl_vars['form_id'])."_num_submissions"); ?>
          <?php $this->assign('num_submissions', $this->_tpl_vars['SESSION'][$this->_tpl_vars['form_num_submissions_key']]); ?>
          (<?php echo $this->_tpl_vars['num_submissions']; ?>
)&nbsp;<a href="forms/index.php?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_view'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a>
        </td>
      </tr>
    <?php endforeach; endif; unset($_from); ?>

    </table>

  <?php endif; ?>

<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>