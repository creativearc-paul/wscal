<?php /* Smarty version 2.6.18, created on 2010-12-01 16:34:03
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/index.tpl', 1, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/index.tpl', 68, false),array('function', 'clients_dropdown', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/index.tpl', 184, false),array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/index.tpl', 6, false),array('modifier', 'escape', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/index.tpl', 40, false),array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/admin/forms/index.tpl', 43, false),)), $this); ?>
<?php echo smarty_function_ft_include(array('file' => 'header.tpl'), $this);?>


  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/icon_forms.gif" width="34" height="34" /></td>
    <td class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_forms'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
  </tr>
  </table>

  <?php echo smarty_function_ft_include(array('file' => "messages.tpl"), $this);?>


  <?php if ($this->_tpl_vars['num_forms'] == 0): ?>
    <div><?php echo $this->_tpl_vars['LANG']['text_no_forms']; ?>
</div>
  <?php else: ?>

    <div id="search_form" class="margin_bottom_large">

      <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post">

        <table cellspacing="2" cellpadding="0" id="search_form_table">
        <tr>
          <td class="blue" width="70"><?php echo $this->_tpl_vars['LANG']['word_search']; ?>
</td>
          <td>
            <select name="client_id">
              <option value="" <?php if ($this->_tpl_vars['search_criteria']['client_id'] == ""): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['phrase_all_clients']; ?>
</option>
              <?php $_from = $this->_tpl_vars['clients']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['client']):
        $this->_foreach['row']['iteration']++;
?>
                <option value="<?php echo $this->_tpl_vars['client']['account_id']; ?>
" <?php if ($this->_tpl_vars['search_criteria']['client_id'] == $this->_tpl_vars['client']['account_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['client']['first_name']; ?>
 <?php echo $this->_tpl_vars['client']['last_name']; ?>
</option>
              <?php endforeach; endif; unset($_from); ?>
            </select>
          </td>
          <td>
            <select name="status">
              <option value="" <?php if ($this->_tpl_vars['search_criteria']['status'] == ""): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['phrase_all_statuses']; ?>
</option>
              <option value="online" <?php if ($this->_tpl_vars['search_criteria']['status'] == 'online'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_online']; ?>
</option>
              <option value="offline" <?php if ($this->_tpl_vars['search_criteria']['status'] == 'offline'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_offline']; ?>
</option>
              <option value="incomplete" <?php if ($this->_tpl_vars['search_criteria']['status'] == 'incomplete'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_incomplete']; ?>
</option>
            </select>
          </td>
          <td>
            <input type="text" size="20" name="keyword" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_criteria']['keyword'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
            <input type="submit" name="search_forms" value="<?php echo $this->_tpl_vars['LANG']['word_search']; ?>
" />
            <input type="button" name="reset" value="<?php echo $this->_tpl_vars['LANG']['phrase_show_all']; ?>
" onclick="window.location='<?php echo $this->_tpl_vars['same_page']; ?>
?reset=1'"
              <?php if (count($this->_tpl_vars['forms']) < $this->_tpl_vars['num_forms']): ?>
                class="bold"
              <?php else: ?>
                class="light_grey" disabled
              <?php endif; ?> />
          </td>
        </tr>
        </table>

      </form>

    </div>

    <?php if (count($this->_tpl_vars['forms']) == 0): ?>

      <div class="notify yellow_bg">
        <div style="padding: 8px">
          <?php echo $this->_tpl_vars['LANG']['text_no_forms_found']; ?>

        </div>
      </div>

    <?php else: ?>

      <?php echo $this->_tpl_vars['pagination']; ?>


      <?php echo smarty_function_template_hook(array('location' => 'admin_forms_list_top'), $this);?>


      <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" method="post">

      <?php $this->assign('table_group_id', '1'); ?>

            <?php $_from = $this->_tpl_vars['forms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['form_info']):
        $this->_foreach['row']['iteration']++;
?>
        <?php $this->assign('index', ($this->_foreach['row']['iteration']-1)); ?>
        <?php $this->assign('count', $this->_foreach['row']['iteration']); ?>
        <?php $this->assign('form_id', $this->_tpl_vars['form_info']['form_id']); ?>
        <?php $this->assign('clients', $this->_tpl_vars['form_info']['client_info']); ?>

                <?php if ($this->_tpl_vars['count'] == 1 || $this->_tpl_vars['count'] != 1 && ( ( $this->_tpl_vars['count']-1 ) % $this->_tpl_vars['settings']['num_forms_per_page'] == 0 )): ?>

          <?php if ($this->_tpl_vars['table_group_id'] == '1'): ?>
            <?php $this->assign('style', "display: block"); ?>
          <?php else: ?>
            <?php $this->assign('style', "display: none"); ?>
          <?php endif; ?>

          <div id="page_<?php echo $this->_tpl_vars['table_group_id']; ?>
" style="<?php echo $this->_tpl_vars['style']; ?>
">

            <table class="list_table" width="100%" cellpadding="0" cellspacing="1">
            <tr>
              <th width="30">

                <?php $this->assign('up_down', ""); ?>
                <?php if ($this->_tpl_vars['order'] == "form_id-DESC"): ?>
                  <?php $this->assign('sort_order', "order=form_id-ASC"); ?>
                  <?php $this->assign('up_down', "<img src=\"".($this->_tpl_vars['theme_url'])."/images/sort_down.gif\" />"); ?>
                <?php elseif ($this->_tpl_vars['order'] == "form_id-ASC"): ?>
                  <?php $this->assign('sort_order', "order=form_id-DESC"); ?>
                  <?php $this->assign('up_down', "<img src=\"".($this->_tpl_vars['theme_url'])."/images/sort_up.gif\" />"); ?>
                <?php else: ?>
                  <?php $this->assign('sort_order', "order=form_id-DESC"); ?>
                <?php endif; ?>

                <table cellspacing="0" cellpadding="0" align="center" class="pad_left_small">
                <tr>
                  <td><a href="<?php echo $this->_tpl_vars['same_page']; ?>
?<?php echo $this->_tpl_vars['sort_order']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_id'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a></td>
                  <td class="pad_left"><?php echo $this->_tpl_vars['up_down']; ?>
</td>
                </tr>
                </table>

              </th>
              <th>

                <?php $this->assign('up_down', ""); ?>
                <?php if ($this->_tpl_vars['order'] == "form_name-DESC"): ?>
                  <?php $this->assign('sort_order', "order=form_name-ASC"); ?>
                  <?php $this->assign('up_down', "<img src=\"".($this->_tpl_vars['theme_url'])."/images/sort_down.gif\" />"); ?>
                <?php elseif ($this->_tpl_vars['order'] == "form_name-ASC"): ?>
                  <?php $this->assign('sort_order', "order=form_name-DESC"); ?>
                  <?php $this->assign('up_down', "<img src=\"".($this->_tpl_vars['theme_url'])."/images/sort_up.gif\" />"); ?>
                <?php else: ?>
                  <?php $this->assign('sort_order', "order=form_name-DESC"); ?>
                <?php endif; ?>

                <table cellspacing="0" cellpadding="0" align="center" class="pad_left_small">
                <tr>
                  <td><a href="<?php echo $this->_tpl_vars['same_page']; ?>
?<?php echo $this->_tpl_vars['sort_order']; ?>
"><?php echo $this->_tpl_vars['LANG']['word_form']; ?>
</a></td>
                  <td class="pad_left"><?php echo $this->_tpl_vars['up_down']; ?>
</td>
                </tr>
                </table>

              </th>
              <th><?php echo $this->_tpl_vars['LANG']['phrase_who_can_access']; ?>
</th>
              <th width="70">

                <?php $this->assign('up_down', ""); ?>
                <?php if ($this->_tpl_vars['order'] == "status-DESC"): ?>
                  <?php $this->assign('sort_order', "order=status-ASC"); ?>
                  <?php $this->assign('up_down', "<img src=\"".($this->_tpl_vars['theme_url'])."/images/sort_down.gif\" />"); ?>
                <?php elseif ($this->_tpl_vars['order'] == "status-ASC"): ?>
                  <?php $this->assign('sort_order', "order=status-DESC"); ?>
                  <?php $this->assign('up_down', "<img src=\"".($this->_tpl_vars['theme_url'])."/images/sort_up.gif\" />"); ?>
                <?php else: ?>
                  <?php $this->assign('sort_order', "order=status-DESC"); ?>
                <?php endif; ?>

                <table cellspacing="0" cellpadding="0" align="center" class="pad_left_small">
                <tr>
                  <td><a href="<?php echo $this->_tpl_vars['same_page']; ?>
?<?php echo $this->_tpl_vars['sort_order']; ?>
"><?php echo $this->_tpl_vars['LANG']['word_status']; ?>
</a></td>
                  <td class="pad_left"><?php echo $this->_tpl_vars['up_down']; ?>
</td>
                </tr>
                </table>

              </th>
              <th width="90"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_submissions'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
              <th width="70"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_edit'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
              <th class="del" width="70"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_delete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</th>
            </tr>

        <?php endif; ?>

          <tr>
            <td align="center" class="medium_grey"><?php echo $this->_tpl_vars['form_id']; ?>
</td>
            <td class="pad_left_small"><a href="<?php echo $this->_tpl_vars['form_info']['form_url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['form_info']['form_name']; ?>
</a></td>
            <td>

                            <?php if ($this->_tpl_vars['form_info']['is_complete'] == 'no'): ?>

              <?php elseif ($this->_tpl_vars['form_info']['access_type'] == 'admin'): ?>
                <span class="medium_grey pad_left_small"><?php echo $this->_tpl_vars['LANG']['phrase_admin_only']; ?>
</span>
              <?php elseif ($this->_tpl_vars['form_info']['access_type'] == 'public'): ?>

                <?php if (count($this->_tpl_vars['form_info']['client_omit_list']) == 0): ?>
                  <span class="pad_left_small blue"><?php echo $this->_tpl_vars['LANG']['phrase_all_clients']; ?>
</span>
                <?php else: ?>
                  <span class="pad_left_small blue">All clients, except: </span>
                  <?php echo smarty_function_clients_dropdown(array('only_show_clients' => $this->_tpl_vars['form_info']['client_omit_list'],'display_single_client_as_text' => true), $this);?>

                <?php endif; ?>

              <?php else: ?>

                <?php if (count($this->_tpl_vars['clients']) == 0): ?>
                  <span class="pad_left_small light_grey"><?php echo $this->_tpl_vars['LANG']['phrase_no_clients']; ?>
</span>
                <?php elseif (count($this->_tpl_vars['clients']) == 1): ?>
                  <span class="pad_left_small"><?php echo $this->_tpl_vars['clients'][0]['first_name']; ?>
 <?php echo $this->_tpl_vars['clients'][0]['last_name']; ?>
</span>
                <?php else: ?>
                  <select>
                    <?php $_from = $this->_tpl_vars['clients']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['row2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['row2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['client']):
        $this->_foreach['row2']['iteration']++;
?>
                      <option><?php echo $this->_tpl_vars['client']['first_name']; ?>
 <?php echo $this->_tpl_vars['client']['last_name']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                  </select>
                <?php endif; ?>
              <?php endif; ?>

            </td>
            <td align="center">

              <?php if ($this->_tpl_vars['form_info']['is_active'] == 'no'): ?>
                <?php $this->assign('status', "<span style=\"color: orange\">".($this->_tpl_vars['LANG']['word_offline'])."</span>"); ?>
              <?php else: ?>
                <?php $this->assign('status', "<span class=\"light_green\">".($this->_tpl_vars['LANG']['word_online'])."</span>"); ?>
              <?php endif; ?>

              <?php if ($this->_tpl_vars['form_info']['is_complete'] == 'no'): ?>
                <?php $this->assign('status', "<span style=\"color: red\">".($this->_tpl_vars['LANG']['word_incomplete'])."</span>"); ?>
                <?php $this->assign('file', 'add/step1.php'); ?>
              <?php else: ?>
                <?php $this->assign('file', 'edit.php'); ?>
              <?php endif; ?>

              <?php echo $this->_tpl_vars['status']; ?>


            </td>
            <td align="center">

              <?php if ($this->_tpl_vars['form_info']['is_complete'] == 'yes'): ?>
                <?php $this->assign('num_form_submissions', "form_".($this->_tpl_vars['form_id'])."_num_submissions"); ?>
                (<?php echo $this->_tpl_vars['SESSION'][$this->_tpl_vars['num_form_submissions']]; ?>
)&nbsp;<a href="submissions.php?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_view'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a>
              <?php endif; ?>

            </td>
            <td align="center">

              <?php if ($this->_tpl_vars['form_info']['is_complete'] == 'yes'): ?>
                <a href="<?php echo $this->_tpl_vars['file']; ?>
?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_edit'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a>
              <?php else: ?>
                <a href="<?php echo $this->_tpl_vars['file']; ?>
?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_complete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a>
              <?php endif; ?>

            </td>
            <td class="del"><a href="delete_form.php?form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_delete'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</a></td>
          </tr>

        <?php if ($this->_tpl_vars['count'] != 1 && ( $this->_tpl_vars['count'] % $this->_tpl_vars['settings']['num_forms_per_page'] ) == 0): ?>
          </table></div>
          <?php $this->assign('table_group_id', $this->_tpl_vars['table_group_id']+1); ?>
        <?php endif; ?>

      <?php endforeach; endif; unset($_from); ?>

            <?php if (( count($this->_tpl_vars['forms']) % $this->_tpl_vars['settings']['num_forms_per_page'] ) != 0): ?>
        </table></div>
      <?php endif; ?>

    <?php endif; ?>

    </form>

  <?php endif; ?>

  <p>
    <form method="post" action="add/step1.php">
      <input type="submit" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_add_form'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
    </form>
  </p>

  <?php echo smarty_function_template_hook(array('location' => 'admin_forms_list_bottom'), $this);?>


<?php echo smarty_function_ft_include(array('file' => "footer.tpl"), $this);?>