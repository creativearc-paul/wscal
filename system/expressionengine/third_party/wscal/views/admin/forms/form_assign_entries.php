<?php
$hidden = array();
$hidden['member_id'] = (isset($member_id)) ? $member_id : '';
$hidden['return_uri'] = (isset($return_uri)) ? $return_uri : '';
echo form_open($form_action_url, array('id' => 'addEditForm'), $hidden);
?>
<a href="<?=base64_decode($return_uri)?>" class="cssButton icon-cancel-circle" style="margin-bottom: 15px;">Back / Cancel</a>
<?php
if(validation_errors()){
    ?>
    <div class="validationErrors">
        <div><span>!</span> Form contains errors.</div>
        <div>Please review the form, correct any errors, and resubmit.</div>
    </div>
    <?php
}
?>
<script type="text/javascript">
    $(document).ready(function() {

        $('#available').sortable({      
            'connectWith'       : '.connectedSortable',
            'tolerance'         : "pointer",
            'revert'            : true,
            'containment'       : $('.related'),
            'scroll'            : true,
            'placeholder'       : "sortableHighlight",
            'forcePlaceholderSize' : true,
            'receive' : function(ev, ui){
                                        $(ui.item).find('input:checkbox').prop('checked', false);
                                        }
        }).disableSelection();

        $('#assigned').sortable({    
            'connectWith'       : '.connectedSortable',
            'tolerance'         : "pointer",
            'revert'            : true,
            'containment'       : $('.related'),
            'scroll'            : true,
            'placeholder'       : "sortableHighlight",
            'forcePlaceholderSize' : true,
            'receive' : function(ev, ui){
                                        $(ui.item).children(':checkbox').prop('checked', true);
                                        }
        }).disableSelection();
        
/*        $('#assignedFields').on('click', 'a', function() {
            var parentLi = $(this).parent();
            $(this).remove();
            parentLi.find('input:checkbox').prop('checked', false);
            parentLi.appendTo($('#availableFields'));
        });*/
        
        $('#assigned').on('click', 'li', function() {
            /*$(this).children(':checkbox').prop('checked', false);*/
            $(this).appendTo($('#available'));
        });
        $('#available').on('click', 'li', function() {
            /*$(this).children(':checkbox').prop('checked', true);*/
            $(this).appendTo($('#assigned'));
        });
        
        // init incase user hits back button
        $("#available").find('input:checkbox').prop('checked', false);
        $("#assigned").find('input:checkbox').prop('checked', true);
    });
</script>
<div class="cf related" style="position: relative;padding:15px 0 15px 0;">
    <div style="float: left;width: 49%;">
        <strong>Entries - click to add</strong>
        <ul id="available" class="connectedSortable cf">
            <?php 
            foreach($available as $entry){
                ?>
                <li data-id="<?= $entry['entry_id']; ?>" style="background-image: url('<?= $entry['thumb']; ?>');">

                    <?=form_checkbox('entry_ids[]', $entry['entry_id'], FALSE, 'id="cb_'.$entry['entry_id'].'" style="visibility:hidden;"')?>
                    <?=$entry['title'];?>

                </li>
                <?php 
            }
            ?>
        </ul>
    </div>
    <div style="float: right;width: 49%;">
        <strong>Assigned Entries - click to remove</strong>
        <ul id="assigned" class="connectedSortable cf">
            <?php 
            foreach($assigned as $entry){
                ?>
                <li data-id="<?= $entry['entry_id']; ?>" style="background-image: url('<?= $entry['thumb']; ?>');">
  
                    <?=form_checkbox('entry_ids[]', $entry['entry_id'], TRUE, 'id="cb_'.$entry['entry_id'].'" style="visibility:hidden;"')?>
                    <?=$entry['title'];?>
              
                </li>
                <?php 
            }
            ?>
        </ul>
    </div>
</div>
<table cellpadding="8" cellspacing="0">
    <tr>
        <td class="formFieldTitle">&nbsp;</td>
        <td class="formFieldInput">
           <?php
                echo form_submit('submit', 'Save', 'class="cssButton" style="margin:10px;"');
            ?>
        </td>
    </tr>
</table>
<?php
echo form_close();