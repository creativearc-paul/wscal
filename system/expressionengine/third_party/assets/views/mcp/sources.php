<?php

echo form_open($edit_source_action);
echo '<h3>' . lang('external_sources') . '</h3>';
$this->load->view('mcp/components/source_type_list', array('sources' => $sources));

echo form_submit(array('name' => 'submit', 'value' => lang('add_new_source'), 'class' => 'submit'));
echo form_close();

if (!empty($sources))
{
	echo form_open($delete_source_action, 'id="delete_source"');
	echo '<input id="source_id" type="hidden" name="source_id" value="0" />';
	echo form_close();
}
