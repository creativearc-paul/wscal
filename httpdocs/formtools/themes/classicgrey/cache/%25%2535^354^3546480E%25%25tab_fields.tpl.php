<?php /* Smarty version 2.6.18, created on 2015-10-02 12:57:13
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_fields.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_fields.tpl', 1, false),array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_fields.tpl', 23, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_fields.tpl', 40, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_fields.tpl', 3, false),)), $this); ?>
  <div class="subtitle underline margin_top_large"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_fields'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

  <?php echo smarty_function_ft_include(array('file' => 'messages.tpl'), $this);?>


  <div class="margin_bottom_large">
    <?php echo $this->_tpl_vars['text_fields_tab_summary']; ?>

  </div>

  <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" name="display_form" id="display_form" method="post" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="page" value="fields" />

    <table class="list_table" style="width:100%" cellpadding="0" cellspacing="1">
    <tr>
      <th width="30"><?php echo $this->_tpl_vars['LANG']['word_order']; ?>
</th>
      <th><?php echo $this->_tpl_vars['LANG']['phrase_display_text']; ?>
</th>
      <th><?php echo $this->_tpl_vars['LANG']['phrase_form_field']; ?>
</th>
      <th width="150"><?php echo $this->_tpl_vars['LANG']['phrase_field_type']; ?>
</th>
      <th width="50" class="nowrap pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_pass_on']; ?>
</th>
      <th width="60"><?php echo $this->_tpl_vars['LANG']['word_options']; ?>
</th>
    </tr>

    <?php $this->assign('field_ids', ''); ?>
    <?php $this->assign('total_num_fields', count($this->_tpl_vars['form_fields'])); ?>
    <?php $_from = $this->_tpl_vars['form_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['field']):
        $this->_foreach['row']['iteration']++;
?>
      <?php $this->assign('count', $this->_foreach['row']['iteration']); ?>
      <?php $this->assign('field_id', $this->_tpl_vars['field']['field_id']); ?>
      <?php $this->assign('field_ids', ($this->_tpl_vars['field_ids']).",".($this->_tpl_vars['field']['field_id'])); ?>

      <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>
        <?php $this->assign('class_style', 'row_highlight'); ?>
      <?php else: ?>
        <?php $this->assign('class_style', ""); ?>
      <?php endif; ?>

      <tr class="<?php echo $this->_tpl_vars['class_style']; ?>
">
        <td align="center">
          <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
" value="1" />
          <input type="text" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_order" style="width: 30px;" value="<?php echo $this->_tpl_vars['count']; ?>
" tabindex="<?php echo $this->_tpl_vars['count']; ?>
" />
        </td>
        <td><input type="text" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_display_name" style="width: 97%" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['field_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" tabindex="<?php echo $this->_tpl_vars['count']+$this->_tpl_vars['total_num_fields']; ?>
" /></td>
        <td>
          <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>
            <span class="pad_left_small medium_grey"><?php echo $this->_tpl_vars['LANG']['word_na']; ?>
</span>
          <?php else: ?>
            <?php $this->assign('offset2', $this->_tpl_vars['total_num_fields']*2); ?>
            <input type="text" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_name" id="field_<?php echo $this->_tpl_vars['field_id']; ?>
_name" style="width: 97%;" value="<?php echo $this->_tpl_vars['field']['field_name']; ?>
" tabindex="<?php echo $this->_tpl_vars['count']+$this->_tpl_vars['offset2']; ?>
" />
          <?php endif; ?>
        </td>
        <td>
          <input type="hidden" name="old_field_<?php echo $this->_tpl_vars['field_id']; ?>
_type" value="<?php echo $this->_tpl_vars['field']['field_type']; ?>
" />

          <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>
            <span class="pad_left_small medium_grey">
            <?php if ($this->_tpl_vars['field']['col_name'] == 'ip_address'): ?>
              <?php echo $this->_tpl_vars['LANG']['phrase_ip_address']; ?>

              <script type="text/javascript">var g_ip_address_field=<?php echo $this->_tpl_vars['field_id']; ?>
</script>
            <?php elseif ($this->_tpl_vars['field']['col_name'] == 'submission_date'): ?>
              <?php echo $this->_tpl_vars['LANG']['phrase_submission_date']; ?>

            <?php elseif ($this->_tpl_vars['field']['col_name'] == 'last_modified_date'): ?>
              <?php echo $this->_tpl_vars['LANG']['phrase_last_modified_date']; ?>

            <?php elseif ($this->_tpl_vars['field']['col_name'] == 'submission_id'): ?>
              <?php echo $this->_tpl_vars['LANG']['phrase_submission_id']; ?>

            <?php endif; ?>
            </span>
            <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_type" value="system" />
          <?php else: ?>
            <?php $this->assign('offset3', $this->_tpl_vars['total_num_fields']*3); ?>
            <select name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_type" tabindex="<?php echo $this->_tpl_vars['count']+$this->_tpl_vars['offset3']; ?>
">
              <optgroup label="<?php echo $this->_tpl_vars['LANG']['phrase_standard_fields']; ?>
">
                <option value="textbox"       <?php if ($this->_tpl_vars['field']['field_type'] == 'textbox'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_textbox']; ?>
</option>
                <option value="textarea"      <?php if ($this->_tpl_vars['field']['field_type'] == 'textarea'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_textarea']; ?>
</option>
                <option value="password"      <?php if ($this->_tpl_vars['field']['field_type'] == 'password'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_password']; ?>
</option>
                <option value="select"        <?php if ($this->_tpl_vars['field']['field_type'] == 'select'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_dropdown']; ?>
</option>
                <option value="multi-select"  <?php if ($this->_tpl_vars['field']['field_type'] == "multi-select"): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['phrase_multi_select_dropdown']; ?>
</option>
                <option value="radio-buttons" <?php if ($this->_tpl_vars['field']['field_type'] == "radio-buttons"): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['phrase_radio_buttons']; ?>
</option>
                <option value="checkboxes"    <?php if ($this->_tpl_vars['field']['field_type'] == 'checkboxes'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_checkboxes']; ?>
</option>
              </optgroup>
              <optgroup label="<?php echo $this->_tpl_vars['LANG']['phrase_special_fields']; ?>
">
                <option value="file" <?php if ($this->_tpl_vars['field']['field_type'] == 'file'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_file']; ?>
</option>
                <option value="wysiwyg" <?php if ($this->_tpl_vars['field']['field_type'] == 'wysiwyg'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['phrase_wysiwyg_field']; ?>
</option>
              </optgroup>
            </select>
          <?php endif; ?>

        </td>
        <td align="center">
          <?php $this->assign('offset4', $this->_tpl_vars['total_num_fields']*4); ?>
          <input type="checkbox" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_include_on_redirect" <?php if ($this->_tpl_vars['field']['include_on_redirect'] == 'yes'): ?>checked<?php endif; ?> tabindex="<?php echo $this->_tpl_vars['count']+$this->_tpl_vars['offset4']; ?>
" />
        </td>
        <td align="center"><a href="edit.php?page=field_options&field_id=<?php echo $this->_tpl_vars['field_id']; ?>
"><?php echo $this->_tpl_vars['LANG']['word_options']; ?>
</a></td>
      </tr>

    <?php endforeach; endif; unset($_from); ?>

    </table>

    <input type="hidden" id="field_ids" value="<?php echo $this->_tpl_vars['field_ids']; ?>
" />

    <p>
      <input type="submit" name="update_fields" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
    </p>

  </form>