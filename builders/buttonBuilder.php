<?php

include("common.php");

// a table booking      -> /table-booking
// a party booking      -> /party-booking
// a pre-order          -> /party-booking
// view menus           -> /drinks
// enter a competition  -> /website
// find us              -> /contact
// request a call back. -> /party-enquiry

// Define hyperlinks for buttons
$tableBookingLink = "http://stonegateemail.co.uk/\$dynamic3\$/table-booking";
$partyBookingLink = "http://stonegateemail.co.uk/\$dynamic3\$/party-booking";
$preOrderLink = "http://stonegateemail.co.uk/\$dynamic3\$/party-booking";
$menuLink = "http://stonegateemail.co.uk/\$dynamic3\$/drinks";
$competitionLink = "http://stonegateemail.co.uk/\$dynamic3\$/website";
$findUsLink = "http://stonegateemail.co.uk/\$dynamic3\$/contact";
$callBackLink = "http://stonegateemail.co.uk/\$dynamic3\$/party-enquiry";

// Define text for buttons
$tableBookingText = "Book My Table";
$partyBookingText = "Book My Party";
$preOrderText = "Pre Order Now";
$menuText = "View Menus Now";
$competitionText = "Enter Now";
$findUsText = "Find Us";
$callBackText = "Request A Call";


// Get default button from file
$basicButton = file_get_contents("../sites/_defaults/button.html");

// Get default spacer from file
$basicSpacer = file_get_contents("../sites/_defaults/basic_spacer.html");

// Styles for button insertion
$basicStyles = "width: 150px; text-align:center; font-size: 14px; [[FONT_FAMILY_HERE]] font-weight: normal; [[TEXT_COLOR_HERE]] text-decoration: none; [[BACKGROUND_COLOR_HERE]] border-top-width: 15px; border-bottom-width: 15px; border-left-width: 25px; border-right-width: 25px; border-style: solid; [[BORDER_COLOR_HERE]] border-radius: 0px; -webkit-border-radius: 0px; -moz-border-radius: 0px; display: inline-block;";

// Foreach brand in sites folder
foreach(glob('../sites/*/templates/*_branded.html') as $filename){
  $template = file_get_contents($filename);

  // Get current brand name
  preg_match_all('/.*sites\/(.*?)\//', $filename, $matches, PREG_SET_ORDER, 0);
  $brand = $matches[0][1];

  // Variables to be set
  $fontFamily;
  $textColor;
  $backgroundColor;
  $borderColor;

  // Get the 1 Col Bespoke Block for this brand
  $location = "../sites/{$brand}/bespoke_blocks/{$brand}_1_col.html";
  $colBlock = file_get_contents($location);

  // Get the link colour from this block and set the button colours
  preg_match_all('/"buttonColour": "(.*?)"/', $colBlock, $matches, PREG_SET_ORDER, 0);
  $backgroundColor = "background-color: " . $matches[0][1] . ";";
  $borderColor = "border-color: " . $matches[0][1] . ";";

  // Get Text Colour
  preg_match_all('/"buttonText": "(.*?)"/', $colBlock, $matches, PREG_SET_ORDER, 0);
  $textColor = $matches[0][1];
  $textColor = "color: " . $textColor . ";";

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
  $competitionButton = str_replace("[[LINK_HERE]]", $competitionLink, $competitionButton);

  $findUsButton = str_replace("[[TEXT_HERE]]", $findUsText, $venueButton);
  $findUsButton = str_replace("[[LINK_HERE]]", $findUsLink, $findUsButton);

  $callBackButton = str_replace("[[TEXT_HERE]]", $callBackText, $venueButton);
  $callBackButton = str_replace("[[LINK_HERE]]", $callBackLink, $callBackButton);

  // Write buttons to file
  $file = '../sites/' . $brand . '/' . 'bespoke_blocks/buttons/' . $brand . '_table_booking_button.html';
  file_put_contents($file, $tableBookingButton);

  $file = '../sites/' . $brand . '/' . 'bespoke_blocks/buttons/' . $brand . '_party_booking_button.html';
  file_put_contents($file, $partyBookingButton);

  $file = '../sites/' . $brand . '/' . 'bespoke_blocks/buttons/' . $brand . '_pre_order_button.html';
  file_put_contents($file, $preOrderButton);

  $file = '../sites/' . $brand . '/' . 'bespoke_blocks/buttons/' . $brand . '_menu_button.html';
  file_put_contents($file, $menuButton);

  $file = '../sites/' . $brand . '/' . 'bespoke_blocks/buttons/' . $brand . '_competition_button.html';
  file_put_contents($file, $competitionButton);

  $file = '../sites/' . $brand . '/' . 'bespoke_blocks/buttons/' . $brand . '_find_us_button.html';
  file_put_contents($file, $findUsButton);

  $file = '../sites/' . $brand . '/' . 'bespoke_blocks/buttons/' . $brand . '_call_back_button.html';
  file_put_contents($file, $callBackButton);

  // Generate Demo Code
  $insert = $basicSpacer . $tableBookingButton . $basicSpacer . $partyBookingButton . $basicSpacer . $preOrderButton . $basicSpacer . $menuButton . $basicSpacer . $competitionButton . $basicSpacer . $findUsButton . $basicSpacer . $callBackButton . $basicSpacer;
  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

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

  //Remove comments
  $output = preg_replace('/\{.*?\}/ms', '', $output);

  // Save Demo Code Top File
  $file = '../client.demo/buttons/' . $brand . '_buttons.html';
  file_put_contents($file, $output);

  echo $output;

}

 ?>
