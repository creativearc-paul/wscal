<?php /* Smarty version 2.6.18, created on 2015-10-02 12:35:50
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/delete_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/delete_form.tpl', 1, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/delete_form.tpl', 4, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <p>
    <span class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_delete_form_c'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
    <span class="subtitle"><?php echo $this->_tpl_vars['form_info']['form_name']; ?>
</span> (<b><?php echo $this->_tpl_vars['form_id']; ?>
</b>)
  </p>

  <div class="error yellow_bg">
    <div style="padding: 8px;">
      <b><?php echo $this->_tpl_vars['LANG']['word_warning_c']; ?>
</b> <?php echo $this->_tpl_vars['LANG']['text_delete_form_warning']; ?>

    </div>
  </div>

  <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


  <form method="post" action="<?php echo $this->_tpl_vars['same_page']; ?>
" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="form_id" value="<?php echo $this->_tpl_vars['form_id']; ?>
" />
    <input type="checkbox" name="delete_form" id="delete_form" value="yes" />
    <label for="delete_form"><?php echo $this->_tpl_vars['LANG']['text_confirm_delete_form']; ?>
</label><br />

    <?php if ($this->_tpl_vars['files_uploaded']): ?>
      <input type="checkbox" name="delete_files" id="delete_files" value="yes" />
      <label for="delete_files"><?php echo $this->_tpl_vars['LANG']['text_delete_all_forms']; ?>
</label>
      (<a href="#" onclick="page_ns.show_uploaded_files(); return false"><?php echo $this->_tpl_vars['LANG']['phrase_view_uploaded_files']; ?>
</a>)<br />
    <?php endif; ?>

    <br />
    <input type="button" name="sss" value="<?php echo $this->_tpl_vars['LANG']['phrase_return_form_list']; ?>
" onclick="window.location='index.php'" />
    <input type="submit" name="sss2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_delete_form'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" class="bold" />
  </form>

  <?php if ($this->_tpl_vars['files_uploaded']): ?>
    <div id="uploaded_files" style="display: none;">
      <br />
      <hr size="1" />

      <?php $this->assign('has_at_least_one_file', false); ?>
      <?php $_from = $this->_tpl_vars['file_field_hash']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field_id'] => $this->_tpl_vars['v']):
?>

         <?php $_from = $this->_tpl_vars['files_uploaded'][$this->_tpl_vars['field_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file']):
?>
           <a href="<?php echo $this->_tpl_vars['v'][1]; ?>
/<?php echo $this->_tpl_vars['file']; ?>
" target="_blank"><?php echo $this->_tpl_vars['file']; ?>
</a><br />
           <?php $this->assign('has_at_least_one_file', true); ?>
         <?php endforeach; endif; unset($_from); ?>

      <?php endforeach; endif; unset($_from); ?>

      <?php if (! $this->_tpl_vars['has_at_least_one_file']): ?>
        <span class="medium_grey"><?php echo $this->_tpl_vars['LANG']['phrase_no_files_uploaded']; ?>
</span>
      <?php endif; ?>
    </div>
  <?php endif; ?>

<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>