<script type="text/javascript">
    $(document).ready(function() {

        var ajaxIndicator = $('.ajaxIndicator');

        $('.mainTable').table('add_filter', $('#filterForm')).bind('tableload', function() {
                                                                ajaxIndicator.css('visibility', '');
                                                            })
                                                            .bind('tableupdate', function() {
                                                                ajaxIndicator.css('visibility', 'hidden');
                                                            });
        
        $("#filterForm").on("click", "#clearFilters", function() {
            $("#filterForm").get(0).reset();
            $("#filterForm").submit();
        });
        
        <?php
        if(isset($Date_filter)){
            ?>          
            $('#datePickerStartDate').datepicker({
                maxDate: '+2Y',
                dateFormat: 'm/d/yy',
                changeMonth: true,
                changeYear: true,
                onClose: function(selectedDate) {
                    $('#datePickerEndDate').datepicker( 'option', 'minDate', selectedDate );
                },
                showOn: 'both',
                buttonImage: '<?=$theme_url?>/images/calendar.png',
                buttonImageOnly: true,
                showAnim: false,
                showOtherMonths: true,
                selectOtherMonths: true
            });

            $('#datePickerEndDate').datepicker({
                maxDate: '+2Y',
                dateFormat: 'm/d/yy',
                changeMonth: true,
                changeYear: true,
                onClose: function(selectedDate) {
                    $('#datePickerStartDate').datepicker( 'option', 'maxDate', selectedDate );
                },
                showOn: 'both',
                buttonImage: '<?=$theme_url?>/images/calendar.png',
                buttonImageOnly: true,
                showAnim: false,
                showOtherMonths: true,
                selectOtherMonths: true
            });
            <?php
        }
        ?>
    });
</script>
<div id="filterMenu">
    <?=form_open($filter_form_action, array('id'=>'filterForm'))?>
    <fieldset class="clearfix" style="padding: 5px;">
        <table class="filterForm">
            <tr>
                <td style="white-space: nowrap;padding-right: 20px;">
                    <?php
                    if(isset($select_filters)){
                        ?>
                        <div>
                            <?php
                            foreach($select_filters as $Select){
                                ?>
                                <strong><?=$Select->label?>:&nbsp;&nbsp;</strong>
                                <?=form_dropdown($Select->column, $Select->options, set_value($Select->column, ''), ' id="' .$Select->column . '_filter"') ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    if(isset($Date_filter)){
                        ?>
                        <div>
                            <strong>Date Range:&nbsp;&nbsp;</strong>
                            <?=form_dropdown('date_filter', $Date_filter->options, set_value('date_filter'), ' id="date_filter"') ?>&nbsp;&nbsp;
                            From: <?=form_input('start_date', set_value('start_date', ''), 'id="datePickerStartDate" placeholder="m/d/yyyy" class="datePickerStartDate" style="width:100px;"');?>&nbsp;&nbsp;&nbsp;
                            To: <?=form_input('end_date', set_value('end_date', ''), 'id="datePickerEndDate" placeholder="m/d/yyyy" class="datePickerEndDate" style="width:100px;"');?>&nbsp;&nbsp;&nbsp;
                        </div>
                        <?php
                    }
                    if(isset($Keyword_filter)){
                        ?>
                        <div>
                            <strong>Filter Results:&nbsp;&nbsp;</strong>
                            <?=form_dropdown('keyword_filter', $Keyword_filter->options, set_value('keyword_filter',''), ' id="keyword_filter"') ?>&nbsp;&nbsp;
                            <?=form_input('keywords', set_value('keywords', ''), 'id="keywords" placeholder="keyword" class="keywords" style="width:150px;"');?>&nbsp;&nbsp;&nbsp;
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="62px" height="10px" viewBox="0 0 62 10" style="margin-left:30px;visibility:hidden;enable-background:new 0 0 50 50;" xml:space="preserve" class="ajaxIndicator">
                                <rect x="0" y="0" width="10" height="10" fill="#333">
                                    <animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0s" dur="1s" repeatCount="indefinite"></animate>
                                </rect>
                                <rect x="13" y="0" width="10" height="10" fill="#333">
                                    <animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0.2s" dur="1s" repeatCount="indefinite"></animate>
                                </rect>
                                <rect x="26" y="0" width="10" height="10" fill="#333">
                                    <animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0.4s" dur="1s" repeatCount="indefinite"></animate>
                                </rect>
                                <rect x="39" y="0" width="10" height="10" fill="#333">
                                    <animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0.6s" dur="1s" repeatCount="indefinite"></animate>
                                </rect>
                                <rect x="52" y="0" width="10" height="10" fill="#333">
                                    <animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0.8s" dur="1s" repeatCount="indefinite"></animate>
                                </rect>
                            </svg>
                        </div>
                        <?php
                    }
                    ?>
                </td>
                <td style="width: 100%;vertical-align: middle;padding-left: 20px;border-left: 1px solid #dadada;">
                    <input type="button" id="clearFilters" value="Clear Filters" class="cssButton">
                </td>
            </tr>
        </table>
    </fieldset>
    <?= form_close(); ?>
</div>