<?php /* Smarty version 2.6.18, created on 2015-10-02 12:30:18
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 1, false),array('function', 'display_edit_submission_view_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 13, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 27, false),array('function', 'submission_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 55, false),array('function', 'submission_radios', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 60, false),array('function', 'submission_checkboxes', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 65, false),array('function', 'submission_dropdown_multiple', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 70, false),array('function', 'display_file_field', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 76, false),array('function', 'module_function', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 93, false),array('function', 'display_email_template_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 211, false),array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 29, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 79, false),array('modifier', 'custom_format_date', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 131, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/edit_submission.tpl', 170, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <div style="width: 100%">

    <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td><span class="title"><?php echo $this->_tpl_vars['edit_submission_page_label']; ?>
</span></td>
      <td align="right">
        <div style="float:right; padding-left: 6px;">
          <a href="edit.php?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/edit_small.gif" border="0" alt="<?php echo $this->_tpl_vars['LANG']['phrase_edit_form']; ?>
"
            title="<?php echo $this->_tpl_vars['LANG']['phrase_edit_form']; ?>
" /></a>
        </div>
        <?php echo smarty_function_display_edit_submission_view_dropdown(array('form_id' => $this->_tpl_vars['form_id'],'view_id' => $this->_tpl_vars['view_id'],'submission_id' => $this->_tpl_vars['submission_id'],'account_id' => $this->_tpl_vars['SESSION']['account']['account_id'],'is_admin' => true), $this);?>

      </td>
    </tr>
    </table>

    <table cellpadding="0" cellspacing="0" class="pad_top_large pad_bottom_large">
    <tr>
      <td width="80" class="nowrap"><?php echo $this->_tpl_vars['previous_link_html']; ?>
</td>
      <td width="150" class="nowrap"><?php echo $this->_tpl_vars['search_results_link_html']; ?>
</td>
      <td><?php echo $this->_tpl_vars['next_link_html']; ?>
</td>
    </tr>
    </table>

    <?php echo smarty_function_template_hook(array('location' => 'admin_edit_submission_top'), $this);?>


    <?php if (count($this->_tpl_vars['tabs']) > 0): ?>
      <?php echo smarty_function_ft_include(array('file' => 'tabset_open.tpl'), $this);?>

    <?php endif; ?>

    <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


    <form action="edit_submission.php" method="post" name="edit_submission_form" enctype="multipart/form-data">
            <input type="hidden" name="form_id" id="form_id" value="<?php echo $this->_tpl_vars['form_id']; ?>
" />
      <input type="hidden" name="submission_id" id="submission_id" value="<?php echo $this->_tpl_vars['submission_id']; ?>
" />
      <input type="hidden" name="tab" id="tab" value="<?php echo $this->_tpl_vars['tab_number']; ?>
" />

      <?php if (count($this->_tpl_vars['submission_tab_fields']) > 0): ?>
        <table class="list_table" cellpadding="1" cellspacing="1" border="0" width="100%">
      <?php endif; ?>

            <?php $_from = $this->_tpl_vars['submission_tab_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['submission_field']):
?>
        <?php $this->assign('field_id', $this->_tpl_vars['submission_field']['field_id']); ?>

        <tr>
          <td width="150" class="pad_left_small"><?php echo $this->_tpl_vars['submission_field']['field_title']; ?>
</td>
          <td>

          <?php if ($this->_tpl_vars['submission_field']['field_type'] == 'select'): ?>

            <?php echo smarty_function_submission_dropdown(array('name' => $this->_tpl_vars['submission_field']['col_name'],'field_id' => $this->_tpl_vars['field_id'],'selected' => $this->_tpl_vars['submission_field']['content'],'is_editable' => $this->_tpl_vars['submission_field']['is_editable']), $this);?>


          <?php elseif ($this->_tpl_vars['submission_field']['field_type'] == "radio-buttons"): ?>

            <?php echo smarty_function_submission_radios(array('name' => $this->_tpl_vars['submission_field']['col_name'],'field_id' => $this->_tpl_vars['field_id'],'selected' => $this->_tpl_vars['submission_field']['content'],'is_editable' => $this->_tpl_vars['submission_field']['is_editable']), $this);?>


          <?php elseif ($this->_tpl_vars['submission_field']['field_type'] == 'checkboxes'): ?>

            <?php echo smarty_function_submission_checkboxes(array('name' => $this->_tpl_vars['submission_field']['col_name'],'field_id' => $this->_tpl_vars['field_id'],'selected' => $this->_tpl_vars['submission_field']['content'],'is_editable' => $this->_tpl_vars['submission_field']['is_editable']), $this);?>


          <?php elseif ($this->_tpl_vars['submission_field']['field_type'] == "multi-select"): ?>

            <?php echo smarty_function_submission_dropdown_multiple(array('name' => $this->_tpl_vars['submission_field']['col_name'],'field_id' => $this->_tpl_vars['field_id'],'selected' => $this->_tpl_vars['submission_field']['content'],'is_editable' => $this->_tpl_vars['submission_field']['is_editable']), $this);?>


          <?php elseif ($this->_tpl_vars['submission_field']['field_type'] == 'file'): ?>

            <span id="field_<?php echo $this->_tpl_vars['field_id']; ?>
_link" <?php if ($this->_tpl_vars['submission_field']['content'] == ""): ?>style="display:none"<?php endif; ?>>
              <?php echo smarty_function_display_file_field(array('field_id' => $this->_tpl_vars['field_id'],'filename' => $this->_tpl_vars['submission_field']['content']), $this);?>


              <?php if ($this->_tpl_vars['submission_field']['is_editable'] == 'yes'): ?>
                <input type="button" class="pad_left_large" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_delete_file'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" onclick="ms.delete_submission_file(<?php echo $this->_tpl_vars['field_id']; ?>
, 'file', false)" />
              <?php endif; ?>
            </span>

            <span id="field_<?php echo $this->_tpl_vars['field_id']; ?>
_upload_field" <?php if ($this->_tpl_vars['submission_field']['content'] != ""): ?>style="display:none"<?php endif; ?>>
              <?php if ($this->_tpl_vars['submission_field']['is_editable'] == 'yes'): ?>
                <input type="file" name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" />
              <?php endif; ?>
            </span>

            <span id="file_field_<?php echo $this->_tpl_vars['field_id']; ?>
_message_id"></span>

          <?php elseif ($this->_tpl_vars['submission_field']['field_type'] == 'image'): ?>

            <?php echo smarty_function_module_function(array('name' => 'display_image','type' => 'main_thumb','extended_field_info' => $this->_tpl_vars['image_field_info'][$this->_tpl_vars['field_id']],'field_id' => $this->_tpl_vars['field_id'],'image_info_string' => $this->_tpl_vars['submission_field']['content']), $this);?>


          <?php elseif ($this->_tpl_vars['submission_field']['field_type'] == 'system'): ?>

            <?php if ($this->_tpl_vars['submission_field']['col_name'] == 'submission_id'): ?>

              <b><?php echo $this->_tpl_vars['submission_field']['content']; ?>
</b>

            <?php elseif ($this->_tpl_vars['submission_field']['col_name'] == 'ip_address'): ?>

              <?php if ($this->_tpl_vars['submission_field']['is_editable'] == 'yes'): ?>
                <input type="text" style="width: 100px;" name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" value="<?php echo $this->_tpl_vars['submission_field']['content']; ?>
" />
              <?php else: ?>
                <?php echo $this->_tpl_vars['submission_field']['content']; ?>

              <?php endif; ?>

            <?php elseif ($this->_tpl_vars['submission_field']['col_name'] == 'submission_date'): ?>

              <?php if ($this->_tpl_vars['submission_field']['is_editable'] == 'yes'): ?>
                <table cellspacing="0" cellpadding="0">
                <tr>
                  <td><input type="text" style="width: 125px;" name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" id="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" value="<?php echo $this->_tpl_vars['submission_field']['content']; ?>
" /></td>
                  <td><img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/calendar_icon.gif" id="date_image_<?php echo $this->_tpl_vars['field_id']; ?>
" style="cursor:pointer" /></td>
                </tr>
                </table>
                <script type="text/javascript">
                <?php echo 'Calendar.setup({'; ?>

                   inputField     :    "<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
",
                   showsTime      :    true,
                   timeFormat     :    "24",
                   ifFormat       :    "%Y-%m-%d %H:%M:00",
                   button         :    "date_image_<?php echo $this->_tpl_vars['field_id']; ?>
",
                   align          :    "tr",
                   singleClick    :    true
                <?php echo '});'; ?>

                </script>
              <?php else: ?>
                <?php echo ((is_array($_tmp=$this->_tpl_vars['submission_field']['content'])) ? $this->_run_mod_handler('custom_format_date', true, $_tmp, $this->_tpl_vars['SESSION']['account']['timezone_offset'], $this->_tpl_vars['SESSION']['account']['date_format']) : smarty_modifier_custom_format_date($_tmp, $this->_tpl_vars['SESSION']['account']['timezone_offset'], $this->_tpl_vars['SESSION']['account']['date_format'])); ?>

              <?php endif; ?>

            <?php elseif ($this->_tpl_vars['submission_field']['col_name'] == 'last_modified_date'): ?>

              <?php if ($this->_tpl_vars['submission_field']['is_editable'] == 'yes'): ?>
                <table cellspacing="0" cellpadding="0">
                <tr>
                  <td><input type="text" style="width: 125px;" name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" id="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" value="<?php echo $this->_tpl_vars['submission_field']['content']; ?>
" /></td>
                  <td><img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/calendar_icon.gif" id="date_image_<?php echo $this->_tpl_vars['field_id']; ?>
" style="cursor:pointer" /></td>
                </tr>
                </table>
                <script type="text/javascript">
                <?php echo 'Calendar.setup({'; ?>

                   inputField     :    "<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
",
                   showsTime      :    true,
                   timeFormat     :    "24",
                   ifFormat       :    "%Y-%m-%d %H:%M:00",
                   button         :    "date_image_<?php echo $this->_tpl_vars['field_id']; ?>
",
                   align          :    "tr",
                   singleClick    :    true
                <?php echo '});'; ?>

                </script>
              <?php else: ?>
                <?php echo ((is_array($_tmp=$this->_tpl_vars['submission_field']['content'])) ? $this->_run_mod_handler('custom_format_date', true, $_tmp, $this->_tpl_vars['SESSION']['account']['timezone_offset'], $this->_tpl_vars['SESSION']['account']['date_format']) : smarty_modifier_custom_format_date($_tmp, $this->_tpl_vars['SESSION']['account']['timezone_offset'], $this->_tpl_vars['SESSION']['account']['date_format'])); ?>

              <?php endif; ?>

            <?php endif; ?>

          <?php elseif ($this->_tpl_vars['submission_field']['field_type'] == 'wysiwyg'): ?>
            <?php if ($this->_tpl_vars['submission_field']['is_editable'] == 'yes'): ?>
                            <textarea name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" id="field_<?php echo $this->_tpl_vars['field_id']; ?>
_wysiwyg" style="width: 100%; height: 160px"><?php echo $this->_tpl_vars['submission_field']['content']; ?>
</textarea>
            <?php else: ?>
              <?php echo $this->_tpl_vars['submission_field']['content']; ?>

            <?php endif; ?>

          <?php elseif ($this->_tpl_vars['submission_field']['field_type'] == 'password'): ?>
            <?php if ($this->_tpl_vars['submission_field']['is_editable'] == 'yes'): ?>
              <input type="password"  name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['submission_field']['content'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width: 150px;" />
            <?php endif; ?>
          <?php else: ?>

            <?php if ($this->_tpl_vars['submission_field']['is_editable'] == 'yes'): ?>
              <?php if ($this->_tpl_vars['submission_field']['field_size'] == 'tiny'): ?>
                <input type="text" name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['submission_field']['content'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width: 50px;" />
              <?php elseif ($this->_tpl_vars['submission_field']['field_size'] == 'small'): ?>
                <input type="text" name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['submission_field']['content'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width: 150px;" />
              <?php elseif ($this->_tpl_vars['submission_field']['field_size'] == 'medium'): ?>
                <input type="text" name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['submission_field']['content'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width: 99%;" />
              <?php elseif ($this->_tpl_vars['submission_field']['field_size'] == 'large' || $this->_tpl_vars['submission_field']['field_size'] == 'very_large'): ?>
                <textarea name="<?php echo $this->_tpl_vars['submission_field']['col_name']; ?>
" style="width: 99%; height: 80px"><?php echo $this->_tpl_vars['submission_field']['content']; ?>
</textarea>
              <?php endif; ?>
            <?php else: ?>
              <?php echo $this->_tpl_vars['submission_field']['content']; ?>

            <?php endif; ?>

          <?php endif; ?>

        </td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>

      <?php if (count($this->_tpl_vars['submission_tab_fields']) > 0): ?>
        </table>
      <?php endif; ?>

      <input type="hidden" name="field_ids" value="<?php echo $this->_tpl_vars['submission_tab_field_id_str']; ?>
" />

            <?php if (count($this->_tpl_vars['submission_tab_fields']) == 0): ?>
        <div><?php echo $this->_tpl_vars['LANG']['notify_no_fields_in_tab']; ?>
</div>
      <?php endif; ?>

      <br />

      <div style="position:relative">

        <span style="float:right">
                    <?php echo smarty_function_display_email_template_dropdown(array('form_id' => $this->_tpl_vars['form_id'],'view_id' => $this->_tpl_vars['view_id'],'submission_id' => $this->_tpl_vars['submission_id']), $this);?>

         </span>

                <?php if (count($this->_tpl_vars['submission_tab_fields']) > 0 && $this->_tpl_vars['tab_has_editable_fields']): ?>
          <input type="submit" name="update" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
        <?php endif; ?>

        <?php if ($this->_tpl_vars['view_info']['may_delete_submissions'] == 'yes'): ?>
           <input type="button" name="delete" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_delete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" class="red" onclick="return ms.delete_submission(<?php echo $this->_tpl_vars['submission_id']; ?>
, 'submissions.php')"/>
         <?php endif; ?>

      </div>

    </form>

    <?php if (count($this->_tpl_vars['tabs']) > 0): ?>
      <?php echo smarty_function_ft_include(array('file' => 'tabset_close.tpl'), $this);?>

    <?php endif; ?>

    <?php echo smarty_function_template_hook(array('location' => 'admin_edit_submission_bottom'), $this);?>


  </div>

<?php echo smarty_function_ft_include(array('file' => 'footer.tpl'), $this);?>