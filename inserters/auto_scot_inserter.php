<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = $_POST['saveStatus'];

$sql = null;
foreach (glob("../pre_made/*/auto_welcome_scot.html") as $filename) {
  $temp = file_get_contents($filename);

  //Get brand and type names
  preg_match_all('/.*?\/.*?\/(.*?)\/(.*?).html/', $filename, $matches);
  $brand = $matches[1][0];
  $type = $matches[2][0];

  //Remove comment tags
  $temp = preg_replace('/\{.*?\}/ms', '', $temp);
  $temp = preg_replace('/<!-- VenueStart -->/ms', '', $temp);
  $temp = preg_replace('/<!-- VenueEnd -->/ms', '', $temp);
  $temp = preg_replace('/<!-- BrandedStart -->/ms', '', $temp);
  $temp = preg_replace('/<!-- BrandedEnd -->/ms', '', $temp);
  $temp = preg_replace('/\'/ms', '[[RSQUO]]', $temp);
  $temp = removeWhiteSpace($temp);

  //Brand to uppercase
  $upperCaseName = str_replace('_', ' ', $brand);
  $upperCaseName = ucwords($upperCaseName);

  //Email type fix
  $type = str_replace('_', ' ', $type);
  $type = ucwords($type);
  if($type === 'Auto Welcome Scot'){
    $type = "Auto Welcome (Scot)";
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
  $email = 'Auto Welcome  - Immediate (Scot)';
  $autoRows = null;
  $table = 'copy_iteration3_' . $brand;
  $initialQuery = "SELECT * FROM `" . $table . "` WHERE `email` = '" . $email . "'";
  $rows = databaseQuery($initialQuery);
  foreach($rows as $key => $row){
    $autoRows = $row;
    break;
  }
  $subject = null;
  $voucher = null;
  $preHeader = null;
  foreach($autoRows as $key => $row){
    $subject = $autoRows[3];
    $subject = preg_replace('/\'/ms', '[[RSQUO]]', $subject);
    $preHeader = $autoRows[4];
    $preHeader = preg_replace('/\'/ms', '[[RSQUO]]', $preHeader);
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

$append = "auto_welcome_scot_insert";
$path = "inserts";
$save = $saveToFile;

sendToFile($sql,$path, $append, $brand, '.sql', $save);

// print_r($sql);

echo $sql;
 ?>
