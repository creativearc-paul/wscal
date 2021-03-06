<?php
session_start();

/**
 * File: process.php
 *
 * This file processes any form submissions for forms already added and configured within Form Tools. To
 * use it, just point your form to this file, like so:
 *
 *   <form method="post" action="/path/to/process.php">
 *
 * Once the form has been added through the Form Tools UI, this script parses the form contents
 * and adds it to the database then redirects the user to whatever page is required. In addition,
 * this script is used to initially set up the form within the database, to map input fields to
 * database columns and types.
 */


$folder = dirname(__FILE__);

// always include the core library functions
require_once("$folder/global/library.php");

// if the API is supplied, include it as well
@include_once("$folder/global/api/api.php");


// check we're receiving something
if (empty($_POST))
{
  $page_vars = array("message_type" => "error", "message" => $LANG["processing_no_post_vars"]);
  ft_display_page("../../global/smarty/messages.tpl", $page_vars);
  exit;
}

// check there's a form ID included
else if (empty($_POST["form_tools_form_id"]))
{
  $page_vars = array("message_type" => "error", "message" => $LANG["processing_no_form_id"]);
  ft_display_page("../../global/smarty/messages.tpl", $page_vars);
  exit;
}

// is this an initialization submission?
else if (isset($_POST["form_tools_initialize_form"]))
  ft_initialize_form($_POST);

// otherwise, it's a regular form submission. Process it!
else
  ft_process_form($_POST);

// -------------------------------------------------------------------------------------------------

/**
 * This function processes the form submissions, after the form has been set up in the database.
 */
