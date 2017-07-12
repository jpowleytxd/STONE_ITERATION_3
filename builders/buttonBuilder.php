<?php

include("common.php");

// a table booking      -> /table-booking
// a party booking      -> /party-booking
// a pre-order          -> /party-booking
// view menus           -> /food-and-drink
// enter a competition  -> /competition
// give feedback        -> /feedback
// request a call back. -> /party-enquiry

// New Re-directs to be made:
//    -> /competition
//    -> /feedback

// var_dump(textColor("#ffff01")); die();

// Define hyperlinks for buttons
$tableBookingLink = "http://stonegateemail.co.uk/\$dynamic3\$/table-booking";
$partyBookingLink = "http://stonegateemail.co.uk/\$dynamic3\$/party-booking";
$preOrderLink = "http://stonegateemail.co.uk/\$dynamic3\$/party-booking";
$menuLink = "http://stonegateemail.co.uk/\$dynamic3\$/food-and-drink";
$competitionLink = "http://stonegateemail.co.uk/\$dynamic3\$/website";
$feedbackLink = "http://stonegateemail.co.uk/\$dynamic3\$/website";
$callBackLink = "http://stonegateemail.co.uk/\$dynamic3\$/party-enquiry";

// Define text for buttons
$tableBookingText = "Book My Table";
$partyBookingText = "Book My Party";
$preOrderText = "Pre Order Now";
$menuText = "View Menus Now";
$competitionText = "Enter Now";
$feedbackText = "Feedback";
$callBackText = "Request A Call";

// Get default button from file
$basicButton = file_get_contents("../sites/_defaults/button.html");

// Get default spacer from file
$basicSpacer = file_get_contents("../sites/_defaults/basic_spacer.html");

// Styles for button insertion
$basicStyles = "width: 150px; text-align:center; font-size: 14px; [[FONT_FAMILY_HERE]] font-weight: 500; [[TEXT_COLOR_HERE]] text-decoration: none; [[BACKGROUND_COLOR_HERE]] border-top-width: 15px; border-bottom-width: 15px; border-left-width: 25px; border-right-width: 25px; border-style: solid; [[BORDER_COLOR_HERE]] display: inline-block;";

