<?php

/**
 * This file defines all general functions used throughout Form Tools.
 *
 * @copyright Encore Web Studios 2010
 * @author Encore Web Studios <formtools@encorewebstudios.com>
 * @package 2-0-4
 * @subpackage General
 */


// -------------------------------------------------------------------------------------------------


/**
 * Open a database connection. This is called once for all page requests, and closed at the footer.
 * Depending on the $g_check_ft_sessions global (true by default), it also logs the time of each
 * request, to perform the sessions timeout check. This parameter is enabled for the main script
 * so that all users are subject to being booted out if there's been no activity. But for external
 * scripts (such as the API) this setting can be disabled, giving them unfettered use of the database
 * connection without worrying about being - incorrectly - logged out.
 *
 * @return resource returns a reference to the open connection.
 */
function ft_db_connect()
{
  global $g_db_hostname, $g_db_username, $g_db_password, $g_db_name, $g_unicode, $g_db_ssl,
    $g_check_ft_sessions;

  if ($g_db_ssl)
    $link = @mysql_connect($g_db_hostname, $g_db_username, $g_db_password, true, MYSQL_CLIENT_SSL)
      or ft_handle_error("Couldn't connect to database: <b>$g_db_hostname</b>", "");
  else
    $link = @mysql_connect($g_db_hostname, $g_db_username, $g_db_password, true)
      or ft_handle_error("Couldn't connect to database: <b>$g_db_hostname</b>", "");

  @mysql_select_db($g_db_name) or
    ft_handle_error("couldn't find database <b>$g_db_name</b>", "");

  // if required, set all queries as UTF-8 (enabled by default)
  if ($g_unicode)
    @mysql_query("SET NAMES 'utf8'", $link);


  if ($g_check_ft_sessions && isset($_SESSION["ft"]["account"]))
  {
    $now = date("U");

    // check to see if the session has timed-out
    if (isset($_SESSION["ft"]["account"]["last_activity_unixtime"]) && isset($_SESSION["ft"]["account"]["sessions_timeout"]))
    {
      $sessions_timeout_mins = $_SESSION["ft"]["account"]["sessions_timeout"];
      $timeout_secs = $sessions_timeout_mins * 60;

      if ($_SESSION["ft"]["account"]["last_activity_unixtime"] + $timeout_secs < $now)
      {
        ft_logout_user("notify_sessions_timeout");
      }
    }

    // log this unixtime for checking the sessions timeout
    $_SESSION["ft"]["account"]["last_activity_unixtime"] = $now;
  }

  return $link;
}


/**
 * Closes a database connection.
 *
 * @param resource Closes the connection included in this parameter.
 */
function ft_db_disconnect($link)
{
  @mysql_close($link);
}


/**
 * A handy, generic function used throughout the site to output messages to the user - the content
 * of which are returned by the various functions. It can handle multiple messages (notifications
 * and/or errors) by passing in arrays for each of the two parameters.
 *
 * Ultimately, one of the goals is to move to complete consistency in the ways the various functions
 * handle their return values. Specifically, an array with the following indexes:<br/>
 *    [0] T/F (or an array of T/F values),<br/>
 *    [1] error/success message string (or an array of strings)<br/>
 *    [2] other information, e.g. new IDs (optional).
 *
 *
 * @param boolean $results This parameter can be EITHER a boolean or an array of booleans if you
 *          need to display multiple messages at once.
 * @param boolean $messages The message to output, or an array of messages. The indexes of each
 *          corresponds to the success/failure boolean in the $results parameter.
 */
function ft_display_message($results, $messages)
{
  global $LANG;

  // if there are no messages, just return
  if (empty($messages))
    return;

  $notifications = array();
  $errors        = array();

  if (is_array($results))
  {
    for ($i=0; $i<=count($results); $i++)
    {
      if     ($results[$i])  $notifications[] = $messages[$i];
      elseif (!$results[$i]) $errors[]        = $messages[$i];
    }
  }
  else
  {
    if     ($results)  $notifications[] = $messages;
    elseif (!$results) $errors[]        = $messages;
  }


  // display notifications
  if (!empty($notifications))
  {
    if (count($notifications) > 1)
    {
      array_walk($notifications, create_function('&$el','$el = "&bull;&nbsp; " . $el;'));
      $display_str = join("<br />", $notifications);
    }
    else
      $display_str = $notifications[0];

    echo "<div class='notify'>$display_str</div>";
  }

  // display errors
  if (!empty($errors))
  {
    // if there were notifications displayed, add a little padding to separate the two sections
    if (!empty($notifications)) { echo "<br />"; }

    if (count($errors) > 1)
    {
      array_walk($errors, create_function('&$el','$el = "&bull;&nbsp; " . $el;'));
      $display_str = join("<br />", $errors);
      $title_str = $LANG["word_errors"];
    }
    else
    {
      $display_str = $errors[0];
      $title_str = $LANG["word_error"];
    }

    echo "<div class='error'><span>$title_str</span><br /><br />$display_str</div><br />";
  }
}


