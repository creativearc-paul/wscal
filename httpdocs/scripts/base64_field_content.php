<?php

// create db connection
$hostname = 'localhost';
$username = 'wscal';
$password = 'wsAA23al.db';
$database = 'wscal';

mysql_connect($hostname, $username, $password);
mysql_select_db($database);

// field id
$field_id = 48;

$sql = "SELECT entry_id, field_id_" . $field_id . " FROM exp_channel_data WHERE channel_id = 5";

$query = mysql_query($sql);

while($result = mysql_fetch_object($query)){
    echo $result->entry_id . ' - ' . $result->{'field_id_' . $field_id} . '<br>';
}

exit;
?>