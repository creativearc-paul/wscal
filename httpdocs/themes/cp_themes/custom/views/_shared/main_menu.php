<div id="mainMenu" class="cf">
	<ul id="navigationTabs" class="cf">
		<li class="home"><a href="<?=BASE?>" title="<?=lang('main_menu')?>" class="first_level">&nbsp;</a></li>

		<?php echo $menu_string; ?>

		<li><a class="addTab first_level" id="addQuickTab" href="<?=generate_quicktab($cp_page_title)?>" title="<?=lang('nav_add_tab')?>"><?=lang('nav_add_tab')?></a></li>
	</ul>
	<div class="clear"></div>
</div>
<div id="mainWrapper">