<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$sql = null;
foreach (glob("../client.demo/*/wifi_1_day.html") as $filename) {
  $temp = file_get_contents($filename);#

  //Get brand and type names
  preg_match_all('/.*?\/.*?\/(.*?)\/(.*?).html/', $filename, $matches);
  $brand = $matches[1][0];
  $type = $matches[2][0];

  //Remove comment tags
  $temp = preg_replace('/\<!--.*?\-->/ms', '', $temp);
  $temp = str_replace('WHAT\'S', 'WHAT&apos;S', $temp);
  $temp = preg_replace('/\'/ms', '\\\'', $temp);
  $temp = removeWhiteSpace($temp);

  //Brand to uppercase
  $upperCaseName = str_replace('_', ' ', $brand);
  $upperCaseName = ucwords($upperCaseName);

  //Email type fix
  $type = str_replace('_', ' ', $type);
  $type = ucwords($type);
  if($type === 'Wifi 1 Day'){
    $type = "WIFI sign in 1 + 1 Day";
  }

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

  //Get Email content
  $email = 'WIFI sign in 1 + 1 Day';
  $wifiRows = null;
  $initialQuery = "SELECT * FROM `copy_iteration1_all` WHERE `email` = '" . $email . "'";
  $rows = databaseQuery($initialQuery);
  foreach($rows as $key => $row){
    $wifiRows = $row;
    break;
  }
  $subject = null;
  $voucher = null;
  $preHeader = null;
  foreach($wifiRows as $key => $row){
    $subject = $wifiRows[3];
    $preHeader = $wifiRows[4];
    $voucher = '0';
  }

  //Name declaration
  $name = $upperCaseName . ' - T:' . date("Ymd") . ' - ' . $type;

  //Build campaigns+ settings
  $settings = buildTemplateSettings($name, $preHeader, $subject, $brandID, $profileID);
  $mappings = buildTemplateMappings();

  //Build SQL statements
  $sql .= "insert into `tbl_email_templates` (`template_account_id`, `template_status`, `template_html`, `template_text`, `template_title`, `template_description`, `template_added`, `template_modified`, `template_visible`, `template_subject`, `template_preview`, `template_last_used`, `template_sender_id`, `template_dynamic1_mapping`, `template_dynamic2_mapping`, `template_dynamic3_mapping`, `template_dynamic4_mapping`, `template_dynamic5_mapping`, `template_dynamic6_mapping`, `template_dynamic7_mapping`, `template_dynamic8_mapping`, `template_isTemp`, `template_visual_editor`, `template_has_voucher`, `template_ve_settings`, `template_ve_mappings`)
          values('" . $accountID . "', '1', '" . $temp . "', '', '" . $name . "', '', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', '1', '" . $subject . "', '', '" . date("Y-m-d H:i:s") . "', '" . $profileID . "', '', '', '', '', '', '', '', '', '0', '1', '" . $voucher . "', '" . $settings . "', '" . $mappings . "');\n";
}

$append = "wifi_1_insert";
$path = "inserts";
$save = false;

sendToFile($sql,$path, $append, $brand, '.html', $save);

print_r($sql);

 ?>