// Foreach brand in sites folder
foreach(glob('../sites/*/templates/*_branded.html') as $filename){
  $template = file_get_contents($filename);

  // Get current folder structure, file name and remove file name from folder structure
  preg_match_all('/.*\/(templates\/.*_branded.html)/', $filename, $matches, PREG_SET_ORDER, 0);
  $currentFile = $matches[0][1];
  $folder = str_replace($currentFile, '', $filename);

  // Get brand from filename
  preg_match_all('/.*\/(.*)_branded.html/', $filename, $matches, PREG_SET_ORDER, 0);
  $brand = $matches[0][1];

  // Variables to be set
  $fontFamily;
  $textColor;
  $backgroundColor;
  $borderColor;

  // Get Background Colour
  preg_match_all('/"linkColour": "(.*?)"/', $template, $matches, PREG_SET_ORDER, 0);

  if($brand === 'TPK'){
    $matches[0][1] = '#474843';
  } else if(($brand === 'proper_pubs_BNO') || ($brand === 'proper_pubs_community') || ($brand === 'proper_pubs_sports')){
    $matches[0][1] = '#BC9418';
  } else if($brand === 'slug_and_lettuce'){
    $matches[0][1] = '#CADB2A';
  } else if($brand === 'classic_inns'){
    $matches[0][1] = '#839678';
  }

  $backgroundColor = "background-color: " . $matches[0][1] . ";";
  $borderColor = "border-color: " . $matches[0][1] . ";";

  // Get Text Colour
  $textColor;
  if($brand === 'TPK'){
    $textColor = '#F2D2A3';
  } else if(($brand === 'proper_pubs_BNO') || ($brand === 'proper_pubs_community') || ($brand === 'proper_pubs_sports')){
    $textColor = '#323232';
  } else if($brand === 'slug_and_lettuce'){
    $textColor = '#343642';
  } else if($brand === 'classic_inns'){
    $textColor = '#fff';
  }
  $textColor = "color: " . $textColor . ";";

  // Get Font Fomily
  preg_match_all('/"h1FontFamily": "(.*?)"/', $template, $matches, PREG_SET_ORDER, 0);
  $fontFamily = "font-family: " . $matches[0][1] . ";";

  // Insert variables into basic style string
  $styleInsert = str_replace("[[FONT_FAMILY_HERE]]", $fontFamily, $basicStyles);
  $styleInsert = str_replace("[[TEXT_COLOR_HERE]]", $textColor, $styleInsert);
  $styleInsert = str_replace("[[BACKGROUND_COLOR_HERE]]", $backgroundColor, $styleInsert);
  $styleInsert = str_replace("[[BORDER_COLOR_HERE]]", $borderColor, $styleInsert);

  // Insert style into venue button
  $venueButton = str_replace("[[STYLE_HERE]]", $styleInsert, $basicButton);

  // Insert link text and link
  $tableBookingButton = str_replace("[[TEXT_HERE]]", $tableBookingText, $venueButton);
  $tableBookingButton = str_replace("[[LINK_HERE]]", $tableBookingLink, $tableBookingButton);

  $partyBookingButton = str_replace("[[TEXT_HERE]]", $partyBookingText, $venueButton);
  $partyBookingButton = str_replace("[[LINK_HERE]]", $partyBookingLink, $partyBookingButton);

  $preOrderButton = str_replace("[[TEXT_HERE]]", $preOrderText, $venueButton);
  $preOrderButton = str_replace("[[LINK_HERE]]", $preOrderLink, $preOrderButton);

  $menuButton = str_replace("[[TEXT_HERE]]", $menuText, $venueButton);
  $menuButton = str_replace("[[LINK_HERE]]", $menuLink, $menuButton);

  $competitionButton = str_replace("[[TEXT_HERE]]", $competitionText, $venueButton);
  $competitionButton = str_replace("[[LINK_HERE]]", $feedbackLink, $competitionButton);

  $feedbackButton = str_replace("[[TEXT_HERE]]", $feedbackText, $venueButton);
  $feedbackButton = str_replace("[[LINK_HERE]]", $feedbackText, $feedbackButton);

  $callBackButton = str_replace("[[TEXT_HERE]]", $callBackText, $venueButton);
  $callBackButton = str_replace("[[LINK_HERE]]", $callBackLink, $callBackButton);

  // Write buttons to file
  $file = $folder . 'bespoke_blocks/' . $brand . '_table_booking_button.html';
  file_put_contents($file, $tableBookingButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_party_booking_button.html';
  file_put_contents($file, $partyBookingButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_pre_order_button.html';
  file_put_contents($file, $preOrderButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_menu_button.html';
  file_put_contents($file, $menuButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_competition_button.html';
  file_put_contents($file, $competitionButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_feedback_button.html';
  file_put_contents($file, $feedbackButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_call_back_button.html';
  file_put_contents($file, $callBackButton);

  // Generate Demo Code
  $insert = $basicSpacer . $tableBookingButton . $basicSpacer . $partyBookingButton . $basicSpacer . $preOrderButton . $basicSpacer . $menuButton . $basicSpacer . $competitionButton . $basicSpacer . $feedbackButton . $basicSpacer . $callBackButton . $basicSpacer;
  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  //Remove comments
  $output = preg_replace('/\{.*?\}/ms', '', $output);
  // $output = preg_replace('/\<!--.*?\-->/ms', '', $output);

  if($brand === 'TPK'){
    $output = preg_replace('/\$dynamic3\$/', '1110555', $output);
  } else if($brand === 'classic_inns'){
    $output = preg_replace('/\$dynamic3\$/', '3500635', $output);
  } else if($brand === 'slug_and_lettuce'){
    $output = preg_replace('/\$dynamic3\$/', '1110055', $output);
  } else if($brand === 'proper_pubs_BNO'){
    $output = preg_replace('/\$dynamic3\$/', '1111299', $output);
  } else if($brand === 'proper_pubs_community'){
    $output = preg_replace('/\$dynamic3\$/', '1111299', $output);
  } else if($brand === 'proper_pubs_sports'){
    $output = preg_replace('/\$dynamic3\$/', '1111299', $output);
  }

  echo $output;

  // Save Demo Code Top File
  $file = '../client.demo/buttons/inner/' . $brand . '_buttons.html';
  file_put_contents($file, $output);
}


?>
