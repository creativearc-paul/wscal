<?php /* Smarty version 2.6.18, created on 2015-09-17 12:23:21
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'template_hook', 'header.tpl', 20, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="<?php echo $this->_tpl_vars['LANG']['special_text_direction']; ?>
">
<head>
  <title><?php echo $this->_tpl_vars['head_title']; ?>
</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="shortcut icon" href="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/favicon.ico" >
  <?php echo smarty_function_template_hook(array('location' => 'head_top'), $this);?>


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

  <link type="text/css" rel="stylesheet" href="<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/css/main.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $this->_tpl_vars['theme_url']; ?>
/css/styles.css">
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/scripts/prototype.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/scripts/scriptaculous.js?load=effects"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/scripts/effects.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/scripts/general.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/scripts/rsv.js"></script>

  <?php echo $this->_tpl_vars['head_string']; ?>

  <?php echo $this->_tpl_vars['head_js']; ?>

  <?php echo $this->_tpl_vars['head_css']; ?>

  <?php echo smarty_function_template_hook(array('location' => 'head_bottom'), $this);?>

</head>
<body>

<div id="container">

	<div id="header">
        <a href="http://wscal.edu/formtools" style="color:#ffffff;font-size:16px;text-decoration:none;line-height:60px;padding-left:30px;">WSCAL Form Tools</a>
    </div>
	<div id="header_row">

    <div id="left_nav_top">
      <?php if ($this->_tpl_vars['SESSION']['account']['is_logged_in']): ?>
        <?php if ($this->_tpl_vars['settings']['release_type'] == 'beta'): ?>
          <b><?php echo $this->_tpl_vars['settings']['program_version']; ?>
-beta-<?php echo $this->_tpl_vars['settings']['release_date']; ?>
</b>
        <?php else: ?>
          <b><?php echo $this->_tpl_vars['settings']['program_version']; ?>
</b>
        <?php endif; ?>
				&nbsp;
        <a href="#" onclick="return ft.check_updates()" class="update_link"><?php echo $this->_tpl_vars['LANG']['word_update']; ?>
</a>
	  	<?php else: ?>
	  	  <div style="height: 20px"> </div>
	    <?php endif; ?>
    </div>

	</div>

	  <div class="outer">
	  <div class="inner">
	    <div class="float-wrap">
	    <div id="content">

			  <div class="content_wrap">

					<div id="main_window">
					  <div id="page_content">