/**
 * This function evaluates any string that contains Smarty logic / variables. It handles
 * parsing the email templates, filename strings and other such functionality. It uses on the
 * eval.tpl template, found in /global/smarty.
 *
 * @param string $placeholder_str the string containing the placeholders / Smarty logic
 * @param array $placeholders a hash of values to pass to the template. The contents of the
 *    current language file is ALWAYS sent.
 * @param string $theme
 * @return string a string containing the output of the eval()'d smarty template
 */
function ft_eval_smarty_string($placeholder_str, $placeholders = array(), $theme = "", $plugin_dirs = array())
{
  global $g_root_dir, $g_default_theme, $LANG;

  if (empty($theme) && isset($_SESSION["ft"]["account"]["theme"]))
    $theme = $_SESSION["ft"]["account"]["theme"];
  else
    $theme = $g_default_theme;

  $smarty = new Smarty();
  $smarty->template_dir = "$g_root_dir/global/smarty/";
  $smarty->compile_dir  = "$g_root_dir/themes/$theme/cache/";

  foreach ($plugin_dirs as $dir)
    $smarty->plugins_dir[] = $dir;

  $smarty->assign("eval_str", $placeholder_str);
  if (!empty($placeholders))
  {
    while (list($key, $value) = each($placeholders))
      $smarty->assign($key, $value);
  }
  $smarty->assign("LANG", $LANG);

  $output = $smarty->fetch("eval.tpl");

  extract(ft_process_hooks("end", compact("output", "placeholder_str", "placeholders", "theme"), array("output")), EXTR_OVERWRITE);

  return $output;
}


/**
 * Handy function to help manage long strings by adding either an ellipsis or inserts a inserts a
 * <br /> at the position specified, and returns the result.
 *
 * @param string $str The string to manipulate.
 * @param string $length The max length of the string / place to insert <br />
 * @param string $flag "ellipsis" / "page_break"
 * @return string The modified string.
 */
function ft_trim_string($str, $length, $flag = "ellipsis")
{
  $new_string = "";
  if (mb_strlen($str) < $length)
    $new_string = $str;
  else
  {
    if ($flag == "ellipsis")
      $new_string = mb_substr($str, 0, $length) . "...";
    else
    {
      $parts = mb_str_split($str, $length);
      $new_string = join("<br />", $parts);
    }
  }

  return $new_string;
}


/**
 * Displays basic &lt;&lt; 1 2 3 >> navigation for lists, each linking to the current page.
 *
 * This uses the pagination.tpl template, found in the theme's root folder.
 *
 * @param integer $num_results The total number of results found.
 * @param integer $num_per_page The max number of results to list per page.
 * @param integer $current_page The current page number being examined (defaults to 1).
 * @param string $pass_along_str The string to include in nav links.
 * @param string $page_str The string used in building the page nav to indicate the page number
 */
function ft_get_page_nav($num_results, $num_per_page, $current_page = 1, $pass_along_str = "", $page_str = "page", $theme = "")
{
  global $g_max_nav_pages, $g_smarty_debug, $g_root_dir, $g_root_url, $LANG, $g_smarty_use_sub_dirs;

  $current_page = ($current_page < 1) ? 1 : $current_page;

  if (empty($theme))
    $theme = $_SESSION["ft"]["account"]["theme"];

  $smarty = new Smarty();
  $smarty->template_dir = "$g_root_dir/themes/$theme";
  $smarty->compile_dir  = "$g_root_dir/themes/$theme/cache/";
  $smarty->use_sub_dirs = $g_smarty_use_sub_dirs;
  $smarty->assign("LANG", $LANG);

  if (isset($_SESSION["ft"]))
    $smarty->assign("SESSION", $_SESSION["ft"]);

  $smarty->assign("g_root_dir", $g_root_dir);
  $smarty->assign("g_root_url", $g_root_url);
  $smarty->assign("samepage", $_SERVER["PHP_SELF"]);
  $smarty->assign("num_results", $num_results);
  $smarty->assign("num_per_page", $num_per_page);
  $smarty->assign("current_page", $current_page);
  $smarty->assign("page_str", $page_str);

  // display the total number of results found
  $range_start = ($current_page - 1) * $num_per_page + 1;
  $range_end   = $range_start + $num_per_page - 1;
  $range_end   = ($range_end > $num_results) ? $num_results : $range_end;

  $smarty->assign("range_start", $range_start);
  $smarty->assign("range_end", $range_end);

  $viewing_range = "";
  if ($num_results > $num_per_page)
  {
    $replacement_info = array(
      "startnum" => "<span id='nav_viewing_num_start'>$range_start</span>",
      "endnum"   => "<span id='nav_viewing_num_end'>$range_end</span>"
        );
    $viewing_range = ft_eval_smarty_string($LANG["phrase_viewing_range"], $replacement_info);
  }
  $total_pages = ceil($num_results / $num_per_page);
  $smarty->assign("viewing_range", $viewing_range);
  $smarty->assign("total_pages", $total_pages);

  // piece together additional query string values
  if (!empty($pass_along_str))
    $smarty->assign("query_str", "&{$pass_along_str}");

  // determine the first and last pages to show page nav links for
  $half_total_nav_pages  = floor($g_max_nav_pages / 2);
  $first_page = ($current_page > $half_total_nav_pages) ? $current_page - $half_total_nav_pages : 1;
  $last_page  = (($current_page + $half_total_nav_pages) < $total_pages) ? $current_page + $half_total_nav_pages : $total_pages;

  $smarty->assign("first_page", $first_page);
  $smarty->assign("last_page", $last_page);

  $smarty->assign("include_first_page_direct_link", (($first_page != 1) ? true : false));
  $smarty->assign("include_last_page_direct_link", (($first_page != $total_pages) ? true : false));


  // now process the template and return the HTML
  return $smarty->fetch(ft_get_smarty_template_with_fallback($theme, "pagination.tpl"));
}


