<?php /* Smarty version 2.6.18, created on 2010-12-01 16:34:03
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'template_hook', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/header.tpl', 21, false),array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/header.tpl', 82, false),)), $this); ?>
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

    <?php if ($this->_tpl_vars['SESSION']['account']['is_logged_in']): ?>
      <div style="float:right">
        <table cellspacing="0" cellpadding="0" height="25">
        <tr>
          <td><img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/account_section_left.jpg" border="0" /></td>
          <td id="account_section">
            <?php if ($this->_tpl_vars['settings']['release_type'] == 'beta'): ?>
              <b><?php echo $this->_tpl_vars['settings']['program_version']; ?>
-beta-<?php echo $this->_tpl_vars['settings']['release_date']; ?>
</b>
            <?php else: ?>
              <b><?php echo $this->_tpl_vars['settings']['program_version']; ?>
</b>
            <?php endif; ?>
            |
            <a href="#" onclick="return ft.check_updates()" class="update_link"><?php echo $this->_tpl_vars['LANG']['word_update']; ?>
</a>
          </td>
          <td><img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/account_section_right.jpg" border="0" /></td>
        </tr>
        </table>
      </div>
    <?php endif; ?>

    <span style="float:left; padding-top: 8px; padding-right: 10px">
      <a href="<?php echo $this->_tpl_vars['settings']['logo_link']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/logo.jpg" border="0" width="220" height="61" /></a>
    </span>
  </div>

  <div id="content">

    <table cellspacing="0" cellpadding="0" width="100%">
    <tr>
      <td width="180" valign="top">
        <div id="left_nav">
          <?php echo smarty_function_ft_include(array('file' => "menu.tpl"), $this);?>

        </div>
      </td>
      <td valign="top">
        <div style="width:740px">