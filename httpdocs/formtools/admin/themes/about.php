<?php

require("../../global/session_start.php");
ft_check_permission("admin");
$request = array_merge($_POST, $_GET);
$theme_id = isset($request["theme_id"]) ? $request["theme_id"] : "";

if (empty($theme_id))
{
  header("location: index.php");
  exit;
}
$theme_info = ft_get_theme($theme_id);

// compile header information
$page_vars = array();
$page_vars["page"]       = "themes_about";
$page_vars["page_url"]   = ft_get_page_url("themes_about");
$page_vars["head_title"] = "{$LANG["word_themes"]} - {$LANG["word_about"]}";
$page_vars["theme_info"] = $theme_info;

ft_display_page("admin/themes/about.tpl", $page_vars);