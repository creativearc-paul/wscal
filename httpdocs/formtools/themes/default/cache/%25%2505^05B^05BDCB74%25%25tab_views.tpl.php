<?php /* Smarty version 2.6.18, created on 2010-12-01 16:48:45
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/tab_views.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/tab_views.tpl', 2, false),array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/tab_views.tpl', 14, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/tab_views.tpl', 9, false),array('function', 'clients_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/tab_views.tpl', 62, false),)), $this); ?>
  <div class="subtitle underline margin_top_large margin_bottom_large">
    <?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_views'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

  </div>

  <div>
    <?php echo $this->_tpl_vars['LANG']['text_view_tab_summary']; ?>

  </div>

  <?php echo smarty_function_ft_include(array('file' => 'messages.tpl'), $this);?>


  <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post">
    <input type="hidden" name="page" value="views" />

    <?php if (count($this->_tpl_vars['form_views']) == 0): ?>

      <div class="error yellow_bg" class="margin_bottom_large">
        <div style="padding:8px">
          <b><?php echo $this->_tpl_vars['LANG']['word_warning']; ?>
</b>
          <br/>
          <br/>
          &bull;&nbsp;<?php echo $this->_tpl_vars['LANG']['notify_no_views_defined']; ?>

        </div>
      </div>

    <?php else: ?>

      <?php echo $this->_tpl_vars['pagination']; ?>


      <table class="list_table" cellspacing="1" cellpadding="1">
      <tr>
        <?php if (count($this->_tpl_vars['form_views']) > 1): ?>
          <th width="30"><input type="submit" name="update_view_order" value="<?php echo $this->_tpl_vars['LANG']['word_order']; ?>
" class="bold" /></th>
        <?php endif; ?>
        <th><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_id'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
        <th><?php echo $this->_tpl_vars['LANG']['phrase_view_name']; ?>
</th>
        <th><?php echo $this->_tpl_vars['LANG']['phrase_who_can_access']; ?>
</th>
        <th width="60"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_edit'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
        <th width="60" class="del"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_delete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
      </tr>

      <?php $_from = $this->_tpl_vars['form_views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['view']):
        $this->_foreach['row']['iteration']++;
?>
        <?php $this->assign('index', ($this->_foreach['row']['iteration']-1)); ?>
        <?php $this->assign('count', $this->_foreach['row']['iteration']); ?>
        <?php $this->assign('view_id', $this->_tpl_vars['view']['view_id']); ?>

         <tr>

          <?php if (count($this->_tpl_vars['form_views']) > 1): ?>
             <td align="center"><input type="text" name="view_<?php echo $this->_tpl_vars['view_id']; ?>
" value="<?php echo $this->_tpl_vars['view']['view_order']; ?>
" style="width:30px" /></td>
          <?php endif; ?>

          <td class="medium_grey" align="center"><?php echo $this->_tpl_vars['view']['view_id']; ?>
</td>
           <td class="pad_left_small"><?php echo $this->_tpl_vars['view']['view_name']; ?>
</td>
          <td>
            <?php if ($this->_tpl_vars['view']['access_type'] == 'admin'): ?>
              <span class="pad_left_small medium_grey"><?php echo $this->_tpl_vars['LANG']['phrase_admin_only']; ?>
</span>
            <?php elseif ($this->_tpl_vars['view']['access_type'] == 'public'): ?>
              <?php if (count($this->_tpl_vars['view']['client_omit_list']) == 0): ?>
                <span class="pad_left_small blue"><?php echo $this->_tpl_vars['LANG']['phrase_all_clients']; ?>
</span>
              <?php else: ?>
                <span class="pad_left_small blue"><?php echo $this->_tpl_vars['LANG']['phrase_all_clients_except_c']; ?>
</span>
                <?php echo smarty_function_clients_dropdown(array('name_id' => "",'only_show_clients' => $this->_tpl_vars['view']['client_omit_list']), $this);?>

              <?php endif; ?>

            <?php elseif (count($this->_tpl_vars['view']['client_info']) > 0): ?>
              <?php if (count($this->_tpl_vars['view']['client_info']) == 1): ?>
                <?php echo $this->_tpl_vars['view']['client_info'][0]['first_name']; ?>
 <?php echo $this->_tpl_vars['view']['client_info'][0]['last_name']; ?>

              <?php else: ?>
                <select>
                  <?php $_from = $this->_tpl_vars['view']['client_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['user_row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['user_row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['user']):
        $this->_foreach['user_row']['iteration']++;
?>
                    <option><?php echo $this->_tpl_vars['user']['first_name']; ?>
 <?php echo $this->_tpl_vars['user']['last_name']; ?>
</option>
                  <?php endforeach; endif; unset($_from); ?>
                </select>
              <?php endif; ?>
            <?php else: ?>
              <span class="pad_left_small light_grey"><?php echo $this->_tpl_vars['LANG']['phrase_no_clients']; ?>
</span>
            <?php endif; ?>
          </td>
          <td align="center"><a href="<?php echo $this->_tpl_vars['same_page']; ?>
?page=edit_view&view_id=<?php echo $this->_tpl_vars['view_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_edit'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a></td>
          <td class="del"><a href="#" onclick="return page_ns.delete_view(<?php echo $this->_tpl_vars['view_id']; ?>
)"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_delete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a></td>
        </tr>

      <?php endforeach; endif; unset($_from); ?>

      </table>

    <?php endif; ?>

    <p>
      <?php if (count($this->_tpl_vars['all_form_views']) > 0): ?>
        <select name="create_view_from_view_id">
          <option value=""><?php echo $this->_tpl_vars['LANG']['phrase_new_blank_view']; ?>
</option>
          <optgroup label="<?php echo $this->_tpl_vars['LANG']['phrase_copy_view_settings_from']; ?>
">
            <?php $_from = $this->_tpl_vars['all_form_views']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
              <option value="<?php echo $this->_tpl_vars['i']['view_id']; ?>
"><?php echo $this->_tpl_vars['i']['view_name']; ?>
</option>
            <?php endforeach; endif; unset($_from); ?>
          </optgroup>
        </select>
      <?php endif; ?>
      <input type="submit" name="add_view" value="<?php echo $this->_tpl_vars['LANG']['phrase_create_new_view']; ?>
" />
    </p>

  </form>