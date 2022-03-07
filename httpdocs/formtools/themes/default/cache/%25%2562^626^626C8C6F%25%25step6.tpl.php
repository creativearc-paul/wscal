<?php /* Smarty version 2.6.18, created on 2010-12-01 16:33:30
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/install/templates/step6.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../install/templates/install_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <h1><?php echo $this->_tpl_vars['LANG']['phrase_clean_up']; ?>
</h1>

  <p class="notify">
    <?php echo $this->_tpl_vars['LANG']['text_ft_installed']; ?>

  </p>

 	<p>
 	  <?php echo $this->_tpl_vars['LANG']['text_must_delete_install_folder']; ?>

  </p>

    <ul>
      <li><a href="<?php echo $this->_tpl_vars['g_root_url']; ?>
"><?php echo $this->_tpl_vars['LANG']['text_log_in_to_ft']; ?>
</a></li>
      <li><a href="http://docs.formtools.org/tutorials/adding_first_form/"><?php echo $this->_tpl_vars['LANG']['text_tutorial_adding_first_form']; ?>
</a></li>
      <li><a href="http://docs.formtools.org/userdoc/"><?php echo $this->_tpl_vars['LANG']['text_review_user_doc']; ?>
</a></li>
    </ul>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../install/templates/install_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>