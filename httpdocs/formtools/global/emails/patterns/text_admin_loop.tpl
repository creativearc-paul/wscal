{$LANG.text_email_template_text_1_c}

{literal}{foreach from=$fields item=field}
{if $field.col_name != "submission_date"}
  {if $field.field_type == "file"}{$field.field_title}: {$field.folder_url}/{$field.answer}
{else}{$field.field_title}: {$field.answer}
{/if}
{/if}
{/foreach}{/literal}

{$LANG.phrase_submission_made}