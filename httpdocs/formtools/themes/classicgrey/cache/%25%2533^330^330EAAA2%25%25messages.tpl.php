<?php /* Smarty version 2.6.18, created on 2015-10-02 13:24:31
         compiled from /var/www/vhosts/wscal.edu/httpdocs/formtools/themes/classicgrey/../../global/smarty/messages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', '/var/www/vhosts/wscal.edu/httpdocs/formtools/themes/classicgrey/../../global/smarty/messages.tpl', 75, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="<?php echo $this->_tpl_vars['LANG']['special_text_direction']; ?>
">
<head>
  <title><?php echo $this->_tpl_vars['LANG']['special_form_tools']; ?>
</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="shortcut icon" href="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/favicon.ico" >

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
	          <b><?php echo $this->_tpl_vars['settings']['program_version']; ?>
</b>
	        </td>
	        <td><img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/account_section_right.jpg" border="0" /></td>
	      </tr>
	      </table>
	    </div>
    <?php endif; ?>

    <span style="float:left; padding-top: 7px; padding-right: 10px">
      <img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/logo.jpg" border="0" width="220" height="61" />
    </span>
  </div>

  <div id="content">

    <div class="title underline">
			<?php if ($this->_tpl_vars['message_type'] == 'error'): ?>
			  <span class="red bold">
			    <?php if ($this->_tpl_vars['title']): ?>
			      <?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

			    <?php else: ?>
			      <?php echo ((is_array($_tmp=$this->_tpl_vars['LANG']['word_error'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

			    <?php endif; ?>
			  </span>
			<?php else: ?>
	      <span class="blue bold">
	        <?php if ($this->_tpl_vars['title']): ?>
	          <?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

	        <?php else: ?>
	        <?php endif; ?>
	      </span>
			<?php endif; ?>
    </div>

    <?php if (isset ( $this->_tpl_vars['message'] )): ?>
	    <p><?php echo $this->_tpl_vars['message']; ?>
</p>
    <?php endif; ?>

    <?php if (isset ( $this->_tpl_vars['error_code'] )): ?>
      <p>
        <b><?php echo $this->_tpl_vars['LANG']['phrase_type_c']; ?>

          <?php if ($this->_tpl_vars['error_type'] == 'system'): ?>
            <span class="red"><?php echo $this->_tpl_vars['LANG']['word_system']; ?>
</span>
          <?php else: ?>
            <span class="green"><?php echo $this->_tpl_vars['LANG']['word_user']; ?>
</span>
          <?php endif; ?><br />
        <b><?php echo $this->_tpl_vars['LANG']['phrase_code_c']; ?>
 #<?php echo $this->_tpl_vars['error_code']; ?>
</b> &#8212;
        <a href="http://docs.formtools.org/api/index.php?page=error_codes#<?php echo $this->_tpl_vars['error_code']; ?>
" target="_blank" /><?php echo $this->_tpl_vars['LANG']['phrase_error_learn_more']; ?>
</a>
      </p>
    <?php endif; ?>

    <?php if (isset ( $this->_tpl_vars['error_codes'] )): ?>
      <p>
        <div><?php echo $this->_tpl_vars['LANG']['phrase_errors_learn_more']; ?>
</div>

        <b><?php echo $this->_tpl_vars['LANG']['phrase_codes_c']; ?>
</b>

        <?php $_from = $this->_tpl_vars['error_codes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
          <a href="http://docs.formtools.org/api/index.php?page=error_codes#<?php echo $this->_tpl_vars['row']; ?>
" target="_blank" /><?php echo $this->_tpl_vars['row']; ?>
</a>
        <?php endforeach; endif; unset($_from); ?>
      </p>
    <?php endif; ?>

    <?php if (isset ( $this->_tpl_vars['debugging'] )): ?>
      <h4><?php echo $this->_tpl_vars['LANG']['word_debugging_c']; ?>
</h4>
      <p>
        <?php echo $this->_tpl_vars['debugging']; ?>

      </p>
    <?php endif; ?>

	</td>
</tr>
</table>

</div>


<?php if ($this->_tpl_vars['account']['settings']['footer_text'] != ""): ?>
  <div id="footer">
    <div style="padding-top:3px;"><?php echo $this->_tpl_vars['account']['settings']['footer_text']; ?>
</div>
  </div>
<?php endif; ?>

</body>
</html>