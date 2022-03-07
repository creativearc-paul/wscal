<?php extend_template('default') ?>

<?php if ($notice === TRUE): ?>
	<p align="center">
		<strong>
			<?=sprintf(lang('no_unlocked_member_groups'), 'mailto:'.$sys_admin_email)?>
		</strong>
	</p>
<?php else: ?>

<?=form_open('C=members'.AMP.'M=new_member_form')?>
<?php
$this->table->set_template($cp_pad_table_template);
$this->table->set_heading(
	array('data' => '&nbsp;', 'style' => 'max-width:50%;'),
	'&nbsp;'
);

// Username
$this->table->add_row(array(
		form_label(required().lang('username'), 'username').NBS.form_error('username'),
		form_input(array(
			'id'	=> 'username',
			'name'	=> 'username',
			'class' => 'field',
			'maxlength' => 50,
			'value' => set_value('username')
			)
		)
	)
);

// Password
$this->table->add_row(array(
		form_label(required().lang('password'), 'password').NBS.form_error('password'),
		form_password(array(
			'id'	=> 'password',
			'name'	=> 'password',
			'class' => 'field',
			'maxlength' => 40,
			'value' => set_value('password'),
			'auto_complete' => 'off'
			)
		)
	)
);

// Password Confirm
$this->table->add_row(array(
		form_label(required().lang('password_confirm'), 'password_confirm').NBS.form_error('password_confirm'),
		form_password(array(
			'id'	=> 'password_confirm',
			'name'	=> 'password_confirm',
			'class' => 'field',
			'maxlength' => 40,
			'value' => set_value('password_confirm'),
			'auto_complete' => 'off'
			)
		)
	)
);

// Screen Name
$this->table->add_row(array(
		form_label(lang('screen_name'), 'screen_name').NBS.form_error('screen_name'),
		form_input(array(
			'id'	=> 'screen_name',
			'name'	=> 'screen_name',
			'class' => 'field',
			'maxlength' => 50,
			'value' => set_value('screen_name')
			)
		)
	)
);

// Email
$this->table->add_row(array(
		form_label(required().lang('email'), 'email').NBS.form_error('email'),
		form_input(array(
			'id'	=> 'email',
			'name'	=> 'email',
			'class' => 'field',
			'maxlength' => 72,
			'value' => set_value('email')
			)
		)
	)
);

// Member Group Assignment
if ($this->cp->allowed_group('can_admin_mbr_groups'))
{
	$this->table->add_row(array(
			form_label(required().lang('member_group_assignment'), 'group_id').NBS.form_error('group_id'),
			form_dropdown('group_id', $member_groups, set_value('group_id', 5), 'id="group_id"')
		)
	);
}

// Custom Fields

foreach($custom_profile_fields as $row)
{
	$required  = ($row['m_field_required'] == 'n') ? '' : required();
	
	if ($row['m_field_type'] == 'textarea') // Textarea fieled types
	{
		$rows = ( ! isset($row['m_field_ta_rows'])) ? '10' : $row['m_field_ta_rows'];

		$this->table->add_row(array(
			form_label($required.$row['m_field_label'], 'm_field_id_'.$row['m_field_id']).
			NBS.form_error('m_field_id_'.$row['m_field_id']).BR.$row['m_field_description'], 
			form_textarea(array(
				'name'	=>	'm_field_id_'.$row['m_field_id'],
				'class'	=>	'field',
				'id'	=>	'm_field_id_'.$row['m_field_id'], 
				'rows'	=>	$rows, 
				'style'=>'width:99%;', 
				'value'	=> 	set_value('m_field_id_'.$row['m_field_id']))
				)
			)
		);
	}
	elseif ($row['m_field_type'] == 'select') // Drop-down lists
	{
		$dropdown_options = array();
		foreach (explode("\n", trim($row['m_field_list_items'])) as $v)
		{
			$v = trim($v);
			$dropdown_options[$v] = $v;
		}

		$this->table->add_row(array(
			form_label($required.$row['m_field_label'], 'm_field_id_'.$row['m_field_id']).
			NBS.form_error('m_field_id_'.$row['m_field_id']).BR.$row['m_field_description'],
			form_dropdown('m_field_id_'.$row['m_field_id'], $dropdown_options, set_value('m_field_id_'.$row['m_field_id']), 'id="m_field_id_'.$row['m_field_id'].'"')));
	}
	elseif ($row['m_field_type'] == 'text') // Text input fields
	{
		$this->table->add_row(array(
			form_label($required.$row['m_field_label'], 'm_field_id_'.$row['m_field_id']).
			NBS.form_error('m_field_id_'.$row['m_field_id']).BR.$row['m_field_description'],
			form_input(array(
				'name'		=>	'm_field_id_'.$row['m_field_id'], 
				'id'		=>	'm_field_id_'.$row['m_field_id'], 
				'class'		=>	'field', 
				'value'		=>	set_value('m_field_id_'.$row['m_field_id']), 
				'maxlength'	=>	$row['m_field_maxl'])))
				);
	}

}

echo $this->table->generate();
?>
	<p><?=form_submit('members', lang('register_member'), 'class="submit"')?></p>

	<?=form_close()?>
	
<?php endif ?>
