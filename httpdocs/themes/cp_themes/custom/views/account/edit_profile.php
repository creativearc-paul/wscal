<?php extend_view('account/_wrapper') ?>

<div>
	<h3><?=lang('profile_form')?></h3>

	<?=form_open('C=myaccount'.AMP.'M=update_profile', '', $form_hidden)?>

	<?php foreach($custom_profile_fields as $field):?>
	<p>
		<?=$field?>
	</p>
	<?php endforeach;?>

	<p class="submit"><?=form_submit('edit_profile', lang('update'), 'class="submit"')?></p>

	<?=form_close()?>
</div>