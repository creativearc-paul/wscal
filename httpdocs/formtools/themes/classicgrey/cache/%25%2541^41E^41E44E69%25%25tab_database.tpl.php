<?php /* Smarty version 2.6.18, created on 2016-10-28 11:41:35
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_database.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_database.tpl', 2, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_database.tpl', 10, false),)), $this); ?>

  <div class="subtitle underline margin_bottom_large margin_top_large"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_database'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

  <div class="error">
    <div style="padding: 8px;">
      <b><?php echo $this->_tpl_vars['LANG']['word_warning_c']; ?>
</b> <?php echo $this->_tpl_vars['LANG']['text_delete_field_warning']; ?>

    </div>
  </div>

  <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


  <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post" onsubmit="return rsv.validate(this, rules)">

    <table class="list_table" width="600" cellpadding="0" cellspacing="1">
    <tr style="height: 20px;">
      <th><?php echo $this->_tpl_vars['LANG']['phrase_display_text']; ?>
</th>
      <th width="150"><?php echo $this->_tpl_vars['LANG']['phrase_field_size']; ?>
</th>
      <th width="60"><?php echo $this->_tpl_vars['LANG']['phrase_data_type']; ?>
</th>
      <th width="160" nowrap><?php echo $this->_tpl_vars['LANG']['phrase_db_column']; ?>
<span class="pad_right">&nbsp;</span><input type="button" value="<?php echo $this->_tpl_vars['LANG']['phrase_smart_fill']; ?>
" onclick="return page_ns.smart_fill()" class="bold"/></th>
      <th width="50" class="del"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_delete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
    </tr>

    <?php $_from = $this->_tpl_vars['form_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['field']):
        $this->_foreach['row']['iteration']++;
?>
      <?php $this->assign('field_id', $this->_tpl_vars['field']['field_id']); ?>
      <?php $this->assign('count', $this->_foreach['row']['iteration']); ?>

      <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>
        <?php $this->assign('class_style', 'row_highlight'); ?>
      <?php else: ?>
        <?php $this->assign('class_style', ""); ?>
      <?php endif; ?>

      <tr class="<?php echo $this->_tpl_vars['class_style']; ?>
">
        <td class="pad_left_small"><?php echo $this->_tpl_vars['field']['field_title']; ?>
</td>
        <td>

        <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_type" value="<?php echo $this->_tpl_vars['field']['field_type']; ?>
" />

        <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>
          <span class="pad_left_small medium_grey"><?php echo $this->_tpl_vars['LANG']['word_na']; ?>
</span>
        <?php elseif ($this->_tpl_vars['field']['field_type'] == 'image'): ?>
          <span class="pad_left_small medium_grey"><?php echo $this->_tpl_vars['LANG']['phrase_size_large']; ?>
</span>
          <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_size" value="large" />
        <?php elseif ($this->_tpl_vars['field']['field_type'] == 'file'): ?>
          <span class="pad_left_small medium_grey"><?php echo $this->_tpl_vars['LANG']['phrase_size_medium']; ?>
</span>
          <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_size" value="medium" />
        <?php else: ?>
          <select name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_size" tabindex="<?php echo $this->_tpl_vars['count']; ?>
">
            <option <?php if ($this->_tpl_vars['field']['field_size'] == 'tiny'): ?>selected<?php endif; ?> value="tiny"><?php echo $this->_tpl_vars['LANG']['phrase_size_tiny']; ?>
</option>
            <option <?php if ($this->_tpl_vars['field']['field_size'] == 'small'): ?>selected<?php endif; ?> value="small"><?php echo $this->_tpl_vars['LANG']['phrase_size_small']; ?>
</option>
            <option <?php if ($this->_tpl_vars['field']['field_size'] == 'medium'): ?>selected<?php endif; ?> value="medium"><?php echo $this->_tpl_vars['LANG']['phrase_size_medium']; ?>
</option>
            <option <?php if ($this->_tpl_vars['field']['field_size'] == 'large'): ?>selected<?php endif; ?> value="large"><?php echo $this->_tpl_vars['LANG']['phrase_size_large']; ?>
</option>
            <option <?php if ($this->_tpl_vars['field']['field_size'] == 'very_large'): ?>selected<?php endif; ?> value="very_large"><?php echo $this->_tpl_vars['LANG']['phrase_size_very_large']; ?>
	</option>
           </select>
        <?php endif; ?>

      </td>
      <td>

        <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>
          <span class="pad_left_small medium_grey"><?php echo $this->_tpl_vars['LANG']['word_na']; ?>
</span>
        <?php elseif ($this->_tpl_vars['field']['field_type'] == 'image' || $this->_tpl_vars['field']['field_type'] == 'file'): ?>
          <span class="pad_left_small medium_grey"><?php echo $this->_tpl_vars['LANG']['word_string']; ?>
</span>
          <input type="hidden" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_data_type" value="string" />
        <?php else: ?>
          <select name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_data_type" tabindex="<?php echo $this->_tpl_vars['count']+10000; ?>
">
            <option <?php if ($this->_tpl_vars['field']['data_type'] == 'string'): ?>selected<?php endif; ?> value="string"><?php echo $this->_tpl_vars['LANG']['word_string']; ?>
</option>
            <option <?php if ($this->_tpl_vars['field']['data_type'] == 'number'): ?>selected<?php endif; ?> value="number"><?php echo $this->_tpl_vars['LANG']['word_number']; ?>
</option>
          </select>
        <?php endif; ?>

      </td>
      <td class="greyCell">

      <?php if ($this->_tpl_vars['field']['field_type'] == 'system'): ?>
        <span class="pad_left_small medium_grey"><?php echo $this->_tpl_vars['field']['col_name']; ?>
</span>
      <?php else: ?>
        <input type="text" name="col_<?php echo $this->_tpl_vars['field_id']; ?>
_name" id="col_<?php echo $this->_tpl_vars['field_id']; ?>
_name" style="width: 95%;" value="<?php echo $this->_tpl_vars['field']['col_name']; ?>
" tabindex="<?php echo $this->_tpl_vars['count']+20000; ?>
" />
      <?php endif; ?>

      </td>
      <td class="del">
      <?php if ($this->_tpl_vars['field']['field_type'] != 'system'): ?>
        <input type="checkbox" name="field_<?php echo $this->_tpl_vars['field_id']; ?>
_remove" />
      <?php endif; ?>
      </td>
    </tr>

    <?php endforeach; endif; unset($_from); ?>
    </table>

    <br />

    <table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
      <td>
        <input type="submit" name="update_database" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
      </td>
      <td align="right">
        <?php echo $this->_tpl_vars['LANG']['word_add']; ?>
 <input type="text" name="num_fields" size="3" value="1" /><input type="submit" name="add_field" value="<?php echo $this->_tpl_vars['LANG']['word_field_sp']; ?>
" />
      </td>
    </tr>
    </table>

  </form>