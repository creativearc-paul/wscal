<?php /* Smarty version 2.6.18, created on 2010-12-01 16:30:23
         compiled from ../../install/templates/install_header.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="<?php echo $this->_tpl_vars['LANG']['special_text_direction']; ?>
">
<head>
  <title><?php echo $this->_tpl_vars['LANG']['phrase_ft_installation']; ?>
</title>

  <script type="text/javascript">
  //<![CDATA[
  var g = <?php echo '{}'; ?>
;
  g.root_url = "<?php echo $this->_tpl_vars['g_root_url']; ?>
";
  g.error_colours = ["ffbfbf", "ffeded"];
  g.notify_colours = ["c6e2ff", "f2f8ff"];
  //]]>
  </script>

  <link rel="stylesheet" type="text/css" href="files/main.css">
  <script type="text/javascript" src="../global/scripts/prototype.js"></script>
  <script type="text/javascript" src="../global/scripts/scriptaculous.js?load=effects"></script>
  <script type="text/javascript" src="../global/scripts/general.js"></script>
  <script type="text/javascript" src="../global/scripts/rsv.js"></script>

  <?php echo $this->_tpl_vars['head_js']; ?>


</head>
<body>

<div id="container">

	<div id="header">

    <div style="float:right">
	    <table cellspacing="0" cellpadding="0" height="25">
	    <tr>
	      <td><img src="images/account_section_left.jpg" border="0" /></td>
	      <td id="account_section">
		      <b><?php echo $this->_tpl_vars['version_string']; ?>
</b>
	      </td>
	      <td><img src="images/account_section_right.jpg" border="0" /></td>
	    </tr>
	    </table>
    </div>

    <span style="float:left; padding-top: 8px; padding-right: 10px">
      <a href="http://www.formtools.org" class="no_border"><img src="images/logo.jpg" border="0" width="359" height="61" /></a>
    </span>
	</div>

  <div id="content">

		<table cellpadding="0" cellspacing="0" id="body" align="center" width="100%">
		<tr>
		  <td id="main">

		  <table cellpadding="0" cellspacing="0" width="100%">
		  <tr>
		    <td valign="top" width="200">

		      <div id="nav_items">
			      <div class="<?php if ($this->_tpl_vars['step'] == 1): ?>nav_current<?php else: ?>nav_visited<?php endif; ?>">1. <?php echo $this->_tpl_vars['LANG']['word_welcome']; ?>
</div>
			      <div class="<?php if ($this->_tpl_vars['step'] == 2): ?>nav_current<?php elseif ($this->_tpl_vars['step'] < 2): ?>nav_remaining<?php else: ?>nav_visited<?php endif; ?>">2. <?php echo $this->_tpl_vars['LANG']['phrase_system_check']; ?>
</div>
			      <div class="<?php if ($this->_tpl_vars['step'] == 3): ?>nav_current<?php elseif ($this->_tpl_vars['step'] < 3): ?>nav_remaining<?php else: ?>nav_visited<?php endif; ?>">3. <?php echo $this->_tpl_vars['LANG']['phrase_create_database_tables']; ?>
</div>
			      <div class="<?php if ($this->_tpl_vars['step'] == 4): ?>nav_current<?php elseif ($this->_tpl_vars['step'] < 4): ?>nav_remaining<?php else: ?>nav_visited<?php endif; ?>">4. <?php echo $this->_tpl_vars['LANG']['phrase_create_config_file']; ?>
</div>
			      <div class="<?php if ($this->_tpl_vars['step'] == 5): ?>nav_current<?php elseif ($this->_tpl_vars['step'] < 5): ?>nav_remaining<?php else: ?>nav_visited<?php endif; ?>">5. <?php echo $this->_tpl_vars['LANG']['phrase_create_admin_account']; ?>
</div>
			      <div class="<?php if ($this->_tpl_vars['step'] == 6): ?>nav_current<?php elseif ($this->_tpl_vars['step'] < 6): ?>nav_remaining<?php else: ?>nav_visited<?php endif; ?>">6. <?php echo $this->_tpl_vars['LANG']['phrase_clean_up']; ?>
</div>
			    </div>
		    </td>
		    <td width="15"> </td>
		    <td valign="top">