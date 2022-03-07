<?php /* Smarty version 2.6.18, created on 2015-10-02 12:39:40
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab2.tpl', 1, false),array('modifier', 'lower', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab2.tpl', 46, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab2.tpl', 51, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab2.tpl', 143, false),array('function', 'eval_smarty_string', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab2.tpl', 3, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab2.tpl', 255, false),)), $this); ?>
            <?php if (count($this->_tpl_vars['registered_form_emails']) == 0): ?>
              <div class="box">
                <?php echo smarty_function_eval_smarty_string(array('placeholder_str' => $this->_tpl_vars['LANG']['notify_no_user_email_fields_configured']), $this);?>

              </div>
            <?php endif; ?>

            <table cellpadding="2" cellspacing="1" width="100%">
            <tr>
              <td width="10" valign="top" class="red">*</td>
              <td width="140" valign="top"><?php echo $this->_tpl_vars['LANG']['word_recipient_sp']; ?>
</td>
              <td>

                <table cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <table cellspacing="0">
                    <tr>
                      <td>
                        <select id="recipient_options" onchange="emails_ns.show_custom_email_field('recipients', this.value)"
                          onkeyup="emails_ns.show_custom_email_field('recipients', this.value)">
                          <option value="" selected><?php echo $this->_tpl_vars['LANG']['phrase_please_select']; ?>
</option>
                          <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_administrator']; ?>
">
                            <option value="admin"><?php echo $this->_tpl_vars['admin_info']['first_name']; ?>
 <?php echo $this->_tpl_vars['admin_info']['last_name']; ?>
 &lt;<?php echo $this->_tpl_vars['admin_info']['email']; ?>
&gt;</option>
                          </optgroup>
                          <?php if ($this->_tpl_vars['clients']): ?>
                            <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_clients']; ?>
">
                              <?php $_from = $this->_tpl_vars['clients']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['client']):
        $this->_foreach['row']['iteration']++;
?>
                                <option value="client_account_id_<?php echo $this->_tpl_vars['client']['account_id']; ?>
"><?php echo $this->_tpl_vars['client']['first_name']; ?>
 <?php echo $this->_tpl_vars['client']['last_name']; ?>
 &lt;<?php echo $this->_tpl_vars['client']['email']; ?>
&gt;</option>
                              <?php endforeach; endif; unset($_from); ?>
                            </optgroup>
                          <?php endif; ?>
                          <?php if (count($this->_tpl_vars['registered_form_emails']) > 0): ?>
                            <optgroup label="<?php echo $this->_tpl_vars['LANG']['phrase_form_email_fields']; ?>
">
                              <?php $_from = $this->_tpl_vars['registered_form_emails']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['email_info']):
?>
                                <option value="form_email_id_<?php echo $this->_tpl_vars['email_info']['form_email_id']; ?>
"><?php echo $this->_tpl_vars['email_info']['email_field_label']; ?>
</option>
                              <?php endforeach; endif; unset($_from); ?>
                            </optgroup>
                          <?php endif; ?>
                          <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_other']; ?>
">
                            <option value="custom"><?php echo $this->_tpl_vars['LANG']['phrase_custom_recipient']; ?>
</option>
                          </optgroup>
                        </select>
                      </td>
                      <td>
                        <select id="recipient_type">
                          <option value=""><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_main'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
</option>
                          <option value="cc">cc</option>
                          <option value="bcc">bcc</option>
                        </select>
                      </td>
                      <td><input type="button" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_add'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" onclick="emails_ns.add_recipient(this.form)" /></td>
                    </tr>
                    </table>

                    <div id="custom_recipients" class="box" style="display:none;margin-top: 2px;">
                      <table cellspacing="0">
                      <tr>
                        <td>
                          <table cellspacing="0">
                          <tr>
                            <td></td>
                            <td class="pad_right"><?php echo $this->_tpl_vars['LANG']['word_name']; ?>
</td>
                            <td><input type="text" id="custom_recipient_name" style="width:200px" /></td>
                          </tr>
                          <tr>
                            <td class="red">*</td>
                            <td class="pad_right"><?php echo $this->_tpl_vars['LANG']['word_email']; ?>
</td>
                            <td><input type="text" id="custom_recipient_email" style="width:200px" /></td>
                          </tr>
                          <tr>
                            <td class="red">*</td>
                            <td class="pad_right"><?php echo $this->_tpl_vars['LANG']['phrase_recipient_type']; ?>
</td>
                            <td>
                              <select id="custom_recipient_type">
                                <option value=""><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_main'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
</option>
                                <option value="cc">cc</option>
                                <option value="bcc">bcc</option>
                              </select>
                            </td>
                          </tr>
                          </table>
                        </td>
                        <td>
                          <input type="button" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_add'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" onclick="emails_ns.add_custom_recipient(this.form)" />
                        </td>
                      </tr>
                      </table>
                    </div>

                  </td>
                </tr>
                </table>

                <div id="email_recipients" style="padding: 6px; border:1px solid #336699">

                  <div id="no_recipients" <?php if (count($this->_tpl_vars['template_info']['recipients']) > 0): ?>style="display:none"<?php endif; ?>>
                    <?php echo $this->_tpl_vars['LANG']['text_no_recipients_added']; ?>

                  </div>

                  <?php $_from = $this->_tpl_vars['template_info']['recipients']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['recipient']):
        $this->_foreach['row']['iteration']++;
?>
                    <?php $this->assign('count', $this->_foreach['row']['iteration']); ?>
                    <?php $this->assign('recipient_type', $this->_tpl_vars['recipient']['recipient_type']); ?>

                    <?php if ($this->_tpl_vars['recipient_type'] == 'cc'): ?>
                      <?php $this->assign('recipient_type', "&nbsp;<span class=\"bold\">[cc]</span>"); ?>
                    <?php elseif ($this->_tpl_vars['recipient_type'] == 'bcc'): ?>
                      <?php $this->assign('recipient_type', "&nbsp;<span class=\"bold\">[bcc]</span>"); ?>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['recipient']['recipient_user_type'] == 'admin'): ?>
                      <div id="recipient_<?php echo $this->_tpl_vars['count']; ?>
">
                        <?php echo $this->_tpl_vars['admin_info']['first_name']; ?>
 <?php echo $this->_tpl_vars['admin_info']['last_name']; ?>
 &lt;<?php echo $this->_tpl_vars['admin_info']['email']; ?>
&gt;<?php echo $this->_tpl_vars['recipient_type']; ?>
&nbsp;
                        <a href="#" onclick="return emails_ns.remove_recipient(<?php echo $this->_tpl_vars['count']; ?>
)">[x]</a>
                        <input type="hidden" name="recipients[]" value="<?php echo $this->_tpl_vars['count']; ?>
" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_user_type" value="admin" />
                        <input type="hidden" id="recipient_<?php echo $this->_tpl_vars['count']; ?>
_type" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_type" value="<?php echo $this->_tpl_vars['recipient']['recipient_type']; ?>
" />
                      </div>
                    <?php elseif ($this->_tpl_vars['recipient']['recipient_user_type'] == 'form_email_field'): ?>
                      <div id="recipient_<?php echo $this->_tpl_vars['count']; ?>
">
                        <?php echo $this->_tpl_vars['LANG']['phrase_form_email_field_b_c']; ?>
 <?php echo $this->_tpl_vars['recipient']['final_recipient']; ?>
<?php echo $this->_tpl_vars['recipient_type']; ?>
&nbsp;
                        <a href="#" onclick="return emails_ns.remove_recipient(<?php echo $this->_tpl_vars['count']; ?>
)">[x]</a>
                        <input type="hidden" name="recipients[]" value="<?php echo $this->_tpl_vars['count']; ?>
" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_user_type" value="form_email_field" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_form_email_id" value="<?php echo $this->_tpl_vars['recipient']['form_email_id']; ?>
	" />
                        <input type="hidden" id="recipient_<?php echo $this->_tpl_vars['count']; ?>
_type" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_type" value="<?php echo $this->_tpl_vars['recipient']['recipient_type']; ?>
" />
                      </div>
                    <?php elseif ($this->_tpl_vars['recipient']['recipient_user_type'] == 'client'): ?>
                      <div id="recipient_<?php echo $this->_tpl_vars['count']; ?>
">
                        <?php echo $this->_tpl_vars['recipient']['first_name']; ?>
 <?php echo $this->_tpl_vars['recipient']['last_name']; ?>
 &lt;<?php echo $this->_tpl_vars['recipient']['email']; ?>
&gt;<?php echo $this->_tpl_vars['recipient_type']; ?>
&nbsp;
                        <a href="#" onclick="return emails_ns.remove_recipient(<?php echo $this->_tpl_vars['count']; ?>
)">[x]</a>
                        <input type="hidden" name="recipients[]" value="<?php echo $this->_tpl_vars['count']; ?>
" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_user_type" value="client" />
                        <input type="hidden" id="recipient_<?php echo $this->_tpl_vars['count']; ?>
_type" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_type" value="<?php echo $this->_tpl_vars['recipient']['recipient_type']; ?>
" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_account_id" value="<?php echo $this->_tpl_vars['recipient']['account_id']; ?>
" />
                      </div>
                    <?php elseif ($this->_tpl_vars['recipient']['recipient_user_type'] == 'custom'): ?>
                      <div id="recipient_<?php echo $this->_tpl_vars['count']; ?>
">
                        <?php echo $this->_tpl_vars['recipient']['custom_recipient_name']; ?>
 &lt;<?php echo $this->_tpl_vars['recipient']['custom_recipient_email']; ?>
&gt;<?php echo $this->_tpl_vars['recipient_type']; ?>
&nbsp;
                        <a href="#" onclick="return emails_ns.remove_recipient(<?php echo $this->_tpl_vars['count']; ?>
)">[x]</a>
                        <input type="hidden" name="recipients[]" value="<?php echo $this->_tpl_vars['count']; ?>
" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_user_type" value="custom" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_type" id="recipient_<?php echo $this->_tpl_vars['count']; ?>
_type" value="<?php echo $this->_tpl_vars['recipient']['recipient_type']; ?>
" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['recipient']['custom_recipient_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
                        <input type="hidden" name="recipient_<?php echo $this->_tpl_vars['count']; ?>
_email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['recipient']['custom_recipient_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
                      </div>
                    <?php endif; ?>
                  <?php endforeach; endif; unset($_from); ?>
                </div>

              </td>
            </tr>
            <tr>
              <td class="red">*</td>
              <td><?php echo $this->_tpl_vars['LANG']['phrase_subject_line']; ?>
</td>
              <td><input type="text" name="subject" style="width:300px;" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['template_info']['subject'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
            </tr>
            <tr>
              <td valign="top" class="red">*</td>
              <td valign="top"><?php echo $this->_tpl_vars['LANG']['word_from']; ?>
</td>
              <td>

                <select name="email_from" id="email_from" onchange="emails_ns.show_custom_email_field('from', this.value)"
                  onchange="emails_ns.show_custom_email_field('from', this.value)">
                  <option value=""><?php echo $this->_tpl_vars['LANG']['phrase_please_select']; ?>
</option>
                  <option value="none" <?php if ($this->_tpl_vars['template_info']['email_from'] == 'none'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['phrase_none_not_recommended']; ?>
</option>
                  <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_administrator']; ?>
">
                    <option value="admin" <?php if ($this->_tpl_vars['template_info']['email_from'] == 'admin'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['admin_info']['first_name']; ?>
 <?php echo $this->_tpl_vars['admin_info']['last_name']; ?>
 &lt;<?php echo $this->_tpl_vars['admin_info']['email']; ?>
&gt;</option>
                  </optgroup>
                  <?php if ($this->_tpl_vars['clients']): ?>
                    <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_clients']; ?>
">
                      <?php $_from = $this->_tpl_vars['clients']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['client']):
        $this->_foreach['row']['iteration']++;
?>
                        <option value="client_account_id_<?php echo $this->_tpl_vars['client']['account_id']; ?>
" <?php if ($this->_tpl_vars['template_info']['email_from_account_id'] == $this->_tpl_vars['client']['account_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['client']['first_name']; ?>
 <?php echo $this->_tpl_vars['client']['last_name']; ?>
 &lt;<?php echo $this->_tpl_vars['client']['email']; ?>
&gt;</option>
                      <?php endforeach; endif; unset($_from); ?>
                    </optgroup>
                  <?php endif; ?>
                  <?php if (count($this->_tpl_vars['registered_form_emails']) > 0): ?>
                    <optgroup label="<?php echo $this->_tpl_vars['LANG']['phrase_form_email_fields']; ?>
">
                      <?php $_from = $this->_tpl_vars['registered_form_emails']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['email_info']):
?>
                        <option value="form_email_id_<?php echo $this->_tpl_vars['email_info']['form_email_id']; ?>
"
                          <?php if ($this->_tpl_vars['template_info']['email_from_form_email_id'] == $this->_tpl_vars['email_info']['form_email_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['phrase_form_email_field_b_c']; ?>
 <?php echo $this->_tpl_vars['email_info']['email_field_label']; ?>
</option>
                      <?php endforeach; endif; unset($_from); ?>
                    </optgroup>
                  <?php endif; ?>
                  <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_other']; ?>
">
                    <option value="custom" <?php if ($this->_tpl_vars['template_info']['email_from'] == 'custom'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_custom']; ?>
</option>
                  </optgroup>
                </select>

                <div id="custom_from" class="box" style="margin-top: 4px;<?php if ($this->_tpl_vars['template_info']['email_from'] != 'custom'): ?>display:none<?php endif; ?>" >
                  <table>
                  <tr>
                    <td></td>
                    <td class="pad_right"><?php echo $this->_tpl_vars['LANG']['word_name_c']; ?>
</td>
                    <td><input type="text" name="custom_from_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['template_info']['custom_from_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:200px" /></td>
                  </tr>
                  <tr>
                    <td class="red">*</td>
                    <td class="pad_right"><?php echo $this->_tpl_vars['LANG']['word_email_c']; ?>
</td>
                    <td><input type="text" name="custom_from_email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['template_info']['custom_from_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:200px" /></td>
                  </tr>
                  </table>
                </div>

              </td>
            </tr>
            <tr>
              <td valign="top" class="red"> </td>
              <td valign="top"><?php echo $this->_tpl_vars['LANG']['word_reply_to']; ?>
</td>
              <td>

                <select name="email_reply_to" id="email_reply_to" onchange="emails_ns.show_custom_email_field('reply_to', this.value)"
                  onchange="emails_ns.show_custom_email_field('reply_to', this.value)">
                  <option value=""><?php echo $this->_tpl_vars['LANG']['phrase_please_select']; ?>
</option>
                  <option value="none" <?php if ($this->_tpl_vars['template_info']['email_reply_to'] == 'none'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_none']; ?>
</option>
                  <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_administrator']; ?>
">
                    <option value="admin" <?php if ($this->_tpl_vars['template_info']['email_reply_to'] == 'admin'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['admin_info']['first_name']; ?>
 <?php echo $this->_tpl_vars['admin_info']['last_name']; ?>
 &lt;<?php echo $this->_tpl_vars['admin_info']['email']; ?>
&gt;</option>
                  </optgroup>
                  <?php if ($this->_tpl_vars['clients']): ?>
                    <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_clients']; ?>
">
                      <?php $_from = $this->_tpl_vars['clients']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['client']):
        $this->_foreach['row']['iteration']++;
?>
                        <option value="<?php echo $this->_tpl_vars['client']['account_id']; ?>
" <?php if ($this->_tpl_vars['template_info']['email_reply_to_account_id'] == $this->_tpl_vars['client']['account_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['client']['first_name']; ?>
 <?php echo $this->_tpl_vars['client']['last_name']; ?>
 &lt;<?php echo $this->_tpl_vars['client']['email']; ?>
&gt;</option>
                      <?php endforeach; endif; unset($_from); ?>
                    </optgroup>
                  <?php endif; ?>
                  <?php if (count($this->_tpl_vars['registered_form_emails']) > 0): ?>
                    <optgroup label="<?php echo $this->_tpl_vars['LANG']['phrase_form_email_fields']; ?>
">
                      <?php $_from = $this->_tpl_vars['registered_form_emails']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['email_info']):
?>
                        <option value="form_email_id_<?php echo $this->_tpl_vars['email_info']['form_email_id']; ?>
"
                          <?php if ($this->_tpl_vars['template_info']['email_reply_to_form_email_id'] == $this->_tpl_vars['email_info']['form_email_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['phrase_form_email_field_b_c']; ?>
 <?php echo $this->_tpl_vars['email_info']['email_field_label']; ?>
</option>
                      <?php endforeach; endif; unset($_from); ?>
                    </optgroup>
                  <?php endif; ?>
                  <optgroup label="<?php echo $this->_tpl_vars['LANG']['word_other']; ?>
">
                    <option value="custom" <?php if ($this->_tpl_vars['template_info']['email_reply_to'] == 'custom'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_custom']; ?>
</option>
                  </optgroup>
                </select>

                <div id="custom_reply_to" class="box" style="margin-top: 4px;<?php if ($this->_tpl_vars['template_info']['email_reply_to'] != 'custom'): ?>display:none<?php endif; ?>">
                  <table>
                  <tr>
                    <td></td>
                    <td class="pad_right" width="60"><?php echo $this->_tpl_vars['LANG']['word_name_c']; ?>
</td>
                    <td><input type="text" name="custom_reply_to_name" value="<?php echo $this->_tpl_vars['template_info']['custom_reply_to_name']; ?>
" style="width:200px" /></td>
                  </tr>
                  <tr>
                    <td class="red">*</td>
                    <td class="pad_right"><?php echo $this->_tpl_vars['LANG']['word_email_c']; ?>
</td>
                    <td><input type="text" name="custom_reply_to_email" style="width:200px" value="<?php echo $this->_tpl_vars['template_info']['custom_reply_to_email']; ?>
" /></td>
                  </tr>
                  </table>
                </div>

              </td>
            </tr>
            <?php echo smarty_function_template_hook(array('location' => 'edit_template_tab2'), $this);?>

            </table>
