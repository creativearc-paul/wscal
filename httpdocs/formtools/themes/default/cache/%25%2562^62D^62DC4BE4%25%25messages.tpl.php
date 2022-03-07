<?php /* Smarty version 2.6.18, created on 2010-12-01 16:33:50
         compiled from /var/www/vhosts/wscal.edu/httpdocs/system/formtools/themes/default/messages.tpl */ ?>

<?php if ($this->_tpl_vars['g_message']): ?>

  <?php if ($this->_tpl_vars['g_success']): ?>
    <?php $this->assign('class', 'notify'); ?>

    <script type="text/javascript">
    // add the nice fade effect for the notification message
    <?php echo 'Event.observe(document, \'dom:loaded\', function() { Fat.fade_element("ft_message_inner", 60, 1000, "#" + g.notify_colours[0], "#" + g.notify_colours[1]); });'; ?>

    </script>

  <?php else: ?>
    <?php $this->assign('class', 'error'); ?>

    <script type="text/javascript">
    // add the nice fade effect for the notification message
    <?php echo 'Event.observe(document, \'dom:loaded\', function() { Fat.fade_element("ft_message_inner", 60, 1000, "#" + g.error_colours[0], "#" + g.error_colours[1]); });'; ?>

    </script>

  <?php endif; ?>

  <div id="ft_message">
    <div style="height: 8px;"> </div>

    <div class="<?php echo $this->_tpl_vars['class']; ?>
" id="ft_message_inner">
      <div style="padding:8px">
        <span style="float: right; padding-left: 5px;"><a href="#" onclick="return ft.hide_message('ft_message')">X</a></span>
        <?php echo $this->_tpl_vars['g_message']; ?>

      </div>
    </div>
  </div>

<?php else: ?>

  <div id="ft_message" style="width: 100%; display:none">
    <div style="height: 8px;"> </div>
    <div class="<?php echo $this->_tpl_vars['class']; ?>
" id="ft_message_inner"></div>
  </div>

<?php endif; ?>

<div style="height: 10px;"> </div>