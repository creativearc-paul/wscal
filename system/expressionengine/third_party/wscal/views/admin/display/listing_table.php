<?php
if($confirm_delete){
    ?>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#deleteSelected').attr('disabled', $('.deleteCheckBox:checked').length == 0);

            /* disable button until something is checked */
            $('#listingDeleteForm').on('change', '.deleteCheckBox', function(){
                $('#deleteSelected').attr('disabled', $('.deleteCheckBox:checked').length == 0);
            });
           
        });
    </script>
    <?php
    echo form_open($confirm_delete, array('id' => 'listingDeleteForm'));
}
?>
<?= $pagination_html; ?>
<?= $table_html; ?>
<?= $pagination_html; ?>
<?php
if($confirm_delete){
    echo '<div class="tableSubmit" style="text-align:right;">' . form_submit(array('name' => 'delete_selected', 'value' => 'Delete Selected', 'class' => 'submit', 'id' => 'deleteSelected', 'disabled' => 'disabled')) . '</div>';
    echo form_close();
}
