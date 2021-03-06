{include file='header.tpl'}

  <div style="float:right">
    <a href="{$same_page}?refresh_module_list"></a>
  </div>

  <table cellpadding="0" cellspacing="0" height="35">
  <tr>
    <td width="45"><img src="{$images_url}/icon_modules.gif" width="34" height="34" /></td>
    <td class="title">{$LANG.word_modules|upper}</td>
  </tr>
  </table>

  {include file='messages.tpl'}

  <div id="search_form" class=" margin_bottom_large">

    <form action="{$same_page}" method="post">

      <table cellspacing="2" cellpadding="0" id="search_form_table">
      <tr>
        <td class="blue" width="70">{$LANG.word_search}</td>
        <td>
          <input type="text" size="20" name="keyword" value="{$search_criteria.keyword|escape}" />
          <input type="submit" name="search_modules" value="{$LANG.word_search}" />
          <input type="button" name="reset" value="{$LANG.phrase_show_all}" onclick="window.location='{$same_page}?reset=1'"
            {if $modules|@count < $num_modules}
              class="bold"
            {else}
              class="light_grey" disabled
            {/if} />
        </td>
      </tr>
      </table>

    </form>

  </div>

  {if $modules|@count == 0}

    <div class="notify yellow_bg">
      <div style="padding: 8px">
        {$LANG.text_no_modules_found}
      </div>
    </div>

    <p>
      <input type="button" onclick="window.location='{$same_page}?refresh_module_list'" class="blue" value="{$LANG.phrase_refresh_module_list|escape|upper}" />
    </p>

  {else}

    {$pagination}

    <form action="{$same_page}" method="post">

      {assign var="table_group_id" value="1"}

      {* this displays ALL clients on the page, but groups them in separate tables - only one shown
         at a time. The page nav above hides/shows the appropriate page with JS. Sorry the Smarty logic
         is so dense... *}
      {foreach from=$modules item=module name=row}
        {assign var='index' value=$smarty.foreach.row.index}
        {assign var='count' value=$smarty.foreach.row.iteration}
        {assign var='module_id' value=$modules[$index].module_id}
        {assign var='module' value=$modules[$index]}

        {* if it's the first row or the start of a new table, open the table & display the headings *}
        {if $count == 1 || $count != 1 && (($count-1) % $settings.num_modules_per_page == 0)}

          {if $table_group_id == "1"}
            {assign var="style" value="display: block"}
          {else}
            {assign var="style" value="display: none"}
          {/if}

          <div id="page_{$table_group_id}" style="{$style}">

          <table class="list_table" cellspacing="1" cellpadding="0">
          <tr>
            <th>

              {assign var="up_down" value=""}
              {if     $order == "module_name-DESC"}
                {assign var=sort_order value="order=module_name-ASC"}
                {assign var=up_down value="<img src=\"`$theme_url`/images/sort_down.gif\" />"}
              {elseif $order == "module_name-ASC"}
                {assign var=sort_order value="order=module_name-DESC"}
                {assign var=up_down value="<img src=\"`$theme_url`/images/sort_up.gif\" />"}
              {else}
                {assign var=sort_order value="order=module_name-DESC"}
              {/if}

              <table cellspacing="0" cellpadding="0" align="center" class="pad_left_small">
              <tr>
                <td><a href="{$same_page}?{$sort_order}">{$LANG.word_module}</a></td>
                <td class="pad_left">{$up_down}</td>
              </tr>
              </table>

            </th>
            <th class="pad_left pad_right">{$LANG.word_version}</th>
            <th width="70">

              {assign var="up_down" value=""}
              {if     $order == "is_enabled-DESC"}
                {assign var=sort_order value="order=is_enabled-ASC"}
                {assign var=up_down value="<img src=\"`$theme_url`/images/sort_down.gif\" />"}
              {elseif $order == "is_enabled-ASC"}
                {assign var=sort_order value="order=is_enabled-DESC"}
                {assign var=up_down value="<img src=\"`$theme_url`/images/sort_up.gif\" />"}
              {else}
                {assign var=sort_order value="order=is_enabled-DESC"}
              {/if}

              <table cellspacing="0" cellpadding="0" align="center" class="pad_left_small">
              <tr>
                <td><a href="{$same_page}?{$sort_order}">{$LANG.word_enabled}</a></td>
                <td class="pad_left">{$up_down}</td>
              </tr>
              </table>

            </th>
            <th width="70">{$LANG.word_select|upper}</th>
            <th width="70" class="del">{$LANG.word_uninstall|upper}</th>
          </tr>

        {/if}

        {if $module.is_installed == "no" || $module.needs_upgrading}
           <tr class="selected_row_color">
        {else}
          <tr>
        {/if}

          <td class="pad_left_small pad_right_large" valign="top">
            <div><span class="bold pad_right_large">{$module.module_name}</span> [<a href="about.php?module_id={$module.module_id}">{$LANG.word_about|upper}</a>]</div>
            <div class="medium_grey">{$module.description}</div>
          </td>
          <td valign="top" align="center">{$module.version}</td>
          <td valign="top" align="center">
            {if $module.is_installed == "no"}
              <a href="{$same_page}?install={$module.module_id}">{$LANG.word_install|upper}</a>
            {else}
              <input type="checkbox" name="is_enabled[]" value="{$module.module_id}" {if $module.is_enabled == 'yes'}checked{/if} />
            {/if}
          </td>
          <td valign="top" align="center">
            {if $module.is_enabled == "yes"}
						  {if $module.needs_upgrading}
                <a href="{$same_page}?upgrade={$module_id}">{$LANG.word_upgrade|upper}</a>
							{else}
                <a href="{$g_root_url}/modules/{$module.module_folder}/">{$LANG.word_select|upper}</a>
							{/if}
            {/if}
          </td>
          <td valign="top" class="del" align="center">
            {if $module.module_type != "core"}
              <a href="{$same_page}?uninstall={$module.module_id}" onclick="return page.uninstall_module({$module.custom_delete_file})">{$LANG.word_uninstall|upper}</a>
            {/if}
          </td>
        </tr>

        {if $count != 1 && ($count % $settings.num_modules_per_page) == 0}
          </table></div>
          {assign var='table_group_id' value=$table_group_id+1}
        {/if}

      {/foreach}

      {* if the table wasn't closed, close it! *}
      {if ($modules|@count % $settings.num_modules_per_page) != 0}
        </table></div>
      {/if}

      <p>
        <input type="submit" name="enable_modules" value="{$LANG.word_update|upper}" />
        <input type="button" onclick="window.location='{$same_page}?refresh_module_list'" class="blue" value="{$LANG.phrase_refresh_module_list|escape|upper}" />
      </p>

    </form>

  {/if}

{include file='footer.tpl'}