<script type="text/javascript">
    $.ee_filebrowser.add_trigger(".choose_file", function (file) {

       // This is basically a callback method which is called after
       // you select a file in the File Manager modal window. The parameter
       // 'file' is an object representing the file you selected, which
       // has various properties pre-populated such as file_id, file_name etc.
       // Use Firebug in Firefox or similar to see all values.
       // Here you can then manually populate the hidden fields provided
       // by the File Field Class.
       console.log(file); 
        
    });
</script>
<?php
$hidden = array();
$hidden['pk'] = (isset($pk)) ? $pk : '';
$hidden['return_uri'] = (isset($return_uri)) ? $return_uri : '';
$hidden['channel_id'] = (isset($RecordView->channel_id)) ? $RecordView->channel_id : '';
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
<?= form_error('channel_id'); ?>
<fieldset class="neoseoFieldset">
    <legend>Channel Management</legend>
    <table cellpadding="8" cellspacing="0">  
        <tr>
            <td class="formFieldTitle" style="width:200px;">Manage meta tags<br>for this channel</td>
            <td class="formFieldInput">
                <?= form_dropdown('manage_channel', $SelectOptions->no_yes_char, set_value('manage_channel', $Record->manage_channel, 'id="manage_channel" ')); ?><br>
                <?= form_error('manage_channel'); ?>
            </td>
            <td>
                <strong>WARNING!</strong> &ndash; If this is changed to No and the channel was previously managed, all meta data for entries in the channel will be lost. 
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle" style="width:200px;">Select title field<br>for this channel</td>
            <td class="formFieldInput">
                <?= form_dropdown('default_title_field', $SelectOptions->channel_fields, set_value('default_title_field', (isset($Record->default_title_field)) ? $Record->default_title_field : 0), 'id="default_title_field" '); ?><br>
                <?= form_error('default_title_field'); ?>
            </td>
            <td>
                When creating or editing an entry that does not have a title tag value set, populate with this field. An example use would be a news article where the article "title" is different than the EE title.
            </td>
        </tr>
    </table>
</fieldset>
<fieldset class="neoseoFieldset">
    <legend>General Items - Channel Defaults</legend>
    <table cellpadding="8" cellspacing="0">  
        <tr>
            <td class="formFieldTitle" style="width:200px;">Title Tag</td>
            <td class="formFieldInput" style="width: 465px;">
                <?= form_input('title_tag', set_value('title_tag', (isset($Record->title_tag)) ? $Record->title_tag : ''), 'id="title_tag" style="width:450px;"'); ?> 
                <div class="remaining">Characters remaining: <span class="count">70</span></div>
                <?= form_error('title_tag'); ?>
            </td>
            <td>
                Shown in the browser title bar, user bookmarks, and search engine results.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Meta Description</td>
            <td class="formFieldInput">
                <?= form_textarea('meta_description', set_value('meta_description', (isset($Record->meta_description)) ? $Record->meta_description : ''), 'id="meta_description" style="width:450px;height:70px;"'); ?> 
                <div class="remaining">Characters remaining: <span class="count">160</span></div>
                <?= form_error('meta_description'); ?>
            </td>
            <td>
                A brief description of the page that may be presented in search results.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Meta Keywords</td>
            <td class="formFieldInput">
                <?= form_input('meta_keywords', set_value('meta_keywords', (isset($Record->meta_keywords)) ? $Record->meta_keywords : ''), 'id="meta_keywords" style="width:450px;"'); ?>   
                <div class="remaining">Characters remaining: <span class="count">150</span></div>
                <?= form_error('meta_keywords'); ?>
            </td>
            <td>
                Legacy tag. Google will ignore.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Robots</td>
            <td class="formFieldInput">
                <?= form_dropdown('meta_robots_ovid', $SelectOptions->meta_robots_ovid, set_value('meta_robots_ovid', $Record->meta_robots_ovid), 'id="meta_robots_ovid" '); ?>
                <?= form_error('meta_robots_ovid'); ?>
            </td>
            <td>
                Meta tag that tells robots to index the page content, and/or scan for links to follow.
            </td>
        </tr>
    </table>