/**
 * Displays basic << 1 2 3 >> navigation for lists, each linking to the current page.
 *
 * This function has exactly the same purpose as display_page_nav, except that the pages are
 * hidden/shown with DHTML instead of separate server-side calls per page. This technique is better
 * for lists that contain a smaller number of items, e.g. the client and forms listing pages.
 *
 * ASSUMPTION: the JS counterpart function with the same function is defined in the calling page.
 * That function does all the work of hiding/showing pages, updating the "viewing X-Y"
 * text, enabling disabling the << and >> arrows, and storing the current page in sessions. This
 * function merely sets up the base HTML + JS.
 *
 * This function uses a dhtml_pagination.tpl Smarty template file, found in the current theme's root
 * folder.
 *
 * @param integer $num_results The total number of results found.
 * @param integer $num_per_page The max number of results to list per page.
 * @param integer $current_page The current page number being examined (defaults to 1).
 */
function ft_get_dhtml_page_nav($num_results, $num_per_page, $current_page = 1)
{
  global $g_smarty_debug, $g_root_dir, $g_root_url, $LANG, $g_smarty_use_sub_dirs;

  $theme = $_SESSION["ft"]["account"]["theme"];

  $smarty = new Smarty();
  $smarty->template_dir = "$g_root_dir/themes/$theme";
  $smarty->compile_dir  = "$g_root_dir/themes/$theme/cache/";
  $smarty->use_sub_dirs = $g_smarty_use_sub_dirs;
  $smarty->assign("LANG", $LANG);
  $smarty->assign("SESSION", $_SESSION["ft"]);
  $smarty->assign("g_root_dir", $g_root_dir);
  $smarty->assign("g_root_url", $g_root_url);
  $smarty->assign("samepage", $_SERVER["PHP_SELF"]);
  $smarty->assign("num_results", $num_results);
  $smarty->assign("num_per_page", $num_per_page);
  $smarty->assign("current_page", $current_page);

  // find the range that's being displayed (e.g 11 to 20)
  $range_start = ($current_page - 1) * $num_per_page + 1;
  $range_end   = $range_start + $num_per_page - 1;
  $range_end   = ($range_end > $num_results) ? $num_results : $range_end;

  $smarty->assign("range_start", $range_start);
  $smarty->assign("range_end", $range_end);

  $viewing_range = "";
  if ($num_results > $num_per_page)
  {
    $replacement_info = array(
      "startnum" => "<span id='nav_viewing_num_start'>$range_start</span>",
      "endnum"   => "<span id='nav_viewing_num_end'>$range_end</span>"
        );
    $viewing_range = ft_eval_smarty_string($LANG["phrase_viewing_range"], $replacement_info);
  }
  $smarty->assign("viewing_range", $viewing_range);
  $smarty->assign("total_pages", ceil($num_results / $num_per_page));

  // now process the template and return the HTML
  return $smarty->fetch(ft_get_smarty_template_with_fallback($theme, "dhtml_pagination.tpl"));
}


/**
 * Provides basic permission checking for accessing the pages.
 *
 * Verifies the user has permission to view the current page. It is used by feeding the minimum
 * account type to view the page - "client", will let administrators and clients view it, but
 * "admin" will only let administrators. If the person doesn't have permission to view the page
 * they are booted out.
 *
 * Should be called on ALL Form Tools pages - including modules.
 *
 * @param string $account_type The account type - "admin" / "client" / "user" (for Submission Accounts module)
 */
