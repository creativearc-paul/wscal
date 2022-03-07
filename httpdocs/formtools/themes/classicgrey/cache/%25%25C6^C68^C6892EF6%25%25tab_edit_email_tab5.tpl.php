<?php /* Smarty version 2.6.18, created on 2015-10-02 12:39:40
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab5.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email_tab5.tpl', 5, false),)), $this); ?>
          <div class="margin_top margin_bottom_large">
            <?php echo $this->_tpl_vars['LANG']['text_reference_tab_info']; ?>

          </div>

          <p class="subtitle"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_global_placeholders'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</p>
          <p>
            <?php echo $this->_tpl_vars['LANG']['text_global_placeholder_info']; ?>

          </p>

          <table cellpadding="1" cellspacing="1" class="list_table" width="100%">
          <tr>
            <td valign="top" class="blue" width="160"><?php echo '{$FORMNAME}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_name_of_form']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue"><?php echo '{$LOGINURL}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_form_tools_login_url']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue"><?php echo '{$FORMURL}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_form_tools_form_url']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue"><?php echo '{$ADMINEMAIL}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_admin_email_placeholder_info']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue"><?php echo '{$SUBMISSIONDATE}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['submission_date_str']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue"><?php echo '{$LASTMODIFIEDDATE}'; ?>
</td>
            <td>
              <?php echo $this->_tpl_vars['LANG']['text_last_modified_date_explanation_c']; ?>

              <?php echo '{$SUBMISSIONDATE}'; ?>

            </td>
          </tr>
          <tr>
            <td valign="top" class="blue"><?php echo '{$SUBMISSIONID}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_unique_submission_id']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue"><?php echo '{$IPADDRESS}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_submission_ip_address']; ?>
</td>
          </tr>
          </table>
          <br />

          <p class="subtitle"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_form_placeholders'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</p>
          <p>
            <?php echo $this->_tpl_vars['LANG']['text_form_placeholder_info']; ?>

            <?php echo $this->_tpl_vars['file_field_text']; ?>

          </p>

          <table cellpadding="1" cellspacing="1" class="list_table" width="100%">
          <tr>
            <th><?php echo $this->_tpl_vars['LANG']['phrase_field_label']; ?>
</th>
            <th><?php echo $this->_tpl_vars['LANG']['phrase_form_field']; ?>
</th>
            <th><?php echo $this->_tpl_vars['LANG']['phrase_field_label_placeholder']; ?>
</th>
            <th><?php echo $this->_tpl_vars['LANG']['phrase_field_response_placeholder']; ?>
</th>
          </tr>
          <?php $_from = $this->_tpl_vars['form_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['field']):
        $this->_foreach['row']['iteration']++;
?>

            <?php if ($this->_tpl_vars['field']['field_type'] != 'system'): ?>
              <tr>
                <td><?php echo $this->_tpl_vars['field']['field_title']; ?>
</td>
                <td><?php echo $this->_tpl_vars['field']['field_name']; ?>
</td>
                <td class="blue"><?php echo '{$QUESTION'; ?>
_<?php echo $this->_tpl_vars['field']['field_name']; ?>
<?php echo '}'; ?>
</td>
                <td class="blue">

                                    <?php if ($this->_tpl_vars['field']['field_type'] == 'file'): ?>
                    <?php echo '{$FILENAME'; ?>
_<?php echo $this->_tpl_vars['field']['field_name']; ?>
<?php echo '}'; ?>
, <?php echo '{$FILEURL'; ?>
_<?php echo $this->_tpl_vars['field']['field_name']; ?>
<?php echo '}'; ?>

                  <?php else: ?>
                    <?php echo '{$ANSWER'; ?>
_<?php echo $this->_tpl_vars['field']['field_name']; ?>
<?php echo '}'; ?>

                  <?php endif; ?>

                </td>
              </tr>
            <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>
          </table>

          <br />

          <p class="subtitle"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_user_account_placeholders'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</p>
          <p>
            <?php echo $this->_tpl_vars['LANG']['text_user_account_placeholders_explanation']; ?>

          </p>

          <table cellpadding="1" cellspacing="1" class="list_table" width="100%">
          <tr>
            <td valign="top" class="blue" width="160"><?php echo '{$FIRSTNAME}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_first_name']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue" width="160"><?php echo '{$LASTNAME}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_last_name']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue" width="160"><?php echo '{$COMPANYNAME}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_company_name']; ?>
</td>
          </tr>
          <tr>
            <td valign="top" class="blue" width="160"><?php echo '{$EMAIL}'; ?>
</td>
            <td><?php echo $this->_tpl_vars['LANG']['text_email_address']; ?>
</td>
          </tr>
          </table>

        </div>