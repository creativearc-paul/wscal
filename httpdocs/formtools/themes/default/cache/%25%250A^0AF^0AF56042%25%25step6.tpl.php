<?php /* Smarty version 2.6.18, created on 2010-12-01 16:48:21
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step6.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step6.tpl', 1, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step6.tpl', 6, false),)), $this); ?>
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

  <table cellpadding="0" cellspacing="0" width="100%" class="add_form_nav">
  <tr>
    <td class="selected"><?php echo $this->_tpl_vars['LANG']['word_checklist']; ?>
</td>
    <td class="selected"><?php echo $this->_tpl_vars['LANG']['phrase_form_info']; ?>
</td>
    <td class="selected"><?php echo $this->_tpl_vars['LANG']['phrase_test_submission']; ?>
</td>
    <td class="selected"><?php echo $this->_tpl_vars['LANG']['phrase_database_setup']; ?>
</td>
    <td class="selected"><?php echo $this->_tpl_vars['LANG']['phrase_field_types']; ?>
</td>
    <td class="selected"><?php echo $this->_tpl_vars['LANG']['phrase_finalize_form']; ?>
</td>
  </tr>
  </table>

  <br />

  <div>

    <div class="subtitle underline"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_final_touches_page_6'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

    <p>
      <?php echo $this->_tpl_vars['LANG']['text_add_form_step_5_para_1']; ?>

    </p>

    <code><pre class="green">
      &lt;input type="hidden" name="form_tools_initialize_form" value="1" /&gt;</pre></code>

    <p>
      <?php echo $this->_tpl_vars['LANG']['text_add_form_step_5_para_5']; ?>

    </p>

    <code><pre class="green">
      $fields = ft_api_init_form_page(<?php echo $this->_tpl_vars['form_id']; ?>
);</pre></code>

    <p>
      <?php echo $this->_tpl_vars['LANG']['text_add_form_step_5_para_2']; ?>

    </p>

    <p>
      <?php echo $this->_tpl_vars['text_add_form_step_5_para']; ?>

    </p>

    <?php if ($this->_tpl_vars['uploading_files'] == 'yes'): ?>
      <p>
        <?php echo $this->_tpl_vars['text_add_form_step_5_para_4']; ?>

      </p>
    <?php endif; ?>

  </div>

  <form method="post" action="../">
    <input type="submit" name="action" value="<?php echo $this->_tpl_vars['LANG']['phrase_form_list_uc']; ?>
" />
  </form>

<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>