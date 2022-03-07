<?php /* Smarty version 2.6.18, created on 2015-10-02 12:39:40
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab1.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab1.tpl', 5, false),array('modifier', 'in_array', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab1.tpl', 22, false),array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab1.tpl', 49, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab1.tpl', 32, false),array('function', 'views_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab1.tpl', 132, false),)), $this); ?>
            <table cellpadding="2" cellspacing="1" width="100%">
            <tr>
              <td width="10" class="red">*</td>
              <td width="180"><?php echo $this->_tpl_vars['LANG']['phrase_email_template_name']; ?>
</td>
              <td><input type="text" name="email_template_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['template_info']['email_template_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:300px;" maxlength="100" /></td>
            </tr>
            <tr>
              <td class="red">*</td>
              <td><?php echo $this->_tpl_vars['LANG']['word_status']; ?>
</td>
              <td>
                <input type="radio" name="email_status" id="status_enabled" value="enabled" <?php if ($this->_tpl_vars['template_info']['email_status'] == 'enabled'): ?>checked<?php endif; ?> />
                  <label for="status_enabled" class="light_green"><?php echo $this->_tpl_vars['LANG']['word_enabled']; ?>
</label>
                <input type="radio" name="email_status" id="status_disabled" value="disabled" <?php if ($this->_tpl_vars['template_info']['email_status'] == 'disabled'): ?>checked<?php endif; ?> />
                  <label for="status_disabled" class="red"><?php echo $this->_tpl_vars['LANG']['word_disabled']; ?>
</label>
              </td>
            </tr>
            <tr>
              <td valign="top" class="red"> </td>
              <td valign="top"><?php echo $this->_tpl_vars['LANG']['phrase_event_trigger']; ?>
</td>
              <td>
                <input type="checkbox" name="email_event_trigger[]" id="eet1" value="on_submission"
                  <?php if (((is_array($_tmp='on_submission')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['template_info']['email_event_trigger']) : in_array($_tmp, $this->_tpl_vars['template_info']['email_event_trigger']))): ?>checked<?php endif; ?> />
                  <label for="eet1"><?php echo $this->_tpl_vars['LANG']['phrase_on_form_submission']; ?>
</label><br />
                <input type="checkbox" name="email_event_trigger[]" id="eet2" value="on_edit"
                  <?php if (((is_array($_tmp='on_edit')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['template_info']['email_event_trigger']) : in_array($_tmp, $this->_tpl_vars['template_info']['email_event_trigger']))): ?>checked<?php endif; ?> />
                  <label for="eet2"><?php echo $this->_tpl_vars['LANG']['phrase_when_submission_is_edited']; ?>
</label><br />
                <input type="checkbox" name="email_event_trigger[]" id="eet3" value="on_delete"
                  <?php if (((is_array($_tmp='on_delete')) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['template_info']['email_event_trigger']) : in_array($_tmp, $this->_tpl_vars['template_info']['email_event_trigger']))): ?>checked<?php endif; ?> />
                  <label for="eet3"><?php echo $this->_tpl_vars['LANG']['phrase_when_submission_is_deleted']; ?>
</label><br />
              </td>
            </tr>
            <?php echo smarty_function_template_hook(array('location' => 'edit_template_tab1'), $this);?>

            </table>

            <div class="grey_box">
              <div style="margin_top">
                <a href="#" onclick="return page_ns.toggle_advanced_settings()"><?php echo $this->_tpl_vars['LANG']['phrase_advanced_settings_rightarrow']; ?>
</a>
              </div>

              <div <?php if (! isset ( $this->_tpl_vars['SESSION']['edit_email_advanced_settings'] ) || $this->_tpl_vars['SESSION']['edit_email_advanced_settings'] == 'false'): ?>style="display:none"<?php endif; ?> id="advanced_settings">
                <table cellpadding="2" cellspacing="1" width="100%">
                <tr>
                  <td valign="top" class="red" width="10">*</td>
                  <td valign="top" width="180"><?php echo $this->_tpl_vars['LANG']['phrase_when_sent']; ?>
</td>
                  <td>
                    <input type="radio" name="view_mapping_type" id="vmt1" value="all" <?php if ($this->_tpl_vars['template_info']['view_mapping_type'] == 'all'): ?>checked<?php endif; ?> />
                      <label for="vmt1"><?php echo $this->_tpl_vars['LANG']['phrase_for_any_form_submission']; ?>
</label><br />
                    <input type="radio" name="view_mapping_type" id="vmt2" value="specific" <?php if ($this->_tpl_vars['template_info']['view_mapping_type'] == 'specific'): ?>checked<?php endif; ?>
                      <?php if (count($this->_tpl_vars['filtered_views']) == 0): ?>disabled<?php endif; ?> />
                      <label for="vmt2"><?php echo $this->_tpl_vars['LANG']['phrase_for_view_submissions']; ?>
</label>
                      <?php if (count($this->_tpl_vars['filtered_views']) == 0): ?>
                        <select disabled>
                          <option><?php echo $this->_tpl_vars['LANG']['phrase_no_views']; ?>
</option>
                        </select>
                      <?php else: ?>
                        <select name="view_mapping_view_id">
                          <option value=""><?php echo $this->_tpl_vars['LANG']['phrase_please_select']; ?>
</option>
                          <?php $_from = $this->_tpl_vars['filtered_views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['view_info']):
?>
                          <option value="<?php echo $this->_tpl_vars['view_info']['view_id']; ?>
" <?php if ($this->_tpl_vars['template_info']['view_mapping_view_id'] == $this->_tpl_vars['view_info']['view_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['view_info']['view_name']; ?>
</option>
                          <?php endforeach; endif; unset($_from); ?>
                        </select>
                      <?php endif; ?>

                      <div class="medium_grey pad_top_small"><?php echo $this->_tpl_vars['LANG']['text_list_views_with_filters']; ?>
</div>
                  </td>
                </tr>
                <tr>
                  <td class="red" valign="top">*</td>
                  <td valign="top">
                    <?php echo $this->_tpl_vars['LANG']['text_send_email_from_edit_submission_page']; ?>

                  </td>
                  <td>
                    <input type="radio" name="include_on_edit_submission_page" id="iesp1" value="no"
                      onchange="page_ns.change_include_on_edit_submission_page(this.value)"
                      <?php if ($this->_tpl_vars['template_info']['include_on_edit_submission_page'] == 'no'): ?>checked<?php endif; ?> />
                      <label for="iesp1"><?php echo $this->_tpl_vars['LANG']['word_no']; ?>
</label><br />
                    <input type="radio" name="include_on_edit_submission_page" id="iesp2" value="all_views"
                      onchange="page_ns.change_include_on_edit_submission_page(this.value)"
                      <?php if ($this->_tpl_vars['template_info']['include_on_edit_submission_page'] == 'all_views'): ?>checked<?php endif; ?> />
                      <label for="iesp2"><?php echo $this->_tpl_vars['LANG']['phrase_yes_for_all_views']; ?>
</label><br />
                    <input type="radio" name="include_on_edit_submission_page" id="iesp3" value="specific_views"
                      onchange="page_ns.change_include_on_edit_submission_page(this.value)"
                      <?php if ($this->_tpl_vars['template_info']['include_on_edit_submission_page'] == 'specific_views'): ?>checked<?php endif; ?> />
                      <label for="iesp3"><?php echo $this->_tpl_vars['LANG']['phrase_yes_for_specific_views']; ?>
</label><br />

                    <div id="include_on_edit_submission_page_views"
                      <?php if ($this->_tpl_vars['template_info']['include_on_edit_submission_page'] != 'specific_views'): ?>style="display:none"<?php endif; ?>>
                      <table width="100%">
                      <tr>
                        <td>
                          <select name="available_edit_submission_views[]" id="available_edit_submission_views" multiple size="4" style="width:160px">
                            <?php $_from = $this->_tpl_vars['views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['view_info']):
?>

                              <?php $this->assign('is_found', false); ?>
                              <?php $_from = $this->_tpl_vars['template_info']['edit_submission_page_view_ids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['curr_view_id']):
?>
                                <?php if ($this->_tpl_vars['curr_view_id'] == $this->_tpl_vars['view_info']['view_id']): ?>
                                  <?php $this->assign('is_found', true); ?>
                                <?php endif; ?>
                              <?php endforeach; endif; unset($_from); ?>

                              <?php if (! $this->_tpl_vars['is_found']): ?>
                                <option value="<?php echo $this->_tpl_vars['view_info']['view_id']; ?>
"><?php echo $this->_tpl_vars['view_info']['view_name']; ?>
</option>
                              <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?>
                          </select>
                        </td>
                        <td valign="center" align="center">
                          <span id="row_<?php echo $this->_tpl_vars['row']; ?>
_actions">
                            <input type="button" onclick="return ft.move_options($('available_edit_submission_views'), $('selected_edit_submission_views'))" value="<?php echo $this->_tpl_vars['LANG']['word_add_uc_rightarrow']; ?>
" /><br />
                            <input type="button" onclick="return ft.move_options($('selected_edit_submission_views'), $('available_edit_submission_views'))" value="<?php echo $this->_tpl_vars['LANG']['word_remove_uc_leftarrow']; ?>
" />
                          </span>
                        </td>
                        <td>
                          <select name="selected_edit_submission_views[]" id="selected_edit_submission_views" multiple size="4" style="width:160px">
                            <?php $_from = $this->_tpl_vars['selected_edit_submission_views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['view_info']):
?>
                              <option value="<?php echo $this->_tpl_vars['view_info']['view_id']; ?>
"><?php echo $this->_tpl_vars['view_info']['view_name']; ?>
</option>
                            <?php endforeach; endif; unset($_from); ?>
                          </select>
                        </td>
                      </tr>
                      </table>
                    </div>

                  </td>
                </tr>
                <tr>
                  <td class="red" valign="top">*</td>
                  <td valign="top">
                    <?php echo $this->_tpl_vars['LANG']['phrase_limit_email_content']; ?>

                  </td>
                  <td>
                    <?php echo smarty_function_views_dropdown(array('name_id' => 'limit_email_content_to_fields_in_view','form_id' => $this->_tpl_vars['form_id'],'default' => $this->_tpl_vars['template_info']['limit_email_content_to_fields_in_view']), $this);?>

                    <div class="medium_grey">
                      This option only works for HTML and text content generated with Smarty Loops.
                    </div>
                  </td>
                </tr>
                </table>

                <?php echo smarty_function_template_hook(array('location' => 'edit_template_tab1_advanced'), $this);?>


              </div>
            </div>