function ft_check_permission($account_type)
{
  global $g_root_url, $g_table_prefix;

  $boot_out_user = false;
  $message_flag = "";

  extract(ft_process_hooks("end", compact("account_type"), array("boot_out_user", "message_flag")), EXTR_OVERWRITE);

  // some VERY complex logic here. The "user" account permission type is included so that people logged in
  // via the Submission Accounts can still view certain pages, e.g. pages with the Pages module. This checks that
  // IF the minumum account type of the page is a "user", it EITHER has the user account info set (i.e. the submission ID)
  // or it's a regular client or admin account with the account_id set. Crumby, but it'll have to suffice for now.
  if ($account_type == "user")
  {
    if ((!isset($_SESSION["ft"]["account"]["submission_id"]) || empty($_SESSION["ft"]["account"]["submission_id"])) &&
       empty($_SESSION["ft"]["account"]["account_id"]))
    {
      header("location: $g_root_url/modules/submission_accounts/logout.php");
      exit;
    }
  }
   // check the user ID is in sessions
  else if      (empty($_SESSION["ft"]["account"]["account_id"]))
  {
    $boot_out_user = true;
    $message_flag = "notify_no_account_id_in_sessions";
  }
  else if ($_SESSION["ft"]["account"]["account_type"] == "client" && $account_type == "admin")
  {
    $boot_out_user = true;
    $message_flag = "notify_invalid_permissions";
  }
  else
  {
    $query = mysql_query("
      SELECT count(*)
      FROM   {$g_table_prefix}accounts
      WHERE account_id = {$_SESSION["ft"]["account"]["account_id"]}
      AND   password = '{$_SESSION["ft"]["account"]["password"]}'
        ");

    if (mysql_num_rows($query) != 1)
    {
      $boot_out_user = true;
      $message_flag = "notify_invalid_account_information_in_sessions";
    }
  }

  if ($boot_out_user)
    ft_logout_user($message_flag);
}


/**
 * Checks to see if a database table exists. Handy for modules to check to see if they've been installed
 * or not.
 *
 * @return boolean
 */
function ft_check_db_table_exists($table)
{
  global $g_table_prefix, $g_db_name;

  $found = false;
  $result = mysql_query("SHOW TABLES FROM $g_db_name");
  while ($row = mysql_fetch_row($result))
  {
    if ($row[0] == $table)
    {
      $found = true;
      break;
    }
  }

  return $found;
}


/**
 * Checks that the currently logged in client is permitted to view a particular form View. This is called
 * on the form submissions and edit submission pages, to ensure the client isn't trying to look at something
 * they shouldn't. Any time it fails, it logs them out with a message informing them that they're not allowed
 * to access that page. (FYI, it's possible that this scenario could happen honestly: e.g. if the administrator
 * creates a client menu containing links to particular forms; then accidentally assigning a client to the menu
 * that doesn't have permission to view the form).
 *
 * This relies on the $_SESSION["ft"]["permissions"] key being set by the login function: it contains the form
 * and View IDs that this.
 *
 * Because of this, any time the administrator changes the permissions for a client, they'll need te re-login to
 * access that new information.
 *
 * @param integer $form_id The unique form ID
 * @param integer $client_id The unique client ID
 */
function ft_check_client_may_view($client_id, $form_id, $view_id)
{
  global $g_root_url;
  $boot_out_user = false;

  //$search = ft_search_forms($client_id);

  // $permissions = ft_get_client_form_views($account_info["account_id"]);
  $permissions = $_SESSION["ft"]["permissions"];

  extract(ft_process_hooks("main", compact("client_id", "form_id", "view_id", "permissions"), array("permissions")), EXTR_OVERWRITE);

  if (!array_key_exists($form_id, $permissions))
    ft_logout_user("notify_invalid_permissions");
  else
  {
    if (!empty($view_id) && !in_array($view_id, $permissions[$form_id]))
      ft_logout_user("notify_invalid_permissions");
  }
}


/**
 * Return a date string from a MySQL datetime according based on an offset and a display format.
 * As of version 1.5.0, this function is language localized. The following php date() flags are
 * translated:
 * 			D    - Mon through Sun
 *      l    - Sunday through Saturday
 *      F    - January through December
 *      M    - Jan through Dec
 *      a    - am or pm
 *      A    - AM or PM
 *
 * Note that some flags (S for "st","rd", "nd" etc. and T for timezone, EST, MDT etc) are NOT
 * translated. This is. Also, this function only uses the standard Gregorian calendar. Nothing
 * fancy! My Unicode 5 book in on route, so I'll look into that in a later version. ;-)
 *
 * @param integer $offset the number of hours offset from GMT (- or +)
 * @param string $datetime the mysql datetime to format
 * @param string $format the date format to use (PHP's date() function).
 * @return string the date/time as a fully localized string
 */
function ft_get_date($offset, $datetime, $format)
{
  global $LANG;

  $year = substr($datetime, 0, 4);
  $mon  = substr($datetime, 5, 2);
  $day  = substr($datetime, 8, 2);
  $hour = substr($datetime, 11, 2);
  $min  = substr($datetime, 14, 2);
  $sec  = substr($datetime, 17, 2);

  $timestamp = mktime($hour + $offset, $min, $sec, $mon, $day, $year);

  // if this is an English language (British, US English, English Canadian, etc), just
  // use the standard date() functionality (this is faster)
  $date_str = "";
  if ($LANG["special_language"] == "English")
    $date_str = date($format, $timestamp);
  else
  {
    // here's how this works. We replace the special chars in the date formatting
    // string with a single "@" character - which has no special meaning for either date()
    // or in regular expressions - and keep track of the order in which they appear. Then,
    // we call date() to convert all other characters and then replace the @'s with their
    // translated versions.
    $special_chars = array("D", "l", "F", "M", "a", "A"); // M: short month, F: long month
    $char_map = array();
    $new_format = "";
    for ($char_ind=0; $char_ind<strlen($format); $char_ind++)
    {
      if (in_array($format[$char_ind], $special_chars))
      {
        $char_map[] = $format[$char_ind];
        $format[$char_ind] = "@";
      }
      $new_format .= $format[$char_ind];
    }
    $date_str = date($new_format, $timestamp);

    // now replace the @'s with their translated equivalents
    $eng_strings = date(join(",", $char_map), $timestamp);
    $eng_string_arr = split(",", $eng_strings);
    for ($char_ind=0; $char_ind<count($char_map); $char_ind++)
    {
      $eng_string = $eng_string_arr[$char_ind];

      switch($char_ind)
      {
        case "F":
          $translated_str = $LANG["date_month_short_$eng_string"];
          break;
        case "M":
          $translated_str = $LANG["date_month_$eng_string"];
          break;
        default:
          $translated_str = $LANG["date_$eng_string"];
          break;
      }
      $date_str = preg_replace("/@/", $translated_str, $date_str, 1);
    }
  }

  return $date_str;
}


/**
 * Returns a date in Y-m-d H:i:s format, generally used for inserting into a MySQL
 * datetime field.
 *
 * @param string $timestamp an optional Unix timestamp to convert to a datetime
 * @return string the current datetime in string format
 * */
function ft_get_current_datetime($timestamp = "")
{
  $datetime = "";
  if (!empty($timestamp))
    $datetime = date("Y-m-d H:i:s", $timestamp);
  else
    $datetime = date("Y-m-d H:i:s");

  return $datetime;
}


/**
 * Helper function to convert a MySQL datetime to a unix timestamp.
 *
 * @param string $datetime
 * @return string
 */
function ft_convert_datetime_to_timestamp($datetime)
{
  list($date, $time) = explode(" ", $datetime);
  list($year, $month, $day) = explode("-", $date);
  list($hours, $minutes, $seconds) = explode(":", $time);

  return mktime($hours, $minutes, $seconds, $month, $day, $year);
}


/**
 * Helper function which should be used on all submitted data to properly escape user-inputted
 * values for inserting into a database. This replaces the former ft_clean_hash function and
 * can be used on any variable.
 *
 * @param mixed
 * @return array The "clean" (escaped) hash.
 */
function ft_sanitize($input)
{
  if (is_array($input))
  {
    $output = array();
    foreach ($input as $k=>$i)
      $output[$k] = ft_sanitize($i);
  }
  else
  {
    if (get_magic_quotes_gpc())
      $input = stripslashes($input);

    $output = mysql_real_escape_string($input);
  }

  return $output;
}


/**
 * Recursively strips tags from an array / string.
 *
 * @param mixed $input an array or string
 * @return mixes
 */
function ft_strip_tags($input)
{
  if (is_array($input))
  {
    $output = array();
    foreach ($input as $k=>$i)
      $output[$k] = ft_strip_tags($i);
  }
  else
  {
    $output = strip_tags($input);
  }

  return $output;
}


/**
 * Used to convert language file strings into their JS-compatible counterparts, all within an
 * "g" namespace.
 *
 * @param array keys The $LANG keus
 * @return string $js the javascript string (WITHOUT the <script> tags)
 */
function ft_generate_js_messages($keys)
{
  global $g_root_url, $LANG;

  $theme = $_SESSION["ft"]["account"]["theme"];

  $rows = "";
  if (!empty($keys))
  {
    $js_rows = array();
    for ($i=0; $i<count($keys); $i++)
    {
      $key = $keys[$i];
      if (array_key_exists($key, $LANG))
      {
        $str = preg_replace("/\"/", "\\\"", $LANG[$key]);
        $js_rows[] = "g.messages[\"$key\"] = \"$str\";";
      }
    }
    $rows = join("\n", $js_rows);
  }

  $js = "// our namespace
  if (typeof g == \"undefined\")
    g = {};

  g.theme_folder = \"$theme\";
  g.messages     = [];
  $rows
    ";

  extract(ft_process_hooks("end", compact("js"), array("js")), EXTR_OVERWRITE);

  return $js;
}


/**
 * This invaluable little function is used for storing and overwriting the contents of a single
 * form field in sessions based on a sequence of priorities.
 *
 * It assumes that a variable name can be found in GET, POST or SESSIONS (or all three). What this
 * function does is return the value stored in the most important variable (GET first, POST second,
 * SESSIONS third), and update sessions at the same time. This is extremely helpful in situations
 * where you don't want to keep having to submit the same information from page to page.
 * The third parameter is included as a way to set a default value.
 *
 * @param string $field_name the field name
 * @param string $session_name the session key for this field name
 * @param string $default_value the default value for the field
 * @return string the field value
 */
function ft_load_field($field_name, $session_name, $default_value = "", $namespace = "ft")
{
  $field = $default_value;

  if (isset($_GET[$field_name]))
  {
    $field = $_GET[$field_name];
    $_SESSION[$namespace][$session_name] = $field;
  }
  else if (isset($_POST[$field_name]))
  {
    $field = $_POST[$field_name];
    $_SESSION[$namespace][$session_name] = $field;
  }
  else if (isset($_SESSION[$namespace][$session_name]))
  {
    $field = $_SESSION[$namespace][$session_name];
  }

  return $field;
}


/**
 * Checks a user-defined string is a valid MySQL datetime.
 *
 * @param string $datetime
 * @return boolean
 */
function ft_is_valid_datetime($datetime)
{
  if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $datetime, $matches))
  {
    if (checkdate($matches[2], $matches[3], $matches[1]))
      return true;
  }

  return false;
}


