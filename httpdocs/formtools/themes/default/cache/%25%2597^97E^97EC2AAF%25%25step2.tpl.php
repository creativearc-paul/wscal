<?php /* Smarty version 2.6.18, created on 2010-12-01 16:30:30
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/install/templates/step2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/install/templates/step2.tpl', 15, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../install/templates/install_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <h1><?php echo $this->_tpl_vars['LANG']['phrase_system_check']; ?>
</h1>

  <p>
    <?php echo $this->_tpl_vars['LANG']['text_install_system_check']; ?>

  </p>

  <table cellspacing="0" cellpadding="2" width="600" class="info">
  <tr>
    <td width="160"><?php echo $this->_tpl_vars['LANG']['phrase_php_version']; ?>
</td>
    <td class="bold"><?php echo $this->_tpl_vars['phpversion']; ?>
</td>
    <td width="100" align="center">
      <?php if ($this->_tpl_vars['valid_php_version']): ?>
        <span class="green"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_pass'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
      <?php else: ?>
        <span class="red"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_fail'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->_tpl_vars['LANG']['phrase_mysql_version']; ?>
</td>
    <td class="bold"><?php echo $this->_tpl_vars['mysql_get_client_info']; ?>
</td>
    <td align="center">
      <?php if ($this->_tpl_vars['overridden_invalid_db_version']): ?>
        <span class="orange"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_overridden'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
      <?php else: ?>
			  <?php if ($this->_tpl_vars['valid_mysql_version']): ?>
          <span class="green"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_pass'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
        <?php else: ?>
          <span class="red"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_fail'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
          <form action="step2.php" method="post">
            <input type="submit" name="override_invalid_db_version" value="<?php echo $this->_tpl_vars['LANG']['word_ignore']; ?>
" />
  			  </form>
        <?php endif; ?>
			<?php endif; ?>
    </td>
  </tr>
  <tr>
    <td rowspan="2" valign="top"><?php echo $this->_tpl_vars['LANG']['phrase_write_permissions']; ?>
</td>
    <td class="bold">
      /upload/
    </td>
    <td align="center">
      <?php if ($this->_tpl_vars['upload_folder_writable']): ?>
        <span class="green"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_pass'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
      <?php else: ?>
        <span class="red"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_fail'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td class="bold">
      /themes/<?php echo $this->_tpl_vars['g_default_theme']; ?>
/cache/
    </td>
    <td align="center">
      <?php if ($this->_tpl_vars['default_theme_cache_dir_writable']): ?>
        <span class="green"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_pass'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
      <?php else: ?>
        <span class="red"><?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_fail'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
      <?php endif; ?>
    </td>
  </tr>
  </table>

  <br />

  <?php if (! $this->_tpl_vars['valid_php_version'] || ! $this->_tpl_vars['valid_mysql_version']): ?>

    <p class="error" style="padding: 6px">
	      <?php echo $this->_tpl_vars['LANG']['text_install_form_tools_server_not_supported']; ?>

	  </p>

  <?php else: ?>

    <form action="step3.php" method="post">
		  <p>
		    <input type="submit" name="next" value="<?php echo $this->_tpl_vars['LANG']['word_continue_rightarrow']; ?>
" />
		  </p>
    </form>

  <?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../install/templates/install_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>