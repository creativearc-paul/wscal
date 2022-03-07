<?php /* Smarty version 2.6.18, created on 2015-10-02 12:39:27
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_main.tpl', 1, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_main.tpl', 39, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_main.tpl', 3, false),array('function', 'clients_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_main.tpl', 158, false),)), $this); ?>
  <div class="subtitle underline margin_top_large"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_main_settings'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

  <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


  <form method="post" name="add_form" id="add_form" action="<?php echo $this->_tpl_vars['same_page']; ?>
" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="form_id" value="<?php echo $this->_tpl_vars['form_id']; ?>
" />

    <table class="list_table" width="100%" cellpadding="0" cellspacing="1">
    <tr>
      <td class="red" width="15" valign="top" align="center">*</td>
      <td class="pad_left_small" valign="top"><?php echo $this->_tpl_vars['LANG']['word_status']; ?>
</td>
      <td>
        <input type="radio" name="active" id="active1" value="yes" <?php if ($this->_tpl_vars['form_info']['is_active'] == 'yes'): ?>checked<?php endif; ?> />
          <label for="active1" class="light_green"><?php echo $this->_tpl_vars['LANG']['word_online']; ?>
 <?php echo $this->_tpl_vars['LANG']['phrase_accepting_submissions']; ?>
</label><br />
        <input type="radio" name="active" id="active2" value="no" <?php if ($this->_tpl_vars['form_info']['is_active'] == 'no'): ?>checked<?php endif; ?> />
          <label for="active2" class="orange"><?php echo $this->_tpl_vars['LANG']['word_offline']; ?>
</label>
      </td>
    </tr>
    <tr>
      <td class="red" align="center">*</td>
      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_form_name']; ?>
</td>
      <td><input type="text" name="form_name" value="<?php echo $this->_tpl_vars['form_info']['form_name']; ?>
" style="width: 99%" /></td>
    </tr>
    <tr>
      <td valign="top" class="red" align="center">*</td>
      <td valign="top" class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_form_url_sp']; ?>
</td>
      <td>

        <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <input type="hidden" id="original_form_url" value="<?php echo $this->_tpl_vars['form_info']['form_url']; ?>
" />
            <input type="text" name="form_url" id="form_url" value="<?php echo $this->_tpl_vars['form_info']['form_url']; ?>
" style="width: 98%"
              onkeyup="mf_ns.unverify_url_field(this.value, $('original_form_url').value, 1)" />
          </td>
          <td width="60" align="center">
            <?php if ($this->_tpl_vars['form_info']['form_url'] != ""): ?>
              <input type="button" id="form_url_1_button" class="green" onclick="ft.verify_url($('form_url'), 1)"
                value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_verified'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
              <input type="hidden" id="form_url_1_verified" name="form_url_verified" value="yes" />
            <?php else: ?>
              <input type="button" id="form_url_1_button" class="light_grey" onclick="ft.verify_url($('form_url'), 1)"
                value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_verify_url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
              <input type="hidden" id="form_url_1_verified" name="form_url_verified" value="no" />
            <?php endif; ?>
          </td>
        </tr>
        </table>

        <input type="checkbox" name="is_multi_page_form" id="is_multi_page_form" onclick="mf_ns.toggle_multi_page_form_fields(this.checked)"
          <?php if ($this->_tpl_vars['form_info']['is_multi_page_form'] == 'yes'): ?>checked<?php endif; ?> />
          <label for="is_multi_page_form">This a multi-page form</label>

        <input type="hidden" name="num_pages_in_multi_page_form" id="num_pages_in_multi_page_form" value="<?php echo $this->_tpl_vars['num_pages_in_multi_page_form']; ?>
" />

        <div id="multi_page_form_urls" <?php if ($this->_tpl_vars['form_info']['is_multi_page_form'] != 'yes'): ?>style="display:none"<?php endif; ?> class="margin_bottom_large">
          <table width="100%" cellpadding="0" cellspacing="0" id="multi_page_form_url_table">
          <tbody>
          <?php $_from = $this->_tpl_vars['form_info']['multi_page_form_urls']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['r'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['r']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['url']):
        $this->_foreach['r']['iteration']++;
?>
            <?php $this->assign('curr_page', $this->_foreach['r']['iteration']); ?>
          <tr>
            <td width="70" class="bold"><?php echo $this->_tpl_vars['LANG']['word_page']; ?>
 <?php echo $this->_tpl_vars['curr_page']+1; ?>
</td>
            <td>
              <input type="hidden" id="original_form_url_<?php echo $this->_tpl_vars['curr_page']+1; ?>
" value="<?php echo $this->_tpl_vars['url']['form_url']; ?>
" />
              <input type="text" name="form_url_<?php echo $this->_tpl_vars['curr_page']+1; ?>
" id="form_url_<?php echo $this->_tpl_vars['curr_page']+1; ?>
" value="<?php echo $this->_tpl_vars['url']['form_url']; ?>
" style="width: 98%"
                onkeyup="mf_ns.unverify_url_field(this.value, $('original_form_url_<?php echo $this->_tpl_vars['curr_page']+1; ?>
').value, <?php echo $this->_tpl_vars['curr_page']+1; ?>
)" />
            </td>
            <td width="60" align="right">
              <input type="button" class="green" id="form_url_<?php echo $this->_tpl_vars['curr_page']+1; ?>
_button"
                onclick="ft.verify_url('form_url_<?php echo $this->_tpl_vars['curr_page']+1; ?>
', <?php echo $this->_tpl_vars['curr_page']+1; ?>
)" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_verified'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
              <input type="hidden" id="form_url_<?php echo $this->_tpl_vars['curr_page']+1; ?>
_verified" name="form_url_verified" value="yes" />
            </td>
          </tr>
          <?php endforeach; endif; unset($_from); ?>
          </tbody>
          </table>

          <table width="100%" cellpadding="0" cellspacing="0" class="margin_top_small">
          <tr>
            <td width="70"> </td>
            <td><input type="button" value="<?php echo $this->_tpl_vars['LANG']['phrase_add_row']; ?>
" onclick="mf_ns.add_multi_page_form_page(this.form)" /></td>
          </tr>
          </table>

        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" class="red" align="center"> </td>
      <td valign="top" class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_redirect_url']; ?>
</td>
      <td>
          <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <input type="hidden" id="original_redirect_url" value="<?php echo $this->_tpl_vars['form_info']['redirect_url']; ?>
" />
              <input type="text" name="redirect_url" id="redirect_url" value="<?php echo $this->_tpl_vars['form_info']['redirect_url']; ?>
" style="width: 99%;"
                onkeyup="mf_ns.unverify_url_field(this.value, $('original_redirect_url').value, 'redirect')" />
            </td>
            <td width="60" align="center">
              <?php if ($this->_tpl_vars['form_info']['redirect_url'] != ""): ?>
              <input type="button" id="form_url_redirect_button" class="green" value="<?php echo $this->_tpl_vars['LANG']['word_verified']; ?>
"
                onclick="ft.verify_url('redirect_url', 'redirect')" />
              <input type="hidden" id="form_url_redirect_verified" value="yes" />
            <?php else: ?>
              <input type="button" id="form_url_redirect_button" class="light_grey" value="<?php echo $this->_tpl_vars['LANG']['phrase_verify_url']; ?>
"
                onclick="ft.verify_url('redirect_url', 'redirect')" />
              <input type="hidden" id="form_url_redirect_verified" value="no" />
            <?php endif; ?>
            </td>
          </tr>
          </table>
        </td>
      </tr>
    <tr>
      <td class="red" valign="top" align="center">*</td>
      <td class="pad_left_small" valign="top" width="160"><?php echo $this->_tpl_vars['LANG']['word_access']; ?>
</td>
      <td>

        <table cellspacing="1" cellpadding="0" >
        <tr>
          <td>
            <input type="radio" name="access_type" id="at1" value="admin" <?php if ($this->_tpl_vars['form_info']['access_type'] == 'admin'): ?>checked<?php endif; ?>
              onclick="mf_ns.toggle_access_type(this.value)" />
              <label for="at1"><?php echo $this->_tpl_vars['LANG']['phrase_admin_only']; ?>
</label>
          </td>
        </tr>
        <tr>
          <td>
            <div style="float:right;margin-left: 20px">
              <input type="button" id="client_omit_list_button"
                value="Manage Client Omit List<?php if ($this->_tpl_vars['form_info']['access_type'] == 'public'): ?> (<?php echo $this->_tpl_vars['num_clients_on_omit_list']; ?>
)<?php endif; ?>"
                onclick="window.location='edit.php?page=public_form_omit_list&form_id=<?php echo $this->_tpl_vars['form_id']; ?>
'"
                <?php if ($this->_tpl_vars['form_info']['access_type'] != 'public'): ?>disabled<?php endif; ?> /><br />
            </div>
            <input type="radio" name="access_type" id="at2" value="public" <?php if ($this->_tpl_vars['form_info']['access_type'] == 'public'): ?>checked<?php endif; ?>
              onclick="mf_ns.toggle_access_type(this.value)" />
              <label for="at2"><?php echo $this->_tpl_vars['LANG']['word_public']; ?>
 <span class="light_grey"><?php echo $this->_tpl_vars['LANG']['phrase_all_clients_have_access']; ?>
</span></label>
          </td>
        </tr>
        <tr>
          <td>
            <input type="radio" name="access_type" id="at3" value="private" <?php if ($this->_tpl_vars['form_info']['access_type'] == 'private'): ?>checked<?php endif; ?>
              onclick="mf_ns.toggle_access_type(this.value)" />
              <label for="at3"><?php echo $this->_tpl_vars['LANG']['word_private']; ?>
 <span class="light_grey"><?php echo $this->_tpl_vars['LANG']['phrase_only_specific_clients_have_access']; ?>
</span></label>
          </td>
        </tr>
        </table>

        <div id="custom_clients" <?php if ($this->_tpl_vars['form_info']['access_type'] != 'private'): ?>style="display:none"<?php endif; ?> class="margin_top">
          <table cellpadding="0" cellspacing="0" class="subpanel">
          <tr>
            <td class="medium_grey"><?php echo $this->_tpl_vars['LANG']['phrase_available_clients']; ?>
</td>
            <td></td>
            <td class="medium_grey"><?php echo $this->_tpl_vars['LANG']['phrase_selected_clients']; ?>
</td>
          </tr>
          <tr>
            <td>
              <?php echo smarty_function_clients_dropdown(array('name_id' => "available_client_ids[]",'multiple' => 'true','multiple_action' => 'hide','clients' => $this->_tpl_vars['selected_client_ids'],'size' => '4','style' => "width: 140px"), $this);?>

            </td>
            <td align="center" valign="middle" width="100">
              <input type="button" value="<?php echo $this->_tpl_vars['LANG']['word_add_uc_rightarrow']; ?>
"
                onclick="ft.move_options(this.form['available_client_ids[]'], this.form['selected_client_ids[]']);" /><br />
              <input type="button" value="<?php echo $this->_tpl_vars['LANG']['word_remove_uc_leftarrow']; ?>
"
                onclick="ft.move_options(this.form['selected_client_ids[]'], this.form['available_client_ids[]']);" />
            </td>
            <td>
              <?php echo smarty_function_clients_dropdown(array('name_id' => "selected_client_ids[]",'multiple' => 'true','multiple_action' => 'show','clients' => $this->_tpl_vars['selected_client_ids'],'size' => '4','style' => "width: 140px"), $this);?>

            </td>
          </tr>
          </table>
        </div>

      </td>
    </tr>
    <tr>
      <td class="red" valign="top" align="center">*</td>
      <td class="pad_left_small" valign="top" width="160">Edit Submission Page Label</td>
      <td><input type="text" name="edit_submission_page_label" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['form_info']['edit_submission_page_label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width: 99%" /></td>
    </tr>
    <tr>
      <td class="red" align="center">*</td>
      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_delete_uploaded_fields_with_submission']; ?>
</td>
      <td>
        <input type="radio" name="auto_delete_submission_files" id="auto_delete_submission_files1" value="yes" <?php if ($this->_tpl_vars['form_info']['auto_delete_submission_files'] == 'yes'): ?>checked<?php endif; ?> />
          <label for="auto_delete_submission_files1"><?php echo $this->_tpl_vars['LANG']['word_yes']; ?>
</label>
        <input type="radio" name="auto_delete_submission_files" id="auto_delete_submission_files2" value="no" <?php if ($this->_tpl_vars['form_info']['auto_delete_submission_files'] == 'no'): ?>checked<?php endif; ?> />
          <label for="auto_delete_submission_files2"><?php echo $this->_tpl_vars['LANG']['word_no']; ?>
</label>
      </td>
    </tr>
    <tr>
      <td class="red" align="center">*</td>
      <td class="pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_strip_tags_in_submissions']; ?>
</td>
      <td>
        <input type="radio" name="submission_strip_tags" id="sst1" value="yes" <?php if ($this->_tpl_vars['form_info']['submission_strip_tags'] == 'yes'): ?>checked<?php endif; ?> />
          <label for="sst1"><?php echo $this->_tpl_vars['LANG']['word_yes']; ?>
</label>
        <input type="radio" name="submission_strip_tags" id="sst2" value="no" <?php if ($this->_tpl_vars['form_info']['submission_strip_tags'] == 'no'): ?>checked<?php endif; ?> />
          <label for="sst2"><?php echo $this->_tpl_vars['LANG']['word_no']; ?>
</label>
      </td>
    </tr>
    </table>

    <p>
      <input type="submit" name="update_main" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
    </p>

   </form>