/**
 * This function - called on the login page - checks that the /install folder has been removed.
 *
 * If the folder still exists, it redirects to the installation script with a unique GET flag to display an
 * appropriate error message.
 */
function ft_verify_form_tools_installed()
{
  global $LANG;

  if (is_dir('install'))
  {
    $folder = dirname(__FILE__);
    header("Location: install/");
    exit;
  }
}


/**
 * This function was added in 1.4.7 to handle serious, show-stopper errors instead of the former
 * hardcoded die() function calls. This stores the error in sessions and redirects to error.php, which
 * decides how the error is displayed based on the error_type ("notify": for "softer"
 * errors like the install folder hasn't been deleted; "error" for more serious problems) and on
 * whether or not the global $g_debug option is enabled. If it is, the error.php page displays
 * the nitty-gritty errors returned by the server / database.
 *
 * @param string $error_message the user-friendly version of the error.
 * @param string $debug_details the error message returned by the server / database.
 * @param string $error_type either "error" or "notify"
 */
function ft_handle_error($error_message, $debug_details, $error_type = "error")
{
  global $g_root_url;

  // this is for NEW installations. For new installations the $g_root_url isn't set, so we want to
  // redirect to the error page in the current form tools folder
  if (!empty($g_root_url))
    $g_root_url = "$g_root_url/";

  $_SESSION["ft"]["last_error"]       = $error_message;
  $_SESSION["ft"]["last_error_debug"] = $debug_details;
  $_SESSION["ft"]["last_error_type"]  = $error_type;

  session_write_close();
  header("Location: {$g_root_url}error.php");
  exit;
}


