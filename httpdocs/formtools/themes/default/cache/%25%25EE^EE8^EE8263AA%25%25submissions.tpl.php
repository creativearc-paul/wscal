<?php /* Smarty version 2.6.18, created on 2010-12-01 16:50:45
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 1, false),array('function', 'form_view_fields_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 54, false),array('function', 'date_range_search_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 64, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 100, false),array('function', 'display_multi_select_field_values', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 188, false),array('function', 'module_function', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 216, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 6, false),array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 12, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 74, false),array('modifier', 'cat', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 112, false),array('modifier', 'in_array', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 154, false),array('modifier', 'custom_format_date', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/submissions.tpl', 197, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="45"><a href="./"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_forms.gif" border="0" width="34" height="34" /></a></td>
    <td class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['form_info']['form_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
    <td align="right" valign="top">
      <div style="float:right; padding-left: 6px;">
        <a href="edit.php?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/edit_small.gif" border="0" alt="<?php echo $this->_tpl_vars['LANG']['phrase_edit_form']; ?>
"
          title="<?php echo $this->_tpl_vars['LANG']['phrase_edit_form']; ?>
" /></a>
      </div>
      <?php if (count($this->_tpl_vars['form_views']) > 1): ?>
        <select onchange="window.location='<?php echo $this->_tpl_vars['same_page']; ?>
?page=1&view=' + this.value">
          <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_views']; ?>
">
          <?php $_from = $this->_tpl_vars['form_views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
            <option value="<?php echo $this->_tpl_vars['i']['view_id']; ?>
" <?php if ($this->_tpl_vars['view_id'] == $this->_tpl_vars['i']['view_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['i']['view_name']; ?>
</option>
          <?php endforeach; endif; unset($_from); ?>
          </optgroup>
        </select>
      <?php endif; ?>
    </td>
  </tr>
  </table>

    <?php if ($this->_tpl_vars['total_form_submissions'] == 0): ?>
    <p>
      <?php echo $this->_tpl_vars['LANG']['text_no_submissions_found']; ?>

    </p>

    <?php if ($this->_tpl_vars['view_info']['may_add_submissions'] == 'yes'): ?>
      <input type="button" id="add_submission" value="<?php echo $this->_tpl_vars['LANG']['word_add']; ?>
" onclick="window.location='<?php echo $this->_tpl_vars['same_page']; ?>
?add_submission'" />
    <?php endif; ?>

  <?php else: ?>

  <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


  <div id="search_form">

    <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post" name="search_form" onsubmit="return rsv.validate(this, rules)">
      <input type="hidden" name="search" value="1" />
      <input type="hidden" name="select_all" value="<?php if ($this->_tpl_vars['curr_view_select_all'] == 'yes'): ?>1<?php endif; ?>"  />

      <table cellspacing="0" cellpadding="0" id="search_form_table">
      <tr>
        <td class="blue" width="70"><?php echo $this->_tpl_vars['LANG']['word_search']; ?>
</td>
        <td>

          <table cellspacing="2" cellpadding="0">
          <tr>
            <td>
              <?php echo smarty_function_form_view_fields_dropdown(array('name_id' => 'search_field','form_id' => $this->_tpl_vars['form_id'],'view_id' => $this->_tpl_vars['view_id'],'blank_option_value' => 'all','blank_option_text' => $this->_tpl_vars['LANG']['phrase_all_fields'],'onchange' => "ms.change_search_field(this.value)",'onkeyup' => "ms.change_search_field(this.value)",'default' => $this->_tpl_vars['curr_search_fields']['search_field']), $this);?>

            </td>
            <td>
              <div id="search_dropdown_section"
                <?php if ($this->_tpl_vars['curr_search_fields']['search_field'] != 'submission_date' && $this->_tpl_vars['curr_search_fields']['search_field'] != 'last_modified_date'): ?>style="display: none"<?php endif; ?>>

                <?php echo smarty_function_date_range_search_dropdown(array('name_id' => 'search_date','form_id' => $this->_tpl_vars['form_id'],'view_id' => $this->_tpl_vars['view_id'],'default' => $this->_tpl_vars['curr_search_fields']['search_date']), $this);?>

              </div>
            </td>
          </tr>
          </table>

        </td>
        <td width="20" align="center"><?php echo $this->_tpl_vars['LANG']['word_for']; ?>
</td>
        <td>
          <input type="text" style="width: 120px;" name="search_keyword" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['curr_search_fields']['search_keyword'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
        </td>
        <td>
          <input type="submit" name="search" value="<?php echo $this->_tpl_vars['LANG']['word_search']; ?>
" />
          <input type="button" name="" value="<?php echo $this->_tpl_vars['LANG']['phrase_show_all']; ?>
" onclick="window.location='submissions.php?page=1&reset=1'"
            <?php if ($this->_tpl_vars['search_num_results'] < $this->_tpl_vars['view_num_results']): ?>class="bold"<?php endif; ?> />
        </td>
      </tr>
      </table>
    </form>
  </div>

  <br />

  <?php echo $this->_tpl_vars['pagination']; ?>


  <?php if ($this->_tpl_vars['search_num_results'] == 0): ?>
    <div class="notify yellow_bg">
      <div style="padding:8px">
        <?php echo $this->_tpl_vars['LANG']['text_no_search_results']; ?>

      </div>
    </div>
  <?php else: ?>

    <form name="current_form" action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post">

    <?php echo smarty_function_template_hook(array('location' => 'admin_submission_listings_top'), $this);?>


    <table class="submissions_table" id="submissions_table" cellpadding="1" cellspacing="1" border="0" width="650">
    <tr>
      <th align="center" width="25"> </th>
      <?php $_from = $this->_tpl_vars['display_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>

        <?php if ($this->_tpl_vars['i']['is_sortable'] == 'yes'): ?>

          <?php $this->assign('up_down', ""); ?>

                    <?php if ($this->_tpl_vars['order'] == ((is_array($_tmp=$this->_tpl_vars['i']['col_name'])) ? $this->_run_mod_handler('cat', true, $_tmp, '-DESC') : smarty_modifier_cat($_tmp, '-DESC'))): ?>
            <?php $this->assign('order_col', "&order=".($this->_tpl_vars['i']['col_name'])."-ASC"); ?>
            <?php $this->assign('up_down', "<img src=\"".($this->_tpl_vars['theme_url'])."/images/sort_down.gif\" />"); ?>
          <?php elseif ($this->_tpl_vars['order'] == ((is_array($_tmp=$this->_tpl_vars['i']['col_name'])) ? $this->_run_mod_handler('cat', true, $_tmp, '-ASC') : smarty_modifier_cat($_tmp, '-ASC'))): ?>
            <?php $this->assign('order_col', "&order=".($this->_tpl_vars['i']['col_name'])."-DESC"); ?>
            <?php $this->assign('up_down', "<img src=\"".($this->_tpl_vars['theme_url'])."/images/sort_up.gif\" />"); ?>
          <?php else: ?>
            <?php $this->assign('order_col', "&order=".($this->_tpl_vars['i']['col_name'])."-DESC"); ?>
          <?php endif; ?>

          <?php if ($this->_tpl_vars['i']['col_name'] == 'submission_date' || $this->_tpl_vars['i']['col_name'] == 'last_modified_date'): ?>
            <th class="nowrap pad_right">
          <?php else: ?>
            <th class="nowrap pad_right">
          <?php endif; ?>

            <table cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td><a href="<?php echo $this->_tpl_vars['same_page']; ?>
?<?php echo $this->_tpl_vars['pass_along_str']; ?>
<?php echo $this->_tpl_vars['order_col']; ?>
"><?php echo $this->_tpl_vars['i']['field_title']; ?>
</a></td>
              <td class="pad_left"><?php echo $this->_tpl_vars['up_down']; ?>
</td>
            </tr>
            </table>

          </th>
        <?php else: ?>
          <th><?php echo $this->_tpl_vars['i']['field_title']; ?>
</th>
        <?php endif; ?>

      <?php endforeach; endif; unset($_from); ?>
      <th width="50">
        <?php if ($this->_tpl_vars['view_info']['may_edit_submissions'] == 'yes'): ?>
          <?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_edit'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

        <?php else: ?>
          <?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_view'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

        <?php endif; ?>
      </th>
    </tr>

    <?php $_from = $this->_tpl_vars['search_rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['search_row']):
?>
      <?php $this->assign('submission_id', $this->_tpl_vars['search_row']['submission_id']); ?>

        <?php $this->assign('precheck', ""); ?>
        <?php if (((is_array($_tmp=$this->_tpl_vars['submission_id'])) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['preselected_subids']) : in_array($_tmp, $this->_tpl_vars['preselected_subids']))): ?>
          <?php $this->assign('precheck', 'checked'); ?>
        <?php endif; ?>

        <tr id="submission_row_<?php echo $this->_tpl_vars['submission_id']; ?>
" class="unselected_row_color">
          <td align="center"><input type="checkbox" id="submission_cb_<?php echo $this->_tpl_vars['submission_id']; ?>
" name="submissions[]" value="<?php echo $this->_tpl_vars['submission_id']; ?>
"
            onchange="ms.select_row(<?php echo $this->_tpl_vars['submission_id']; ?>
, <?php echo $this->_tpl_vars['results_per_page']; ?>
)" <?php echo $this->_tpl_vars['precheck']; ?>
 />&nbsp;</td>

                <?php $_from = $this->_tpl_vars['display_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k2'] => $this->_tpl_vars['curr_field']):
?>
          <?php $this->assign('field_id', $this->_tpl_vars['curr_field']['field_id']); ?>
          <?php $this->assign('field_type', $this->_tpl_vars['curr_field']['field_info']['field_type']); ?>
          <?php $this->assign('col_name', $this->_tpl_vars['curr_field']['col_name']); ?>

          <?php $this->assign('nowrap_rightpad', ""); ?>
          <?php $this->assign('ellipsis', 'ellipsis'); ?>
          <?php $this->assign('td_class', ""); ?>
          <?php $this->assign('cell_value', ""); ?>

                    <?php if ($this->_tpl_vars['field_type'] == 'select' || $this->_tpl_vars['field_type'] == "radio-buttons"): ?>

            <?php $this->assign('val', $this->_tpl_vars['search_row'][$this->_tpl_vars['col_name']]); ?>

            <?php $_from = $this->_tpl_vars['curr_field']['field_info']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k3'] => $this->_tpl_vars['option']):
?>
              <?php if ($this->_tpl_vars['option']['option_value'] == $this->_tpl_vars['val']): ?>
                <?php $this->assign('cell_value', $this->_tpl_vars['option']['option_name']); ?>
              <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>

          <?php elseif ($this->_tpl_vars['field_type'] == 'checkboxes' || $this->_tpl_vars['field_type'] == "multi-select"): ?>
            <?php $this->assign('value', $this->_tpl_vars['search_row'][$this->_tpl_vars['col_name']]); ?>

                        <?php echo smarty_function_display_multi_select_field_values(array('options' => $this->_tpl_vars['curr_field']['field_info']['options'],'values' => $this->_tpl_vars['value'],'var_name' => 'cell_value'), $this);?>


          <?php elseif ($this->_tpl_vars['field_type'] == 'system'): ?>

            <?php if ($this->_tpl_vars['col_name'] == 'submission_id'): ?>
              <?php $this->assign('td_class', 'submission_id'); ?>
              <?php $this->assign('cell_value', $this->_tpl_vars['submission_id']); ?>
            <?php elseif ($this->_tpl_vars['col_name'] == 'submission_date'): ?>
              <?php $this->assign('td_class', 'dates'); ?>
              <?php $this->assign('cell_value', ((is_array($_tmp=$this->_tpl_vars['search_row']['submission_date'])) ? $this->_run_mod_handler('custom_format_date', true, $_tmp, $this->_tpl_vars['SESSION']['account']['timezone_offset'], $this->_tpl_vars['SESSION']['account']['date_format']) : smarty_modifier_custom_format_date($_tmp, $this->_tpl_vars['SESSION']['account']['timezone_offset'], $this->_tpl_vars['SESSION']['account']['date_format']))); ?>
            <?php elseif ($this->_tpl_vars['col_name'] == 'last_modified_date'): ?>
              <?php $this->assign('td_class', 'dates'); ?>
              <?php $this->assign('cell_value', ((is_array($_tmp=$this->_tpl_vars['search_row']['last_modified_date'])) ? $this->_run_mod_handler('custom_format_date', true, $_tmp, $this->_tpl_vars['SESSION']['account']['timezone_offset'], $this->_tpl_vars['SESSION']['account']['date_format']) : smarty_modifier_custom_format_date($_tmp, $this->_tpl_vars['SESSION']['account']['timezone_offset'], $this->_tpl_vars['SESSION']['account']['date_format']))); ?>
            <?php elseif ($this->_tpl_vars['col_name'] == 'ip_address'): ?>
              <?php $this->assign('td_class', 'ip_address'); ?>
              <?php $this->assign('cell_value', $this->_tpl_vars['search_row']['ip_address']); ?>
            <?php endif; ?>

                        <?php $this->assign('ellipsis', ""); ?>

                        <?php $this->assign('nowrap_rightpad', 'nowrap pad_right_small'); ?>

          <?php elseif ($this->_tpl_vars['field_type'] == 'image'): ?>

                        <?php echo smarty_function_module_function(array('name' => 'display_image','type' => 'search_results_thumb','field_id' => $this->_tpl_vars['field_id'],'image_info_string' => $this->_tpl_vars['search_row'][$this->_tpl_vars['display_field']['col_name']],'var_name' => 'cell_value'), $this);?>


          <?php else: ?>
            <?php $this->assign('cell_value', $this->_tpl_vars['search_row'][$this->_tpl_vars['col_name']]); ?>
          <?php endif; ?>

          <td class="<?php echo $this->_tpl_vars['td_class']; ?>
"><div class="<?php echo $this->_tpl_vars['nowrap_rightpad']; ?>
 <?php echo $this->_tpl_vars['ellipsis']; ?>
 <?php echo $this->_tpl_vars['td_class']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['cell_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
        <?php endforeach; endif; unset($_from); ?>

        <td align="center"><a href="edit_submission.php?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
&view_id=<?php echo $this->_tpl_vars['view_id']; ?>
&submission_id=<?php echo $this->_tpl_vars['submission_id']; ?>
"><?php if ($this->_tpl_vars['view_info']['may_edit_submissions'] == 'yes'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_edit'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_view'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
<?php endif; ?></a></td>
      </tr>

    <?php endforeach; endif; unset($_from); ?>
    </table>

    <div style="padding-top: 5px; padding-bottom: 5px;">
      <div style="float:right; padding:1px" id="display_num_selected_rows"
        <?php if (count($this->_tpl_vars['preselected_subids']) == 0): ?>
          class="light_grey"
        <?php else: ?>
          class="green"
        <?php endif; ?>>
      </div>

      <?php echo smarty_function_template_hook(array('location' => 'admin_submission_listings_buttons1'), $this);?>


      <?php if ($this->_tpl_vars['view_info']['may_add_submissions'] == 'yes'): ?>
        <input type="button" id="add_submission" value="<?php echo $this->_tpl_vars['LANG']['word_add']; ?>
" onclick="window.location='<?php echo $this->_tpl_vars['same_page']; ?>
?add_submission'" />
      <?php endif; ?>

      <?php echo smarty_function_template_hook(array('location' => 'admin_submission_listings_buttons2'), $this);?>


      <input type="button" id="select_button" value="<?php echo $this->_tpl_vars['LANG']['phrase_select_all_on_page']; ?>
" onclick="ms.select_all_on_page();" />
      <input type="button" id="unselect_button" value="<?php echo $this->_tpl_vars['LANG']['phrase_unselect_all']; ?>
" onclick="ms.unselect_all()" />

      <?php echo smarty_function_template_hook(array('location' => 'admin_submission_listings_buttons3'), $this);?>


      <?php if ($this->_tpl_vars['view_info']['may_delete_submissions'] == 'yes'): ?>
        <input type="button" value="<?php echo $this->_tpl_vars['LANG']['word_delete']; ?>
" class="red" onclick="ms.delete_submissions()" />
      <?php endif; ?>

      <?php echo smarty_function_template_hook(array('location' => 'admin_submission_listings_buttons4'), $this);?>

    </div>

    <?php echo smarty_function_template_hook(array('location' => 'admin_submission_listings_bottom'), $this);?>


    </form>


        <?php echo smarty_function_module_function(array('name' => 'export_manager_export_options','account_type' => 'admin','account_id' => $this->_tpl_vars['SESSION']['account']['account_id']), $this);?>


    <?php endif; ?>

  <?php endif; ?>

<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>