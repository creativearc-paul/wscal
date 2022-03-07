<?php /* Smarty version 2.6.18, created on 2010-12-01 16:48:55
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/tab_edit_email_tab3.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'email_patterns_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/tab_edit_email_tab3.tpl', 10, false),)), $this); ?>
            <div class="margin_top margin_bottom_large">
              <?php echo $this->_tpl_vars['LANG']['text_email_template_tab']; ?>

            </div>

            <table cellpadding="0" cellspacing="1" style="padding-bottom: 5px; width:100%">
            <tr>
              <td class="bold"><?php echo $this->_tpl_vars['LANG']['phrase_html_template']; ?>
</td>
              <td class="no_wrap" align="right">
                <?php echo $this->_tpl_vars['LANG']['word_examples_c']; ?>

                <?php echo smarty_function_email_patterns_dropdown(array('type' => 'html','form_id' => $this->_tpl_vars['form_id'],'onchange' => "emails_ns.select_template('html', this.value)"), $this);?>

              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div style="border: 1px solid #666666; padding: 3px">
                  <textarea id="html_template" name="html_template" style="width: 100%; height: 300px"><?php echo $this->_tpl_vars['template_info']['html_template']; ?>
</textarea>
                </div>

                <script type="text/javascript">
								  var html_editor = new CodeMirror.fromTextArea("html_template", <?php echo '{'; ?>

								    parserfile: ["parsexml.js"],
								    path: "<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/codemirror/js/",
								    stylesheet: "<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/codemirror/css/xmlcolors.css"
								  <?php echo '});'; ?>

								</script>

              </td>
            </tr>
            <tr>
              <td class="bold"><?php echo $this->_tpl_vars['LANG']['phrase_text_template']; ?>
</td>
              <td class="no_wrap" align="right">
                <?php echo $this->_tpl_vars['LANG']['word_examples_c']; ?>

                <?php echo smarty_function_email_patterns_dropdown(array('type' => 'text','form_id' => $this->_tpl_vars['form_id'],'onchange' => "emails_ns.select_template('text', this.value)"), $this);?>

              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div style="border: 1px solid #666666; padding: 3px">
                  <textarea id="text_template" name="text_template" style="width: 100%; height: 300px"><?php echo $this->_tpl_vars['template_info']['text_template']; ?>
</textarea>
                </div>

                <script type="text/javascript">
								  var text_editor = new CodeMirror.fromTextArea("text_template", <?php echo '{'; ?>

								    parserfile: ["parsexml.js"],
								    path: "<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/codemirror/js/",
								    stylesheet: "<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/codemirror/css/xmlcolors.css"
								  <?php echo '});'; ?>

								</script>

              </td>
            </tr>
            </table>