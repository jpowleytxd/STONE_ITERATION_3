<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];

$sql = null;
foreach (glob("../pre_made/*/password_reset.html") as $filename) {
  $temp = file_get_contents($filename);

  //Get brand and type names
  preg_match_all('/.*?\/.*?\/(.*?)\/(.*?).html/', $filename, $matches);
  $brand = $matches[1][0];
  $type = $matches[2][0];

  //Remove comment tags
  // $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);
  $temp = preg_replace('/\{.*?\}/ms', '', $temp);
  $temp = str_replace('WHAT\'S', 'WHAT&apos;S', $temp);
  $temp = preg_replace('/\'/ms', '\\\'', $temp);
  $temp = removeWhiteSpace($temp);

  //Brand to uppercase
  $upperCaseName = str_replace('_', ' ', $brand);
  $upperCaseName = ucwords($upperCaseName);

  //Email type fix
  $type = str_replace('_', ' ', $type);
  $type = 'Forgotten Password';
  // if($type === 'Welcome 1 Day Uk'){
  //   $type = "Welcome 1 + 1 Day";
  // }

  //Get account data

  $initialQuery = 'SELECT * FROM account_data WHERE brand = "' . $brand . '"';
  $rows = databaseQuery($initialQuery);
  $accountID = null;
  $profileID = null;
  $brandID = null;
  $venueID = null;
  $veID = null;
  foreach($rows as $key => $row){
    $accountID = $row[2];
    $profileID = $row[3];
    $brandID = $row[4];
    $venueID = $row[5];
    $veID = $row[6];
  }

  // var_dump($profileID);

  //Get Email content
  $email = 'Forgotten Password';
  $table = 'copy_iteration3_' . $brand;
    $initialQuery = "SELECT * FROM `" . $table . "` WHERE `email` = '" . $email . "'";
  $rows = databaseQuery($initialQuery);
  foreach($rows as $key => $row){
    $passwordRows = $row;
    break;
  }
  $subject = null;
  $voucher = null;
  $preHeader = null;
  foreach($passwordRows as $key => $row){
    $subject = $passwordRows[3];
    $preHeader = $passwordRows[4];
    $voucher = '0';
  }

  //Name declaration
  $name = $upperCaseName . ' - T:' . date("Ymd") . ' - ' . $type;

  //Build campaigns+ settings
  $settings = buildTemplateSettings($name, $preHeader, $subject, $brandID, $profileID);
  $mappings = buildTemplateMappings();

  //Build SQL statements
  $sql .= "insert into `tbl_email_templates` (`template_account_id`, `template_status`, `template_html`, `template_text`, `template_title`, `template_description`, `template_added`, `template_modified`, `template_visible`, `template_subject`, `template_preview`, `template_last_used`, `template_sender_id`, `template_dynamic1_mapping`, `template_dynamic2_mapping`, `template_dynamic3_mapping`, `template_dynamic4_mapping`, `template_dynamic5_mapping`, `template_dynamic6_mapping`, `template_dynamic7_mapping`, `template_dynamic8_mapping`, `template_isTemp`, `template_visual_editor`, `template_has_voucher`, `template_ve_settings`, `template_ve_mappings`)
          values('" . '1222' . "', '1', '" . $temp . "', '', '" . $name . "', '', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', '1', '" . $subject . "', '', '" . date("Y-m-d H:i:s") . "', '" . $profileID . "', '', '', '', '', '', '', '', '', '0', '1', '" . $voucher . "', '" . $settings . "', '" . $mappings . "');\n";
}

$append = "forgotten_password_insert";
$path = "inserts";
$save = $saveToFile;

sendToFile($sql,$path, $append, $brand, '.sql', $save);

// print_r($sql);

echo $sql;

 ?>