/**
 * A multibyte version of str_split. Splits a string into chunks and returns the pieces in
 * an array.
 *
 * @param string $string The string to manipulate.
 * @param integer $split_length The number of characters in each chunk.
 * @return array an array of chunks, each of size $split_length. The last index contains the leftovers.
 *      If <b>$split_length</b> is less than 1, return false.
 */
function mb_str_split($string, $split_length = 1)
{
  if ($split_length < 1)
    return false;

  $result = array();
  for ($i=0; $i<mb_strlen($string); $i+=$split_length)
    $result[] = mb_substr($string, $i, $split_length);

  return $result;
}


/**
 * Helper function to construct a valid URL. This will probably be improved and renamed in future.
 *
 * @param string $base_url
 * @param string $query_string
 */
function ft_construct_url($url, $query_str = "")
{
  $valid_url = $url;

  if (!empty($query_str))
  {
    // only include the ? if it's not already there
    if (strpos($url, "?"))
      $valid_url .= "&{$query_str}";
    else
      $valid_url .= "?{$query_str}";
  }

  return $valid_url;
}


/**
 * Helper function that's called as a wrapper to the PHP json_encode function. If it doesn't exist,
 * it manually encodes the value as a JSON object.
 *
 * @param mixed $arr
 * @return string the JSON object (as a PHP string)
 */
