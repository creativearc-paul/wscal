<?php /* Smarty version 2.6.18, created on 2016-02-11 10:22:00
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/settings/tab_wysiwyg.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/settings/tab_wysiwyg.tpl', 1, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/settings/tab_wysiwyg.tpl', 3, false),array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/settings/tab_wysiwyg.tpl', 71, false),)), $this); ?>
  <div class="subtitle underline margin_top_large"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['phrase_wysiwyg_editor'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>

  <?php echo smarty_function_ft_include(array('file' => 'messages.tpl'), $this);?>


  <form action="<?php echo $this->_tpl_vars['same_page']; ?>
" id="wysiwyg_config_form">
    <input type="hidden" name="page" value="wysiwyg" />

    <table cellspacing="0" cellpadding="1">
    <tr>
      <td width="170"><?php echo $this->_tpl_vars['LANG']['word_toolbar']; ?>
</td>
      <td>
        <select name="tinymce_toolbar" class="update_example">
          <option value="basic" <?php if ($this->_tpl_vars['settings']['tinymce_toolbar'] == 'basic'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_basic']; ?>
</option>
          <option value="simple" <?php if ($this->_tpl_vars['settings']['tinymce_toolbar'] == 'simple'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_simple']; ?>
</option>
          <option value="advanced" <?php if ($this->_tpl_vars['settings']['tinymce_toolbar'] == 'advanced'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_advanced']; ?>
</option>
          <option value="expert" <?php if ($this->_tpl_vars['settings']['tinymce_toolbar'] == 'expert'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG']['word_expert']; ?>
</option>
        </select>
      </td>
    </tr>
    <tr>
      <td><?php echo $this->_tpl_vars['LANG']['phrase_toolbar_location']; ?>
</td>
      <td>
        <input type="radio" name="tinymce_toolbar_location" class="update_example" id="ttl1" value="top"
          <?php if ($this->_tpl_vars['settings']['tinymce_toolbar_location'] == 'top'): ?>checked<?php endif; ?> /> <label for="ttl1"><?php echo $this->_tpl_vars['LANG']['word_top']; ?>
</label>
        <input type="radio" name="tinymce_toolbar_location" class="update_example" id="ttl2" value="bottom"
          <?php if ($this->_tpl_vars['settings']['tinymce_toolbar_location'] == 'bottom'): ?>checked<?php endif; ?> /> <label for="ttl2"><?php echo $this->_tpl_vars['LANG']['word_bottom']; ?>
</label>
      </td>
    </tr>
    <tr>
      <td><?php echo $this->_tpl_vars['LANG']['phrase_toolbar_alignment']; ?>
</td>
      <td>
        <input type="radio" name="tinymce_toolbar_align" class="update_example" id="tinymce_toolbar_align1" value="left"
          <?php if ($this->_tpl_vars['settings']['tinymce_toolbar_align'] == 'left'): ?>checked<?php endif; ?> /> <label for="tinymce_toolbar_align1"><?php echo $this->_tpl_vars['LANG']['word_left']; ?>
</label>
        <input type="radio" name="tinymce_toolbar_align" class="update_example" id="tinymce_toolbar_align2" value="center"
          <?php if ($this->_tpl_vars['settings']['tinymce_toolbar_align'] == 'center'): ?>checked<?php endif; ?> /> <label for="tinymce_toolbar_align2"><?php echo $this->_tpl_vars['LANG']['word_center']; ?>
</label>
        <input type="radio" name="tinymce_toolbar_align" class="update_example" id="tinymce_toolbar_align3" value="right"
          <?php if ($this->_tpl_vars['settings']['tinymce_toolbar_align'] == 'right'): ?>checked<?php endif; ?> /> <label for="tinymce_toolbar_align3"><?php echo $this->_tpl_vars['LANG']['word_right']; ?>
</label>
      </td>
    </tr>
    <tr>
      <td><?php echo $this->_tpl_vars['LANG']['phrase_show_path_information']; ?>
</td>
      <td>
        <input type="radio" name="tinymce_show_path" class="update_example" id="tinymce_show_path1" value="yes"
          <?php if ($this->_tpl_vars['settings']['tinymce_show_path'] == 'yes'): ?>checked<?php endif; ?> /> <label for="tinymce_show_path1"><?php echo $this->_tpl_vars['LANG']['word_yes']; ?>
</label>
        <input type="radio" name="tinymce_show_path" class="update_example" id="tinymce_show_path2" value="no"
          <?php if ($this->_tpl_vars['settings']['tinymce_show_path'] == 'no'): ?>checked<?php endif; ?> /> <label for="tinymce_show_path2"><?php echo $this->_tpl_vars['LANG']['word_no']; ?>
</label>
      </td>
    </tr>
    <tr>
      <td>&mdash; <?php echo $this->_tpl_vars['LANG']['phrase_path_info_location']; ?>
</td>
      <td>
        <input type="radio" name="tinymce_path_info_location" class="update_example" id="tinymce_path_info_location1" value="top"
          <?php if ($this->_tpl_vars['settings']['tinymce_path_info_location'] == 'top'): ?>checked<?php endif; ?> <?php if ($this->_tpl_vars['settings']['tinymce_show_path'] == 'no'): ?>disabled<?php endif; ?>
          /> <label for="tinymce_path_info_location1"><?php echo $this->_tpl_vars['LANG']['word_top']; ?>
</label>
        <input type="radio" name="tinymce_path_info_location" class="update_example" id="tinymce_path_info_location2" value="bottom"
          <?php if ($this->_tpl_vars['settings']['tinymce_path_info_location'] == 'bottom'): ?>checked<?php endif; ?> <?php if ($this->_tpl_vars['settings']['tinymce_show_path'] == 'no'): ?>disabled<?php endif; ?>
          /> <label for="tinymce_path_info_location2"><?php echo $this->_tpl_vars['LANG']['word_bottom']; ?>
</label>
      </td>
    </tr>
    <tr>
      <td>&mdash; <?php echo $this->_tpl_vars['LANG']['phrase_allow_toolbar_resizing']; ?>
</td>
      <td>
        <input type="radio" name="tinymce_resize" class="update_example" id="tinymce_resize1" value="yes"
          <?php if ($this->_tpl_vars['settings']['tinymce_resize'] == 'yes'): ?>checked<?php endif; ?> <?php if ($this->_tpl_vars['settings']['tinymce_show_path'] == 'no'): ?>disabled<?php endif; ?>
          /> <label for="tinymce_resize1"><?php echo $this->_tpl_vars['LANG']['word_yes']; ?>
</label>
        <input type="radio" name="tinymce_resize" class="update_example" id="tinymce_resize2" value="no"
          <?php if ($this->_tpl_vars['settings']['tinymce_resize'] == 'no'): ?>checked<?php endif; ?> <?php if ($this->_tpl_vars['settings']['tinymce_show_path'] == 'no'): ?>disabled<?php endif; ?>
          /> <label for="tinymce_resize2"><?php echo $this->_tpl_vars['LANG']['word_no']; ?>
</label>
      </td>
    </tr>
    <?php echo smarty_function_template_hook(array('location' => 'admin_settings_wysiwyg_bottom'), $this);?>
		
    </table>


    <p class="bold"><?php echo $this->_tpl_vars['LANG']['phrase_example_editor']; ?>
</p>

    <div>
      <textarea id="example" name="example" rows="8" cols="90" style="width: 100%"><?php echo $this->_tpl_vars['LANG']['text_example_wysiwyg']; ?>
</textarea>
    </div>

    <p>
      <input type="submit" name="update_wysiwyg" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_update'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" />
    </p>

  </form>