<?php
$hidden = array();
foreach($delete_keys as $id){
    $hidden['delete_keys'][] = $id;
}
$hidden['return_uri'] = (isset($return_uri)) ? $return_uri : '';
echo form_open($form_action_url, array('id' => 'confirmDeleteForm'), $hidden);
?>
<a href="<?=base64_decode($return_uri)?>" class="cssButton icon-cancel-circle" style="margin-bottom: 15px;">Back / Cancel</a>
<h2 class="notice"><?= $warning; ?></h2>
<?php
    foreach($tables as $Table){
        ?>
            <table cellpadding="8" cellspacing="0" id="deleteTable">
                <?php
                    echo (isset($Table->table_caption)) ? '<caption>' . $Table->table_caption . '</caption>' : '';
                ?>
                <thead>
                    <tr>
                        <?php 
                            foreach($Table->table_headers as $header){
                                ?>
                                <th><?= $header; ?></th>
                                <?php
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($Table->table_rows as $row){
                            ?>
                            <tr>
                                <?php
                                foreach($row as $cell){
                                    ?>
                                    <td><?= $cell; ?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        <?php
    }
?>
<?= form_submit('submit', 'Delete', 'class="cssButton" style="margin:20px;"'); ?>
<?php
echo form_close();