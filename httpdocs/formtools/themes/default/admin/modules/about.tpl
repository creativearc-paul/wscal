{include file='header.tpl'}

  <div class="title margin_bottom_large">{$LANG.phrase_module_info|upper}</div>

  <table cellspacing="1" cellpadding="1" class="list_table">
  <tr>
    <td width="140" class="pad_left_small">{$LANG.word_module}</td>
    <td class="pad_left_small bold">{$module_info.module_name}</td>
  </tr>
  <tr>
    <td class="pad_left_small">{$LANG.phrase_module_description}</td>
    <td class="pad_left_small">{$module_info.description}</td>
  </tr>
  <tr>
    <td class="pad_left_small">{$LANG.word_version}</td>
    <td class="pad_left_small">{$module_info.version}</td>
  </tr>
  <tr>
    <td class="pad_left_small">{$LANG.word_author}</td>
    <td class="pad_left_small">{$module_info.author}
      {if $module_info.author_email != ''}
        &#8212; <a href="mailto:{$module_info.author_email}">{$module_info.author_email}</a>
      {/if}
    </td>
  </tr>
  {if $module_info.author_link != ''}
    <tr>
      <td class="pad_left_small">{$LANG.phrase_author_link}</td>
      <td class="pad_left_small"><a href="{$module_info.author_link}" target="_blank">{$module_info.author_link}</a></td>
    </tr>
  {/if}
  </table>

  <p>
    <a href="index.php">{$LANG.word_back_leftarrow}</a>
  </p>

{include file='footer.tpl'}