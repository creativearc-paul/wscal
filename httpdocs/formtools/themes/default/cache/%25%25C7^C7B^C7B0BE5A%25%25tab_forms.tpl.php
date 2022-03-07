<?php /* Smarty version 2.6.18, created on 2010-12-15 09:46:58
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_forms.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_forms.tpl', 1, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_forms.tpl', 11, false),array('function', 'forms_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_forms.tpl', 29, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_forms.tpl', 19, false),array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/clients/tab_forms.tpl', 71, false),)), $this); ?>
    <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


    <div class="margin_bottom_large">
      <?php echo $this->_tpl_vars['LANG']['text_client_form_page']; ?>

    </div>

    <form method="post" name="client_forms" id="client_forms" action="<?php echo $this->_tpl_vars['same_page']; ?>
" onsubmit="return cf_ns.check_fields(this)">
      <input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['client_id']; ?>
" />
      <input type="hidden" name="num_forms" id="num_forms" value="0" />

      <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_forms_top'), $this);?>


      <table class="list_table" id="client_forms_table" cellpadding="0" cellspacing="1">
      <tbody><tr>
        <th><?php echo $this->_tpl_vars['LANG']['word_form']; ?>
</th>
        <th width="150"><?php echo $this->_tpl_vars['LANG']['phrase_available_views']; ?>
</th>
        <th width="90"><?php echo $this->_tpl_vars['LANG']['word_action']; ?>
</th>
        <th width="150"><?php echo $this->_tpl_vars['LANG']['phrase_selected_views']; ?>
</th>
        <th width="60" class="del"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_delete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
      </tr>

            <?php $_from = $this->_tpl_vars['client_forms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['i'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['i']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['form_row']):
        $this->_foreach['i']['iteration']++;
?>
        <?php $this->assign('form_info', $this->_tpl_vars['form_row']); ?>
        <?php $this->assign('views', $this->_tpl_vars['form_row']['views']); ?>
        <?php $this->assign('row', $this->_foreach['i']['iteration']); ?>

         <tr id="row_<?php echo $this->_tpl_vars['row']; ?>
">
           <td valign="top"><?php echo smarty_function_forms_dropdown(array('name_id' => "form_row_".($this->_tpl_vars['row']),'include_blank_option' => true,'style' => "width:100%",'default' => $this->_tpl_vars['form_info']['form_id'],'onchange' => "cf_ns.select_form(".($this->_tpl_vars['row']).", this.value)"), $this);?>
</td>
           <td>
             <span id="row_<?php echo $this->_tpl_vars['row']; ?>
_available_views_span">
               <select name="row_<?php echo $this->_tpl_vars['row']; ?>
_available_views[]" id="row_<?php echo $this->_tpl_vars['row']; ?>
_available_views" multiple size="4" style="width:100%">
                                  <?php $_from = $this->_tpl_vars['all_form_views'][$this->_tpl_vars['form_info']['form_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['vr'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['vr']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['view_row']):
        $this->_foreach['vr']['iteration']++;
?>

                   <?php $this->assign('is_found', false); ?>
                   <?php $_from = $this->_tpl_vars['views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['vr2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['vr2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['client_view_row']):
        $this->_foreach['vr2']['iteration']++;
?>
                     <?php if ($this->_tpl_vars['client_view_row']['view_id'] == $this->_tpl_vars['view_row']['view_id']): ?>
                       <?php $this->assign('is_found', true); ?>
                     <?php endif; ?>
                   <?php endforeach; endif; unset($_from); ?>

                   <?php if (! $this->_tpl_vars['is_found']): ?>
                     <option value="<?php echo $this->_tpl_vars['view_row']['view_id']; ?>
"><?php echo $this->_tpl_vars['view_row']['view_name']; ?>
</option>
                   <?php endif; ?>
                 <?php endforeach; endif; unset($_from); ?>
               </select>
             </span>
           </td>
           <td valign="center" align="center">
             <span id="row_<?php echo $this->_tpl_vars['row']; ?>
_actions">
               <input type="button" onclick="return ft.move_options($('row_<?php echo $this->_tpl_vars['row']; ?>
_available_views'), $('row_<?php echo $this->_tpl_vars['row']; ?>
_selected_views'))" value="<?php echo $this->_tpl_vars['LANG']['word_add_uc_rightarrow']; ?>
" />
               <input type="button" onclick="return ft.move_options($('row_<?php echo $this->_tpl_vars['row']; ?>
_selected_views'), $('row_<?php echo $this->_tpl_vars['row']; ?>
_available_views'))" value="<?php echo $this->_tpl_vars['LANG']['word_remove_uc_leftarrow']; ?>
" />
             </span>
           </td>
           <td>
             <span id="row_<?php echo $this->_tpl_vars['row']; ?>
_selected_views_span">
               <select name="row_<?php echo $this->_tpl_vars['row']; ?>
_selected_views[]" id="row_<?php echo $this->_tpl_vars['row']; ?>
_selected_views" multiple size="4" style="width:100%">
               <?php $_from = $this->_tpl_vars['views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['vr'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['vr']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['view_row']):
        $this->_foreach['vr']['iteration']++;
?>
                 <option value="<?php echo $this->_tpl_vars['view_row']['view_id']; ?>
"><?php echo $this->_tpl_vars['view_row']['view_name']; ?>
</option>
               <?php endforeach; endif; unset($_from); ?>
               </select>
             </span>
           </td>
           <td class="del" align="center"><a href="#" onclick="return cf_ns.delete_row(<?php echo $this->_tpl_vars['row']; ?>
)"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_delete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a></td>
         </tr>
      <?php endforeach; endif; unset($_from); ?></tbody>
      </table>

      <script type="text/javascript">
      cf_ns.num_rows = <?php echo count($this->_tpl_vars['client_forms']); ?>
;
            <?php if (count($this->_tpl_vars['client_forms']) == 0): ?>
        cf_ns.add_form_row();
      <?php endif; ?>
      </script>

      <p>
        <a href="#" onclick="return cf_ns.add_form_row()"><?php echo $this->_tpl_vars['LANG']['phrase_add_row']; ?>
</a>
      </p>

      <?php echo smarty_function_template_hook(array('location' => 'admin_edit_client_forms_bottom'), $this);?>


      <p>
        <input type="submit" name="update_client" value="<?php echo $this->_tpl_vars['LANG']['word_update']; ?>
" />
      </p>

    </form>