</fieldset>
<fieldset class="neoseoFieldset">
    <legend>Open Graph Tags (used by Facebook, Pinterest, Google+) - Channel Defaults</legend>
    <table cellpadding="8" cellspacing="0"> 
        <tr>
            <td class="formFieldTitle">Open Graph Type</td>
            <td class="formFieldInput">
                <?= form_dropdown('open_graph_type_ovid', $SelectOptions->open_graph_type_ovid, set_value('open_graph_type_ovid', $Record->open_graph_type_ovid), 'id="open_graph_type_ovid" '); ?>
                <?= form_error('open_graph_type_ovid'); ?>
            </td>
            <td>
                Type of content. Article is the preferred type for blog posts and news stories.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle" style="width:200px;">Open Graph URL</td>
            <td class="formFieldInput" style="width: 465px;">
                <?= form_input('open_graph_url', set_value('open_graph_url', (isset($Record->open_graph_url)) ? $Record->open_graph_url : ''), 'id="open_graph_url" style="width:450px;"'); ?> 
                <?= form_error('open_graph_url'); ?>
            </td>
            <td>
                Leave blank to default to current page URL. Will usually only be used if the current URL is not the canonical version of the page.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Article Author</td>
            <td class="formFieldInput">
                <?= form_input('open_graph_article_author', set_value('open_graph_article_author', (isset($Record->open_graph_article_author)) ? $Record->open_graph_article_author : ''), 'id="open_graph_article_author" style="width:450px;"'); ?> 
                <?= form_error('open_graph_article_author'); ?>
            </td>
            <td>
                URL for the Facebook profile or Facebook page of the article author (e.g. https://www.facebook.com/my_page).
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Open Graph Site Name</td>
            <td class="formFieldInput">
                <?= form_input('open_graph_site_name', set_value('open_graph_site_name', (isset($Record->open_graph_site_name)) ? $Record->open_graph_site_name : ''), 'id="open_graph_site_name" style="width:450px;"'); ?> 
                <?= form_error('open_graph_site_name'); ?>
            </td>
            <td>
                The name of your website (not the URL).
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Open Graph Title</td>
            <td class="formFieldInput">
                <?= form_input('open_graph_title', set_value('open_graph_title', (isset($Record->open_graph_title)) ? $Record->open_graph_title : ''), 'id="open_graph_title" style="width:450px;"'); ?> 
                <div class="remaining">Characters remaining: <span class="count">70</span></div>
                <?= form_error('open_graph_title'); ?>
            </td>
            <td>
                Title of page/article as shown in Facebook (exclude any branding).
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Open Graph Image</td>
            <td class="formFieldInput fileField">
                <?= ee()->file_field->browser(); ?>
                <?= ee()->file_field->field('open_graph_image', set_value('open_graph_image', (isset($Record->open_graph_image)) ? $Record->open_graph_image : ''), 'all', 'image'); ?> 
            </td>
            <td>
                Image shown in Facebook. Facebook suggests that you use an image of at least 1200x630 pixels.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Open Graph Description</td>
            <td class="formFieldInput">
                <?= form_textarea('open_graph_description', set_value('open_graph_description', (isset($Record->open_graph_description)) ? $Record->open_graph_description : ''), 'id="open_graph_description" style="width:450px;height:70px;"'); ?> 
                <?= form_error('open_graph_description'); ?>
            </td>
            <td>
                Two or three sentences describing content.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Facebook App ID</td>
            <td class="formFieldInput">
                <?= form_input('facebook_app_id', set_value('facebook_app_id', (isset($Record->facebook_app_id)) ? $Record->facebook_app_id : ''), 'id="facebook_app_id" style="width:450px;"'); ?> 
                <?= form_error('facebook_app_id'); ?>
            </td>
            <td>
                Used for Facebook Insights. Unique ID that identifies your domain to Facebook.
            </td>
        </tr>
    </table>
</fieldset>
<fieldset class="neoseoFieldset">
    <legend>Twitter Card - Channel Defaults</legend>
    <table cellpadding="8" cellspacing="0"> 
        <tr>
            <td class="formFieldTitle" style="width:200px;">Twitter Card Type</td>
            <td class="formFieldInput" style="width: 465px;">
                <?= form_dropdown('twitter_card_type_ovid', $SelectOptions->twitter_card_type_ovid, set_value('twitter_card_type_ovid', $Record->twitter_card_type_ovid), 'id="twitter_card_type_ovid" '); ?>
                <?= form_error('twitter_card_type_ovid'); ?>
            </td>
            <td>
                Twitter Card Type.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle" style="width:200px;">Twitter Card URL</td>
            <td class="formFieldInput">
                <?= form_input('twitter_card_url', set_value('twitter_card_url', (isset($Record->twitter_card_url)) ? $Record->twitter_card_url : ''), 'id="twitter_card_url" style="width:450px;"'); ?> 
                <?= form_error('twitter_card_url'); ?>
            </td>
            <td>
                Leave blank to default to current page URL. Will usually only be used if the current URL is not the canonical version of the page.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Twitter @username</td>
            <td class="formFieldInput">
                <?= form_input('twitter_card_site', set_value('twitter_card_site', (isset($Record->twitter_card_site)) ? $Record->twitter_card_site : ''), 'id="twitter_card_site" style="width:450px;"'); ?> 
                <?= form_error('twitter_card_site'); ?>
            </td>
            <td>
                The Twitter @username the card should be attributed to. Required for Twitter Card analytics.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Twitter Card Title</td>
            <td class="formFieldInput">
                <?= form_input('twitter_card_title', set_value('twitter_card_title', (isset($Record->twitter_card_title)) ? $Record->twitter_card_title : ''), 'id="twitter_card_title" style="width:450px;"'); ?> 
                <div class="remaining">Characters remaining: <span class="count">70</span></div>
                <?= form_error('twitter_card_title'); ?>
            </td>
            <td>
                Title of item for Twitter Card.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Twitter Card Image</td>
            <td class="formFieldInput fileField">
                <?= ee()->file_field->browser(); ?>
                <?= ee()->file_field->field('twitter_card_image', set_value('twitter_card_image', (isset($Record->twitter_card_image)) ? $Record->twitter_card_image : ''), 'all', 'image'); ?><br>
            </td>
            <td>
                Unique image representing the content of the page. Do not use a generic image such as your website logo, author photo, or other image that spans multiple pages.<br>The image must be a minimum size of 120px by 120px and must be less than 1MB in file size.
            </td>
        </tr>
        <tr>
            <td class="formFieldTitle">Twitter Card Description</td>
            <td class="formFieldInput">
                <?= form_textarea('twitter_card_description', set_value('twitter_card_description', (isset($Record->twitter_card_description)) ? $Record->twitter_card_description : ''), 'id="twitter_card_description" style="width:450px;height:70px;"'); ?> 
                <div class="remaining">Characters remaining: <span class="count">200</span></div>
                <?= form_error('twitter_card_description'); ?>
            </td>
            <td>
                A description that concisely summarizes the content of the page, as appropriate for presentation within a Tweet.<br>Do not re-use the title text as the description, or use this field to describe the general services provided by the website.
            </td>
        </tr>
    </table>
</fieldset>
<table cellpadding="8" cellspacing="0"> 
    <tr>
        <td class="formFieldTitle" style="width:200px;">&nbsp;</td>
        <td class="formFieldInput">
            <?= form_submit('submit', 'Save', 'class="cssButton" style="margin:20px;"'); ?>
        </td>
    </tr>
</table>
<?php
echo form_close();