<div id="listingButtons">
    <?php
        foreach($buttons as $button){
            echo '<a href="' . $button['url'] . '" class="cssButton listingButton">' . $button['label']  . '</a>';
        }
    ?>
</div>