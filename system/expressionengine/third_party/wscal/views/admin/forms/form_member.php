<?php
$hidden = array();
$hidden['pk'] = (isset($pk)) ? $pk : '';
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
        $(document).on('click', '.addDays', function(e){
            e.preventDefault();
            $("#expiration_date").datepicker('setDate', 'today +' + $(this).data('days') + 'd');
        });
    });
</script>
<table cellpadding="8" cellspacing="0">
    <tr>
        <td class="formFieldTitle"><span class="required">*</span> Member Expiration Date</td>
        <td class="formFieldInput">
            <?= form_input('expiration_date', set_value('expiration_date', (isset($Record->expiration_date)) ? $Record->expiration_date : ''), 'id="expiration_date" style="width:150px;" class="datePicker" required'); ?>
            <?= form_error('expiration_date'); ?>&nbsp;&nbsp;&nbsp;
            <button class="addDays" data-days="3">Today + 3 days</button> <button class="addDays" data-days="7">Today + 7 days</button> <button class="addDays" data-days="21">Today + 21 days</button>
        </td>
    </tr>
    <tr>
        <td class="formFieldTitle">Member Permissions</td>
        <td class="formFieldInput">
            <?= form_dropdown('permission_entry_id', $SelectOptions->member_permissions_entry_id, set_value('permission_entry_id', (isset($Record->permission_entry_id)) ? $Record->permission_entry_id : ''), ' id="permission_entry_id" '); ?>
            <?= form_error('permission_entry_id'); ?>
        </td>
    </tr>
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