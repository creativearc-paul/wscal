<?php /* Smarty version 2.6.18, created on 2016-10-28 11:40:08
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/verify_url.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/default/admin/verify_url.tpl', 91, false),)), $this); ?>
<html>
<head>
  <title><?php echo $this->_tpl_vars['LANG']['phrase_verify_url']; ?>
</title>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/scripts/prototype.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['g_root_url']; ?>
/global/scripts/general.js"></script>

  <?php echo $this->_tpl_vars['head_js']; ?>


  <style type="text/css">
  <?php echo '
  body { margin: 0px; }
  input {
	  font: 11px Verdana, sans-serif;
  }
  #top_row {
    height: 32px;
    background-image: url('; ?>
<?php echo $this->_tpl_vars['images_url']; ?>
<?php echo '/popup_bg.jpg);
	  padding-left: 6px;
    padding-bottom: 0px;
    padding-top: 14px;
    border-bottom: 1px solid #666666;
  }
  #title {
	  font: 14px Verdana, sans-serif;
	  font-weight: bold;
	  color: #ffffff;
    padding-left: 50px
  }
  #logo {
    position:absolute;
    left: 6px;
    top: 0px;
  }
  #buttons {
    float:right;
    padding-right: 12px;
    margin-top: -2px;
  }
  .green { color: green; }
  .red { color: red; }
  </style>

  <script type="text/javascript">
  var page_ns = {};
  page_ns.accept_url = function()
  {
	  var url = $("url").value;
	  if (!url)
	  {
	    alert(g.messages["validation_no_url"]);
	    return false;
	  }
	  else if (!ft.is_valid_url(url))
	  {
	    alert(g.messages["validation_invalid_url"]);
	    return false;
	  }

    window.opener.ft.verify_url_page("'; ?>
<?php echo $this->_tpl_vars['form_page']; ?>
<?php echo '", url);
    window.close();
  }
  page_ns.update_url = function()
  {
	  var url = $("url").value;
	  if (!url)
	  {
	    alert(g.messages["validation_no_url"]);
	    return false;
	  }
	  else if (!ft.is_valid_url(url))
	  {
	    alert(g.messages["validation_invalid_url"]);
	    return false;
	  }

    $("iframe_content").src = url;
  }
  </script>
  '; ?>


</head>
<body>

<table cellspacing="0" cellpadding="0" style="width:100%; height: 100%">
<tr>
  <td height="46">
    <div id="top_row">
      <span id="buttons">
        <input type="text" id="url" style="width: 500px" value="<?php echo $this->_tpl_vars['url']; ?>
" />
        <input type="button" value="<?php echo $this->_tpl_vars['LANG']['word_update']; ?>
" onclick="page_ns.update_url()" />
        <input type="button" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_accept'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" class="green" onclick="page_ns.accept_url()" />
        <input type="button" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_close'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
" class="red" onclick="window.close()" />
      </span>
      <img src="<?php echo $this->_tpl_vars['images_url']; ?>
/popup_logo.jpg" id="logo" />
      <span id="title"><?php echo $this->_tpl_vars['LANG']['phrase_verify_url']; ?>
</span>
    </div>
  </td>
</tr>
<tr>
  <td>
    <iframe src="<?php echo $this->_tpl_vars['url']; ?>
" id="iframe_content" style="height: 100%; width:100%" frameborder="0"></iframe>
  </td>
</tr>
</table>

</body>
</html>