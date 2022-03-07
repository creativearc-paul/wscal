<?php /* Smarty version 2.6.18, created on 2013-02-27 11:38:56
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/deepblue/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ft_include', '/var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/deepblue/footer.tpl', 20, false),)), $this); ?>
            </div>
          </div>
        </div>

        <div id="left">
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

					<?php echo smarty_function_ft_include(array('file' => "menu.tpl"), $this);?>

        </div>

      </div>

      <div class="clear"></div>

    </div>
  </div>
</div>

<div id="footer">
  <span style="float:right"><img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/footer_right.jpg" width="16" height="37" /></span>
  <span style="float:left"><img src="<?php echo $this->_tpl_vars['theme_url']; ?>
/images/footer_left.jpg" width="13" height="37" /></span>
  <div style="padding-top:3px;"><?php echo $this->_tpl_vars['account']['settings']['footer_text']; ?>
</div>
</div>

</body>
</html>