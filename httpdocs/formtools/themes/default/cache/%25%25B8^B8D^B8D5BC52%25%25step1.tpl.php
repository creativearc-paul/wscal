<?php /* Smarty version 2.6.18, created on 2010-12-01 16:34:07
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step1.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step1.tpl', 1, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step1.tpl', 6, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table cellpadding="0" cellspacing="0" class="margin_bottom_large">
  <tr>
    <td width="45"><a href="../"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_forms.gif" border="0" width="34" height="34" /></a></td>
    <td class="title"><a href="../"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_forms'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a>: <?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_add_form'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
  </tr>
  </table>

  <table cellpadding="0" cellspacing="0" width="100%" class="add_form_nav margin_bottom_large">
  <tr>
    <td class="selected"><?php echo $this->_tpl_vars['LANG']['word_checklist']; ?>
</td>
    <td class="unselected"><?php echo $this->_tpl_vars['LANG']['phrase_form_info']; ?>
</td>
    <td class="unselected"><?php echo $this->_tpl_vars['LANG']['phrase_test_submission']; ?>
</td>
    <td class="unselected"><?php echo $this->_tpl_vars['LANG']['phrase_database_setup']; ?>
</td>
    <td class="unselected"><?php echo $this->_tpl_vars['LANG']['phrase_field_types']; ?>
</td>
    <td class="unselected"><?php echo $this->_tpl_vars['LANG']['phrase_finalize_form']; ?>
</td>
  </tr>
  </table>

  <div class="subtitle underline" style="position:relative"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_checklist_1'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

  <p>
    <?php echo $this->_tpl_vars['LANG']['text_add_form_step_1_text_1']; ?>

  </p>

  <ul>
    <li><?php echo $this->_tpl_vars['LANG']['text_add_form_step_1_text_2']; ?>
</li>
    <li><?php echo $this->_tpl_vars['LANG']['text_add_form_step_1_text_3']; ?>
</li>
  </ul>

  <p>
    All the settings you enter in the following pages may later be edited.
  </p>

  <p>
    If you run into any trouble during these steps, you may want to consult the <a href="http://docs.formtools.org/userdoc?page=add_form">User Documentation</a>
    for further information.
  </p>

  <form action="step2.php" method="post">
    <p>
      <input type="hidden" name="form_id" value="<?php echo $this->_tpl_vars['form_id']; ?>
" />
      <input type="submit" name="submit" class="next_step" value="<?php echo $this->_tpl_vars['LANG']['word_next_step_rightarrow']; ?>
" />
    </p>
  </form>

<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>