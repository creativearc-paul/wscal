<?php /* Smarty version 2.6.18, created on 2015-10-02 12:39:40
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email.tpl', 6, false),array('modifier', 'count', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email.tpl', 17, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/forms/tab_edit_email.tpl', 9, false),)), $this); ?>
    <div class="previous_page_icon">
      <a href="edit.php?page=emails&form_id=<?php echo $this->_tpl_vars['form_id']; ?>
"><img src="<?php echo $this->_tpl_vars['images_url']; ?>
/up.jpg" title="<?php echo $this->_tpl_vars['LANG']['phrase_previous_page']; ?>
" alt="<?php echo $this->_tpl_vars['LANG']['phrase_previous_page']; ?>
" border="0" /></a>
    </div>

    <div class="underline margin_top_large">
      <span class="subtitle"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_edit_email_template'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
    </div>

    <?php echo smarty_function_ft_include(array('file' => 'messages.tpl'), $this);?>


    <form method="post" id="edit_email_template_form" action="<?php echo $this->_tpl_vars['same_page']; ?>
?page=edit_email"
      onsubmit="return page_ns.onsubmit_check_email_settings(this)">

            <input type="hidden" name="form_id" id="form_id" value="<?php echo $this->_tpl_vars['form_id']; ?>
" />
      <input type="hidden" name="email_id" id="email_id" value="<?php echo $this->_tpl_vars['email_id']; ?>
" />
      <input type="hidden" name="num_recipients" id="num_recipients" value="<?php echo count($this->_tpl_vars['template_info']['recipients']); ?>
" />

      <div class="inner_tab_set">
        <div style="position:relative; height:20">
          <div style="left:0%;  width:20%;" id="inner_tab1" <?php if ($this->_tpl_vars['edit_email_tab'] == 1): ?>class="inner_tab_selected"<?php else: ?>class="inner_tab_unselected"<?php endif; ?>>
            <a href="#" onclick="return ft.change_inner_tab(1, 5, 'edit_email_tab')"><?php echo $this->_tpl_vars['LANG']['word_configuration']; ?>
</a>
          </div>
          <div style="left:20%; width:20%;" id="inner_tab2" <?php if ($this->_tpl_vars['edit_email_tab'] == 2): ?>class="inner_tab_selected"<?php else: ?>class="inner_tab_unselected"<?php endif; ?>>
            <a href="#" onclick="return ft.change_inner_tab(2, 5, 'edit_email_tab')"><?php echo $this->_tpl_vars['LANG']['word_recipient_sp']; ?>
</a>
          </div>
          <div style="left:40%; width:20%;" id="inner_tab3" <?php if ($this->_tpl_vars['edit_email_tab'] == 3): ?>class="inner_tab_selected"<?php else: ?>class="inner_tab_unselected"<?php endif; ?>>
            <a href="#" onclick="return ft.change_inner_tab(3, 5, 'edit_email_tab')"><?php echo $this->_tpl_vars['LANG']['word_content']; ?>
</a>
          </div>
          <div style="left:60%; width:20%;" id="inner_tab4" <?php if ($this->_tpl_vars['edit_email_tab'] == 4): ?>class="inner_tab_selected"<?php else: ?>class="inner_tab_unselected"<?php endif; ?>>
            <a href="#" onclick="return ft.change_inner_tab(4, 5, 'edit_email_tab')"><?php echo $this->_tpl_vars['LANG']['word_test']; ?>
</a>
          </div>
          <div style="left:80%; width:20%;" id="inner_tab5" <?php if ($this->_tpl_vars['edit_email_tab'] == 5): ?>class="inner_tab_selected"<?php else: ?>class="inner_tab_unselected"<?php endif; ?>>
            <a href="#" onclick="return ft.change_inner_tab(5, 5, 'edit_email_tab')"><?php echo $this->_tpl_vars['LANG']['word_reference']; ?>
</a>
          </div>
        </div>
        <div class="inner_tab_content">

          <div id="inner_tab_content1" <?php if ($this->_tpl_vars['edit_email_tab'] != 1): ?>style="display:none"<?php endif; ?>>
            <br />
            <?php echo smarty_function_ft_include(array('file' => "admin/forms/tab_edit_email_tab1.tpl"), $this);?>

          </div>

          <div id="inner_tab_content2" <?php if ($this->_tpl_vars['edit_email_tab'] != 2): ?>style="display:none"<?php endif; ?>>
            <br />
            <?php echo smarty_function_ft_include(array('file' => "admin/forms/tab_edit_email_tab2.tpl"), $this);?>

          </div>

          <div id="inner_tab_content3" <?php if ($this->_tpl_vars['edit_email_tab'] != 3): ?>style="display:none"<?php endif; ?>>
            <br />
            <?php echo smarty_function_ft_include(array('file' => "admin/forms/tab_edit_email_tab3.tpl"), $this);?>

          </div>

          <div id="inner_tab_content4" <?php if ($this->_tpl_vars['edit_email_tab'] != 4): ?>style="display:none"<?php endif; ?>>
            <br />
            <?php echo smarty_function_ft_include(array('file' => "admin/forms/tab_edit_email_tab4.tpl"), $this);?>

          </div>

          <div id="inner_tab_content5" <?php if ($this->_tpl_vars['edit_email_tab'] != 5): ?>style="display:none"<?php endif; ?>>
            <br />
            <?php echo smarty_function_ft_include(array('file' => "admin/forms/tab_edit_email_tab5.tpl"), $this);?>

          </div>
        </div>
      </div>

      <p>
        <input type="submit" name="update_email_template" value="<?php echo $this->_tpl_vars['LANG']['phrase_update_email_template']; ?>
" />
      </p>

    </form>