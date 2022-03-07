<?php
	$vars['lib'] = $lib;
	$vars['mode'] = 'full';
	if($ee_version >= 3)
		$this->load->view('filemanager/ee3_mcp_filemanager', $vars);
	else
		$this->load->view('filemanager/filemanager', $vars);
?>
