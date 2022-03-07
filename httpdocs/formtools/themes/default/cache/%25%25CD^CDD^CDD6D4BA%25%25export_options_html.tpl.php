<?php /* Smarty version 2.6.18, created on 2010-12-01 16:50:45
         compiled from ../../modules/export_manager/templates/export_options_html.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '../../modules/export_manager/templates/export_options_html.tpl', 19, false),array('function', 'eval', '../../modules/export_manager/templates/export_options_html.tpl', 31, false),)), $this); ?>

  <script type="text/javascript" src="<?php echo $this->_tpl_vars['modules_dir']; ?>
/export_manager/global/scripts/export_manager.js"></script>
  <script type="text/javascript">
  if (typeof em == 'undefined')
    em = <?php echo '{}'; ?>
;

  em.export_page = "<?php echo $this->_tpl_vars['modules_dir']; ?>
/export_manager/export.php";
  g.messages["validation_select_rows_to_export"] = "<?php echo $this->_tpl_vars['LANG']['export_manager']['validation_select_rows_to_export']; ?>
";
  </script>

  <?php if (count($this->_tpl_vars['export_groups']) > 0): ?>
    <hr size="1" align="left" style="width: 100%;" />

    <form action="<?php echo $this->_tpl_vars['modules_dir']; ?>
/export_manager/export.php" id="export_manager_form" method="post">
      <input type="hidden" name="export_group_id" id="export_group_id" value="" />
      <input type="hidden" name="export_type_id" id="export_type_id" value="" />

		  <table cellpadding="0" cellpadding="1">
		  <?php $_from = $this->_tpl_vars['export_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['export_group']):
        $this->_foreach['row']['iteration']++;
?>
        <?php $this->assign('export_group_id', $this->_tpl_vars['export_group']['export_group_id']); ?>
			  <tr>
			    <td class="pad_right_large"><img src="<?php echo $this->_tpl_vars['export_icon_folder']; ?>
/<?php echo $this->_tpl_vars['export_group']['icon']; ?>
"/></td>
			    <td class="pad_right_large"><?php echo smarty_function_eval(array('var' => $this->_tpl_vars['export_group']['group_name']), $this);?>
</td>
			    <td>
			      <?php $this->assign('var_name', "export_group_".($this->_tpl_vars['export_group_id'])."_results"); ?>
			      <select name="export_group_<?php echo $this->_tpl_vars['export_group_id']; ?>
_results" id="export_group_<?php echo $this->_tpl_vars['export_group_id']; ?>
_results">
			        <option value="all"      <?php if ($this->_tpl_vars['page_vars'][$this->_tpl_vars['var_name']] == 'all'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_all']; ?>
</option>
			        <option value="selected" <?php if ($this->_tpl_vars['page_vars'][$this->_tpl_vars['var_name']] == 'selected'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_selected']; ?>
</option>
			      </select>
			    </td>
			    <td>

                        <?php if ($this->_tpl_vars['export_group']['action'] == 'popup'): ?>
						  <script type="text/javascript">
						  em.export_group_id_<?php echo $this->_tpl_vars['export_group_id']; ?>
_height = <?php echo $this->_tpl_vars['export_group']['popup_height']; ?>
;
						  em.export_group_id_<?php echo $this->_tpl_vars['export_group_id']; ?>
_width  = <?php echo $this->_tpl_vars['export_group']['popup_width']; ?>
;
						  </script>
            <?php endif; ?>

			      <table cellspacing="0" cellpadding="0">
			      <tr>
			        		          <?php if (count($this->_tpl_vars['export_group']['export_types']) > 1): ?>
		            <?php $this->assign('var_name', "export_group_".($this->_tpl_vars['export_group_id'])."_export_type"); ?>
		            <td>
		              <select name="export_group_<?php echo $this->_tpl_vars['export_group_id']; ?>
_export_type" id="export_group_<?php echo $this->_tpl_vars['export_group_id']; ?>
_export_type">
		              <?php $_from = $this->_tpl_vars['export_group']['export_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['export_type']):
        $this->_foreach['row']['iteration']++;
?>
		                <option value="<?php echo $this->_tpl_vars['export_type']['export_type_id']; ?>
" <?php if ($this->_tpl_vars['page_vars'][$this->_tpl_vars['var_name']] == $this->_tpl_vars['export_type']['export_type_id']): ?>selected<?php endif; ?>><?php echo smarty_function_eval(array('var' => $this->_tpl_vars['export_type']['export_type_name']), $this);?>
</option>
		              <?php endforeach; endif; unset($_from); ?>
		              </select>
		            </td>
		          <?php endif; ?>
		          <td>
					      <input type="button" name="export_group_<?php echo $this->_tpl_vars['export_group_id']; ?>
" value="<?php echo smarty_function_eval(array('var' => $this->_tpl_vars['export_group']['action_button_text']), $this);?>
"
					        onclick="em.export_submissions(<?php echo $this->_tpl_vars['export_group_id']; ?>
, '<?php echo $this->_tpl_vars['export_group']['action']; ?>
')" />
					    </td>
					  </tr>
					  </table>

			    </td>
			  </tr>
		  <?php endforeach; endif; unset($_from); ?>
		  </table>

    </form>

  <?php endif; ?>