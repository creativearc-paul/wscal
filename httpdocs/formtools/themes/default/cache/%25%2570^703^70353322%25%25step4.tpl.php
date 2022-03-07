<?php /* Smarty version 2.6.18, created on 2010-12-01 16:37:43
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step4.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step4.tpl', 1, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step4.tpl', 6, false),array('modifier', 'truncate', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/add/step4.tpl', 72, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => "header.tpl"), $this);?>


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
    <td class="selected"><a href="step1.php"><?php echo $this->_tpl_vars['LANG']['word_checklist']; ?>
</a></td>
    <td class="selected"><a href="step2.php"><?php echo $this->_tpl_vars['LANG']['phrase_form_info']; ?>
</a></td>
    <td class="selected"><a href="step3.php"><?php echo $this->_tpl_vars['LANG']['phrase_test_submission']; ?>
</a></td>
    <td class="selected"><?php echo $this->_tpl_vars['LANG']['phrase_database_setup']; ?>
</td>
    <td class="unselected"><?php echo $this->_tpl_vars['LANG']['phrase_field_types']; ?>
</td>
    <td class="unselected"><?php echo $this->_tpl_vars['LANG']['phrase_finalize_form']; ?>
</td>
  </tr>
  </table>

  <br />

  <div>
    <div class="subtitle underline"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_db_setup_page_4'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

    <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


    <div class="margin_bottom_large">
      <?php echo $this->_tpl_vars['LANG']['text_add_form_step_3_para_1']; ?>

    </div>
    <div class="margin_bottom_large">
      <?php echo $this->_tpl_vars['LANG']['text_add_form_step_3_para_2']; ?>

    </div>

    <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post">
      <input type="hidden" name="form_id" value="<?php echo $this->_tpl_vars['form_id']; ?>
" />

      <table class="list_table" width="100%" cellpadding="0" cellspacing="1">
      <tr>
        <th width="40"><?php echo $this->_tpl_vars['LANG']['word_order']; ?>
</th>
        <th><?php echo $this->_tpl_vars['LANG']['phrase_form_field_name']; ?>
</td>
        <th nowrap><?php echo $this->_tpl_vars['LANG']['phrase_display_name']; ?>
<span class="pad_right">&nbsp;</span><input type="button" class="bold" value="<?php echo $this->_tpl_vars['LANG']['phrase_smart_fill']; ?>
" onclick="page_ns.smart_fill()" /></th>
        <th><?php echo $this->_tpl_vars['LANG']['phrase_sample_data']; ?>
</td>
        <th><?php echo $this->_tpl_vars['LANG']['phrase_field_size']; ?>
</th>
        <th width="50" class="del"><?php echo $this->_tpl_vars['LANG']['word_delete']; ?>
</th>
      </tr>

      <?php $_from = $this->_tpl_vars['form_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['field']):
        $this->_foreach['row']['iteration']++;
?>
        <?php $this->assign('row_count', $this->_foreach['row']['iteration']); ?>
        <?php $this->assign('field_id', $this->_tpl_vars['field']['field_id']); ?>

        <?php $this->assign('style', ""); ?>
        <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>
          <?php $this->assign('style', "background-color: #C6F1C9"); ?>         <?php endif; ?>

        <?php $this->assign('include_on_redirect', ""); ?>
        <?php if ($this->_tpl_vars['field']['include_on_redirect'] == 'yes'): ?>
          <?php $this->assign('include_on_redirect', 'checked'); ?>
        <?php endif; ?>

        <tr style="<?php echo $this->_tpl_vars['style']; ?>
">
          <td align="center">
            <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
" value="1" />
            <input type="text" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_order" style="width: 30px" value="<?php echo $this->_tpl_vars['row_count']; ?>
" tabindex="<?php echo $this->_tpl_vars['row_count']; ?>
" />
          </td>
          <td class="blue pad_left_small"><?php echo $this->_tpl_vars['field']['field_name']; ?>
</td>
          <td class="blue pad_left_small">
            <input type="text" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_display_name" id="field_<?php echo $this->_tpl_vars['field_id']; ?>
_display_name" style="width: 96%;" value="<?php echo $this->_tpl_vars['field']['field_title']; ?>
"
              tabindex="<?php echo $this->_tpl_vars['row_count']+10000; ?>
" />
          </td>
          <td class="pad_left_small"><?php echo ((is_array($_tmp=$this->_tpl_vars['field']['field_test_value'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30) : smarty_modifier_truncate($_tmp, 30)); ?>
</td>
          <td class="pad_left_small" width="100">

            <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>

                            <?php echo $this->_tpl_vars['LANG']['word_na']; ?>

              <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_system" value="1" />
              <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_size" value="small" />

            <?php else: ?>

              <?php if ($this->_tpl_vars['field']['field_test_value'] == $this->_tpl_vars['LANG']['word_file_b_uc']): ?>
                <select disabled>
              <?php else: ?>
                <select name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_size" tabindex="<?php echo $this->_tpl_vars['row_count']+20000; ?>
">
              <?php endif; ?>

              <option <?php if ($this->_tpl_vars['field']['field_size'] == 'tiny'): ?>selected<?php endif; ?> value="tiny"><?php echo $this->_tpl_vars['LANG']['phrase_size_tiny']; ?>
</option>
              <option <?php if ($this->_tpl_vars['field']['field_size'] == 'small'): ?>selected<?php endif; ?> value="small"><?php echo $this->_tpl_vars['LANG']['phrase_size_small']; ?>
</option>
              <option <?php if ($this->_tpl_vars['field']['field_size'] == 'medium' || $this->_tpl_vars['field']['field_test_value'] == $this->_tpl_vars['LANG']['word_file_b_uc']): ?>selected<?php endif; ?> value="medium"><?php echo $this->_tpl_vars['LANG']['phrase_size_medium']; ?>
</option>
              <option <?php if ($this->_tpl_vars['field']['field_size'] == 'large'): ?>selected<?php endif; ?> value="large"><?php echo $this->_tpl_vars['LANG']['phrase_size_large']; ?>
</option>
              <option <?php if ($this->_tpl_vars['field']['field_size'] == 'very_large'): ?>selected<?php endif; ?> value='very_large'><?php echo $this->_tpl_vars['LANG']['phrase_size_very_large']; ?>
</option>
              </select>

                            <?php if ($this->_tpl_vars['field']['field_test_value'] == $this->_tpl_vars['LANG']['word_file_b_uc']): ?>
                <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_size" value="medium" />
              <?php endif; ?>
            <?php endif; ?>

          </td>
          <td class="del">
          <?php if ($this->_tpl_vars['field']['field_type'] != 'system'): ?><input type="checkbox" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_remove" tabindex="<?php echo $this->_tpl_vars['row_count']+30000; ?>
" /><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; endif; unset($_from); ?>
      </table>

      <p>
        <input type="submit" name="action" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
      </p>

      <p>
        <input type="submit" name="next_step" class="next_step" value="<?php echo $this->_tpl_vars['LANG']['word_next_step_rightarrow']; ?>
"
          onclick="return page_ns.validate_fields()"/>
      </p>

    </form>

  </div>

<?php echo smarty_function_ft_include(array('file' => "footer.tpl"), $this);?>