function ft_convert_to_json($arr)
{
  if (function_exists('json_encode'))
    return json_encode($arr);

  $parts = array();
  $is_list = false;

  // find out if the given array is a numerical array
  $keys = array_keys($arr);
  $max_length = count($arr)-1;

  // see if the first key is 0 and last key is length - 1
  if (isset($keys[0]) && ($keys[0] == 0) && ($keys[$max_length] == $max_length))
  {
    $is_list = true;

    // see if each key corresponds to its position
    for ($i=0; $i<count($keys); $i++)
    {
      // a key fails at position check: it's a hash
      if ($i != $keys[$i])
      {
        $is_list = false;
        break;
      }
    }
  }

  foreach ($arr as $key=>$value)
  {
    // custom handling for arrays
    if (is_array($value))
    {
      if ($is_list)
        $parts[] = ft_convert_to_json($value);
      else
        $parts[] = '"' . $key . '":' . ft_convert_to_json($value);
    }
    else
    {
      $str = '';
      if (!$is_list)
        $str = '"' . $key . '":';

      // custom handling for multiple data types
      if (is_numeric($value))
        $str .= $value;
      elseif ($value === false)
        $str .= 'false';
      elseif ($value === true)
        $str .= 'true';
      else
      {
        //$value = preg_replace("/\s+/", "\n", $value);
        //$value = ft_sanitize($value);
        $str .= '"' . ft_sanitize($value) . '"';
      }

      $parts[] = $str;
    }
  }

  $json = implode(',', $parts);

  if ($is_list)
    return '[' . $json . ']';

  return '{' . $json . '}';
}


/**
 * Extracted from validate_fields. Simple function to test if a string is an email or not.
 *
 * @param string $str
 * @return boolean
 */
function ft_is_valid_email($str)
{
  $regexp="/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
  return preg_match($regexp, $str);
}


/**
 * Returns a list of MySQL reserved words, to prevent the user accidentally entering a database field name
 * that has a special meaning for MySQL.
 */
function ft_get_mysql_reserved_words()
{
  global $g_root_dir;

  $words = @file("$g_root_dir/global/misc/mysql_reserved_words.txt");

  $clean_words = array();
  foreach ($words as $word)
  {
    $word = trim($word);
    if (!empty($word) && !in_array($word, $clean_words))
      $clean_words[] = $word;
  }

  return $clean_words;
}


/**
 * A case insensitive version of in_array.
 */
function ft_in_array_case_insensitive($value, $array)
{
  foreach ($array as $item)
  {
    if (is_array($item))
      $return = ft_in_array_case_insensitive($value, $item);
    else
      $return = strtolower($item) == strtolower($value);

    if ($return)
      return $return;
  }

  return false;
}


/**
 * Returns the maximum size of a file allowed to be uploaded according to this server's php.ini file.
 *
 * @return integer the max file size in bytes
 */
function ft_get_upload_max_filesize()
{
  $max_filesize_str = ini_get("upload_max_filesize");
  $max_filesize_mb = (int)preg_replace("/\D+/", "", $max_filesize_str);
  $max_filesize_bytes = $max_filesize_mb * 1000;

  return $max_filesize_bytes;
}


/**
 * A simple helper function to convert any string to a "slug" - an alphanumeric, "_" and/or "-" string
 * for use in (e.g.) generating filenames.
 *
 * @param string $string
 * @return string
 */
function ft_create_slug($string)
{
  $str = trim($string);
  $str = preg_replace('/[^a-zA-Z0-9]/', '_', $str);
  $str = preg_replace('/_{2,}/', "_", $str);

  return $str;
}


/**
 * Generates a random password of a certain length.
 *
 * @param integer $length the number of characters in the password
 * @return string the password
 */
function ft_generate_password($length = 8)
{
  $password = "";
  $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
  $i=0;

  // add random characters to $password until $length is reached
  while ($i <$length)
  {
    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

    // we don't want this character if it's already in the password
    if (!strstr($password, $char))
    {
      $password .= $char;
      $i++;
    }
  }

  return $password;
}


/**
 * This function is used by the Smart Fill functionality. In order for the JS to be allowed to parse
 * the pages, they need to be on the same domain. This function figured out the method by which those
 * pages can be acquired for this particular server. It returns a string representing the method to
 * use, found in this order:
 *   1. "file_get_contents"
 *   2. "curl"
 *   3. "redirect" - this means that the form webpage is already on the same site, so it can be accessed
 *      directly
 *   4. "" - the empty string gets returned if none of the above methods apply. In this case, the user will
 *      have to manually upload copies of the files which are then created locally for parsing.
 *
 * FIXME. There's a potentially bug with this function, which I haven't been able to solve for both PHP 4 & 5:
 * if the URL is invalid, file_get_contents can timeout with a fatal error. To reduce the likelihood of this
 * occurring, Step 2 of the Add Form process requires the user to have confirmed each of the form URLs.
 * Nevertheless, this needs to be addressed at some point.
 */
function ft_get_js_webpage_parse_method($form_url)
{
  // set a 1 minute maximum execution time for this request
  @set_time_limit(60);

  $scrape_method = "";

  // we buffer the file_get_contents call in case the URL is invalid and a fatal error is generated
  // when the function time-outs
  if (@file_get_contents($form_url))
    $scrape_method = "file_get_contents";
  if (function_exists("curl_init") && function_exists("curl_exec"))
    $scrape_method = "curl";
  else
  {
    $current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $current_url_info = parse_url($current_url);
    $form_url_info = parse_url($form_url);

    if (($current_url_info["host"] == $form_url_info["host"]) && ($current_url_info["port"] == $form_url_info["port"]))
      $scrape_method = "redirect";
  }

  return $scrape_method;
}