function ft_process_form($form_data)
{
  global $g_table_prefix, $g_multi_val_delimiter, $g_query_str_multi_val_separator, $g_root_dir, $LANG,
   $g_api_version, $g_api_recaptcha_private_key;

  // ensure the incoming values are escaped
  $form_data = ft_sanitize($form_data);

  $form_id = $form_data["form_tools_form_id"];
  $form_info = ft_get_form($form_id);

  extract(ft_process_hooks("start", compact("form_info", "form_id", "form_data"), array("form_data")), EXTR_OVERWRITE);

  // check to see if this form has been completely set up
  if ($form_info["is_complete"] == "no")
  {
    $page_vars = array("message_type" => "error", "message" => $LANG["processing_form_incomplete"]);
    ft_display_page("../../global/smarty/messages.tpl", $page_vars);
    exit;
  }

  // check to see if this form has been disabled
  if ($form_info["is_active"] == "no")
  {
    if (isset($form_data["form_tools_inactive_form_redirect_url"]))
    {
      header("location: {$form_data["form_tools_inactive_form_redirect_url"]}");
      exit;
    }

    $page_vars = array("message_type" => "error", "message" => $LANG["processing_form_disabled"]);
    ft_display_page("../../global/smarty/messages.tpl", $page_vars);
    exit;
  }

  // do we have a form for this id?
  if (!ft_check_form_exists($form_id))
  {
    $page_vars = array("message_type" => "error", "message" => $LANG["processing_invalid_form_id"]);
    ft_display_page("../../global/smarty/messages.tpl", $page_vars);
    exit;
  }

// Tzvook captcha
$folder = dirname(__FILE__);
@include_once("$folder/securimage/securimage.php");
$securimage = new Securimage();

if ($securimage->check($_POST['captcha_code']) == false) {
  // the code was incorrect
  // you should handle the error so that the form processor doesn't continue

  // or you can use the following code if there is no validation or you do not know how
$page_vars = array("message_type" => "error", "message" => "<center><div style=\"width: 500px; border: 1px solid #FF0000; background-color: #FFFFFF; padding: 30px; font-family: Tahoma, Arial; font-size: 16px; color: #000000; margin: 0 auto;\">You did not typed the right verification code<br /><br />The verification code is checking to see if you are really humans<br />and not a SPAM automatic robots</strong><br /><br />Please go back and try again<br /><br /><A href=\"javascript:history.back(-1)\" onmouseover='window.status = \"\" ;return true'>Back to the form</a></div></center>");
        ft_display_page("../../global/smarty/messages.tpl", $page_vars);
        exit;
}
//  End Tzvook captcha


  // was there a reCAPTCHA response? If so, a recaptcha was just submitted. This generally implies the
  // form page included the API, so check it was entered correctly. If not, return the user to the webpage
  if (isset($g_api_version) && isset($form_data["recaptcha_response_field"]))
  {
    $passes_captcha = false;
    $recaptcha_challenge_field = $form_data["recaptcha_challenge_field"];
    $recaptcha_response_field  = $form_data["recaptcha_response_field"];

    $folder = dirname(__FILE__);
    require_once("$folder/global/api/recaptchalib.php");

    $resp = recaptcha_check_answer($g_api_recaptcha_private_key, $_SERVER["REMOTE_ADDR"], $recaptcha_challenge_field, $recaptcha_response_field);

    if ($resp->is_valid)
      $passes_captcha = true;
    else
    {
      // since we need to pass all the info back to the form page we do it by storing the data in sessions. Enable 'em.
      @ft_api_start_sessions();
      $_SESSION["form_tools_form_data"] = $form_data;
      $_SESSION["form_tools_form_data"]["api_recaptcha_error"] = $resp->error;

      // if there's a form_tools_form_url specified, redirect to that
      if (isset($form_data["form_tools_form_url"]))
      {
        header("location: {$form_data["form_tools_form_url"]}");
        exit;
      }
      // if not, see if the server has the redirect URL specified
      else if (isset($_SERVER["HTTP_REFERER"]))
      {
        header("location: {$_SERVER["HTTP_REFERER"]}");
        exit;
      }
      // no luck! Throw an error
      else
      {
        $page_vars = array("message_type" => "error", "message" => $LANG["processing_no_form_url_for_recaptcha"]);
        ft_display_page("../../global/smarty/messages.tpl", $page_vars);
        exit;
      }
    }
  }


  // get a list of the custom form fields (i.e. non-system) for this form
  $form_fields = ft_get_form_fields($form_id);

  $custom_form_fields = array();
  foreach ($form_fields as $field_info)
  {
    $field_id    = $field_info["field_id"];
    $field_name  = $field_info["field_name"];
    $col_name    = $field_info["col_name"];
    $field_title = $field_info["field_title"];
    $field_type  = $field_info["field_type"];
    $include_on_redirect  = $field_info["include_on_redirect"];

    // ignore system fields
    if ($field_type == "system")
      continue;

    $custom_form_fields[$field_name] = array(
      "field_id" => $field_id,
      "col_name" => $col_name,
      "field_title" => $field_title,
      "include_on_redirect" => $include_on_redirect,
      "field_type" => $field_type
        );
  }


  // now examine the contents of the POST/GET submission and get a list of those fields
  // which we're going to update
  $valid_form_fields     = array();
  while (list($form_field, $value) = each($form_data))
  {
    // if this field is included, store the value for adding to DB
    if (array_key_exists($form_field, $custom_form_fields))
    {
      // ignore file fields - they're handled separately
      if ($custom_form_fields[$form_field]["field_type"] == "file" || $custom_form_fields[$form_field]["field_type"] == "image")
        continue;

      $col_name = $custom_form_fields[$form_field]["col_name"];
      $query_col_names[] = $col_name;
      $cleaned_value = $value;

      if (is_array($value))
      {
        if ($form_info["submission_strip_tags"] == "yes")
        {
          for ($i=0; $i<count($value); $i++)
            $value[$i] = strip_tags($value[$i]);
        }

        $cleaned_value = join("$g_multi_val_delimiter", $value);
      }
      else
      {
        if ($form_info["submission_strip_tags"] == "yes")
          $cleaned_value = strip_tags($value);
      }

      $valid_form_fields[$col_name] = "'$cleaned_value'";
    }
  }


  $now = ft_get_current_datetime();
  $ip_address      = $_SERVER["REMOTE_ADDR"];
  $is_finalized    = "yes";

  $col_names = array_keys($valid_form_fields);
  $col_names_str = join(", ", $col_names);
  if (!empty($col_names_str))
    $col_names_str .= ", ";

  $col_values = array_values($valid_form_fields);
  $col_values_str = join(", ", $col_values);
  if (!empty($col_values_str))
    $col_values_str .= ", ";

  // build our query
  $query = "
      INSERT INTO {$g_table_prefix}form_$form_id ($col_names_str submission_date, last_modified_date, ip_address, is_finalized)
      VALUES ($col_values_str '$now', '$now', '$ip_address', '$is_finalized')
           ";


  // add the submission to the database (if form_tools_ignore_submission key isn't set by either the form or the
  // Submission Pre-Parser module)
  $submission_id = "";
  if (!isset($form_data["form_tools_ignore_submission"]))
  {
    $result = mysql_query($query);

    if (!$result)
    {
      $page_vars = array("message_type" => "error", "error_code" => 304, "error_type" => "system",
        "debugging"=> "Failed query in <b>" . __FUNCTION__ . ", " . __FILE__ . "</b>, line " . __LINE__ .
            ": <i>" . nl2br($query) . "</i>", mysql_error());
      ft_display_page("../../global/smarty/messages.tpl", $page_vars);
      exit;
    }

    $submission_id = mysql_insert_id();
    extract(ft_process_hooks("end", compact("form_id", "submission_id"), array()), EXTR_OVERWRITE);
  }


  $redirect_query_params = array();

  // build the redirect query parameter array. Note that we loop through the original
  foreach ($form_fields as $field_info)
  {
    if ($field_info["include_on_redirect"] == "no" || $field_info["field_type"] == "file" || $field_info["field_type"] == "image")
      continue;

    switch ($field_info["col_name"])
    {
      case "submission_id":
        $redirect_query_params[] = "submission_id=$submission_id";
        break;
      case "submission_date":
        $settings = ft_get_settings();
        $submission_date_formatted = ft_get_date($settings["default_timezone_offset"], $submission_date, $settings["default_date_format"]);
        $redirect_query_params[] = "submission_date=" . rawurlencode($submission_date_formatted);
        break;
      case "last_modified_date":
        $settings = ft_get_settings();
        $submission_date_formatted = ft_get_date($settings["default_timezone_offset"], $submission_date, $settings["default_date_format"]);
        $redirect_query_params[] = "last_modified_date=" . rawurlencode($submission_date_formatted);
        break;
      case "ip_address":
        $redirect_query_params[] = "ip_address=$ip_address";
        break;

      default:
        $field_name = $field_info["field_name"];

        // if $value is an array, convert it to a string, separated by $g_query_str_multi_val_separator
        if (is_array($form_data[$field_name]))
        {
          $value_str = join($g_query_str_multi_val_separator, $form_data[$field_name]);
          $redirect_query_params[] = "$field_name=" . rawurlencode($value_str);
        }
        else
          $redirect_query_params[] = "$field_name=" . rawurlencode($form_data[$field_name]);
        break;
    }
  }

  // now that the submission has been added to the database, upload any files that were included and
  // store the file name in the appropriate field
  while (list($form_field, $fileinfo) = each($_FILES))
  {
    if (empty($fileinfo["name"]))
      continue;

    if (array_key_exists($form_field, $custom_form_fields))
    {
      $field_id   = $custom_form_fields[$form_field]["field_id"];
      $field_type = $custom_form_fields[$form_field]["field_type"];

      if      ($field_type == "file")
        list($success, $message, $filename) = ft_upload_submission_file($form_id, $submission_id, $field_id, $fileinfo);
      else if ($field_type == "image")
        list($success, $message, $filename) = ft_upload_submission_image($form_id, $submission_id, $field_id, $fileinfo);
    }

    // if required, add the filename to the redirect query params
    if ($custom_form_fields[$form_field]["include_on_redirect"] == "yes")
      $redirect_query_params[] = "$form_field=$filename";
  }

  // send any emails
  ft_send_emails("on_submission", $form_id, $submission_id);

  // if the redirect URL has been specified either in the database or as part of the form
  // submission, redirect the user [form submission form_tools_redirect_url value overrides
  // database value]
  if (!empty($form_info["redirect_url"]) || !empty($form_data["form_tools_redirect_url"]))
  {
    // build redirect query string
    $redirect_url = (isset($form_data["form_tools_redirect_url"]) && !empty($form_data["form_tools_redirect_url"]))
      ? $form_data["form_tools_redirect_url"] : $form_info["redirect_url"];

    $query_str = "";
    if (!empty($redirect_query_params))
      $query_str = join("&", $redirect_query_params);

    if (!empty($query_str))
    {
      // only include the ? if it's not already there
      if (strpos($redirect_url, "?"))
        $redirect_url .= "&" . $query_str;
      else
        $redirect_url .= "?" . $query_str;
    }

    header("Location: " . $redirect_url);
    exit;
  }

  // the user should never get here! This means that the no redirect URL has been specified
  $page_vars = array("message_type" => "error", "message" => $LANG["processing_no_redirect_url"]);
  ft_display_page("../../global/smarty/messages.tpl", $page_vars);
  exit;
}