/**
 * This updates the version of the API in the database. It's called on installation and whenever someone logs in.
 * It's used to keep the API version up to date in the database so that whenever the user clicks their UPGRADE
 * button, the correct API version is passed to the upgrade script to let it know if it needs to be upgraded or
 * not.
 */
function ft_update_api_version()
{
  global $g_root_dir;

  $api_file = "$g_root_dir/global/api/api.php";
  if (is_file($api_file))
  {
    include_once($api_file);

    if (!isset($g_api_version) || empty($g_api_version))
      return;

    $settings = array("api_version" => $g_api_version);
    ft_set_settings($settings);
  }
}


// ------------------------------------------------------------------------------------------------


if (!function_exists("mb_strtoupper"))
{
  /**
   * A fallback function for servers that don't include the mbstring PHP extension. Note:
   * this function is NOT multi-byte; it can't be emulated without the extension. However,
   * this will at least allow the user to use Form Tools without errors.
   *
   * @param string $str
   * @return string the uppercased string
   */
  function mb_strtoupper($str)
  {
    return strtoupper($str);
  }
}

if (!function_exists("mb_strtolower"))
{
  /**
   * A fallback function for servers that don't include the mbstring PHP extension. Note:
   * this function is NOT multi-byte; it can't be emulated without the extension. However,
   * this will at least allow the user to use Form Tools without errors.
   *
   * @param string $str
   * @return string the uppercased string
   */
  function mb_strtolower($str)
  {
    return strtolower($str);
  }
}

if (!function_exists("mb_strlen"))
{
  /**
   * A fallback function for servers that don't include the mbstring PHP extension. Note:
   * this function is NOT multi-byte; it can't be emulated without the extension. However,
   * this will at least allow the user to use Form Tools without errors.
   *
   * @param string $str
   * @return string the length of the string
   */
  function mb_strlen($str)
  {
    return strlen($str);
  }
}

if (!function_exists("mb_substr"))
{
  /**
   * A fallback function for servers that don't include the mbstring PHP extension. Note:
   * this function is NOT multi-byte; it can't be emulated without the extension. However,
   * this will at least allow the user to use Form Tools without errors.
   *
   * @param string $str
   * @return string the length of the string
   */
  function mb_substr($str, $start, $length)
  {
    return substr($str, $start, $length);
  }
}

if (!function_exists("htmlspecialchars_decode"))
{
  function htmlspecialchars_decode($string, $style=ENT_COMPAT)
  {
    $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS, $style));
    if ($style === ENT_QUOTES)
      $translation['&#039;'] = '\'';

    return strtr($string, $translation);
  }
}

if (!function_exists('mime_content_type'))
{
  function mime_content_type($filename)
  {
    $mime_types = array(
      'txt' => 'text/plain',
      'htm' => 'text/html',
      'html' => 'text/html',
      'php' => 'text/html',
      'css' => 'text/css',
      'js' => 'application/javascript',
      'json' => 'application/json',
      'xml' => 'application/xml',
      'swf' => 'application/x-shockwave-flash',
      'flv' => 'video/x-flv',

      // images
      'png' => 'image/png',
      'jpe' => 'image/jpeg',
      'jpeg' => 'image/jpeg',
      'jpg' => 'image/jpeg',
      'gif' => 'image/gif',
      'bmp' => 'image/bmp',
      'ico' => 'image/vnd.microsoft.icon',
      'tiff' => 'image/tiff',
      'tif' => 'image/tiff',
      'svg' => 'image/svg+xml',
      'svgz' => 'image/svg+xml',

      // archives
      'zip' => 'application/zip',
      'rar' => 'application/x-rar-compressed',
      'exe' => 'application/x-msdownload',
      'msi' => 'application/x-msdownload',
      'cab' => 'application/vnd.ms-cab-compressed',

      // audio/video
      'mp3' => 'audio/mpeg',
      'qt' => 'video/quicktime',
      'mov' => 'video/quicktime',

      // adobe
      'pdf' => 'application/pdf',
      'psd' => 'image/vnd.adobe.photoshop',
      'ai' => 'application/postscript',
      'eps' => 'application/postscript',
      'ps' => 'application/postscript',

      // ms office
      'doc' => 'application/msword',
      'rtf' => 'application/rtf',
      'xls' => 'application/vnd.ms-excel',
      'ppt' => 'application/vnd.ms-powerpoint',

      // open office
      'odt' => 'application/vnd.oasis.opendocument.text',
      'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    $ext = strtolower(array_pop(explode('.', $filename)));
    if (array_key_exists($ext, $mime_types))
    {
      return $mime_types[$ext];
    }
    elseif (function_exists('finfo_open'))
    {
      $finfo = finfo_open(FILEINFO_MIME);
      $mimetype = finfo_file($finfo, $filename);
      finfo_close($finfo);
      return $mimetype;
    }
    else {
      return 'application/octet-stream';
    }
  }
}