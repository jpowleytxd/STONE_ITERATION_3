<?php
ini_set('max_execution_time', 3000);
include 'common.php';

$saveToFile = true;
$returnString = null;

foreach(glob('../sites/*/templates/*_branded.html') as $filename){
  if(strpos($filename, 'proper_pubs') === false){
    $template = file_get_contents($filename);
    $brand = preg_replace('/.*?\/.*?\/(.*?)\/.*/', '$1', $filename);

    $email ="Welcome 2 + 7 Days (Scot)";

    $welcomeRows = null;
    $table = 'copy_iteration3_' . $brand;
    $initialQuery = "SELECT * FROM `" . $table . "` WHERE `email` = '" . $email . "'";
    $rows = databaseQuery($initialQuery);
    foreach($rows as $key => $row){
      $welcomeRows = $row;
      break;
    }

    //Get Background Color
    preg_match('/"contentBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
    $color = $matches[1][0];
    $textColor = textColor($color);

    preg_match_all('/"paragraphFont": "(.*)"/', $template, $matches);
    $font = $matches[1][0];

    //Prep Heading
    $heading = file_get_contents('../sites/' . $brand . '/bespoke_blocks/' . $brand . '_heading.html');
    $heading = str_replace('Heading goes here', $welcomeRows[4], $heading);
    $heading = str_replace('align="left"', 'align="center"', $heading);
    $heading = marginBuilder($heading);
    preg_match('/<h1.*?style="(.*?)".*?>/', $heading, $matches, PREG_OFFSET_CAPTURE);
    $headingStyle = $matches[1][0];
    $headingStyleNew = $headingStyle . ' font-size: 24px;';
    $heading = str_replace($headingStyle, $headingStyleNew, $heading);

    //Prep Images
    $image = file_get_contents('../sites/_defaults/image.html');
    $promo = $image;
    $image = str_replace('http://img2.email2inbox.co.uk/editor/fullwidth.jpg', getURL($brand, 'welcome_2_scot.png'), $image);

    //Prep Spacer
    $emptySpacer = file_get_contents('../sites/_defaults/basic_spacer.html');
    $largeSpacer = str_replace('<td align="center" height="20" valign="middle"></td>', '<td align="center" height="40" valign="middle"></td>', $emptySpacer);
    $lineSpacer = lineSpacerBuild($brand);

    //Prep All Text
    preg_match('/"paragraphColour": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
    $color = $matches[1][0];
    $textColor = $color;
    $basicText = file_get_contents('../sites/_defaults/text.html');
    $textOne = $textTwo = $basicText;

    //Prep Text One
    $welcomeRows[5] = str_replace('"', '', $welcomeRows[5]);
    $welcomeRows[5] = str_replace("'", '&apos;', $welcomeRows[5]);
    $textOne = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $welcomeRows[5], $textOne);
    $styleInsert = 'style="Margin-top: 15px; Margin-bottom: 15px; color: ' . $textColor . ';font-weight: normal; font-family: ' . $font . ';"';
    $textOne = preg_replace('/##(.+?)##/m', '<p ' . $styleInsert . '>$1</p>', $textOne);
    $styleInsert = 'style="color: ' . $textColor . ';font-weight: normal; font-family: ' . $font . ';"';
    $textOne = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textOne);
    $textOne = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textOne);
    $textOne = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textOne);

    //Prep Text Two
    preg_match('/"paragraphColour": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
    $color = $matches[1][0];
    $textColor = $color;
    $welcomeRows[7] = str_replace('"', '', $welcomeRows[7]);
    $textTwo = str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sodales vehicula tellus pellentesque malesuada. Integer malesuada magna felis, id rutrum leo volutpat eget. Morbi finibus et diam in placerat. Suspendisse magna enim, pharetra at erat vel, consequat facilisis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla est velit, lobortis eu tincidunt sit amet, semper et lorem.', $welcomeRows[7], $textTwo);
    $styleInsert = 'style="Margin-top: 15px; Margin-bottom: 15px; color: ' . $textColor . ';font-weight: normal; font-family: ' . $font . ';"';
    $textTwo = preg_replace('/##(.+?)##/m', '<p ' . $styleInsert . '>$1</p>', $textTwo);
    $linkInsert = '<a href="http://stonegateemail.co.uk/$dynamic3$/website" style="color: ' . $textColor . '; text-decoration: underline; font-weight: bold;"><span style="color: ' . $textColor . '; text-decoration: underline; font-weight: bold;">click here</span></a>';
    $textTwo = str_replace('click here', $linkInsert, $textTwo);
    $styleInsert = 'style="color: ' . $textColor . ';font-weight: normal; font-family: ' . $font . ';"';
    $textTwo = str_replace('<td class="text" align="left" valign="0">', '<td class="text" align="center" valign="0" ' . $styleInsert . '>', $textTwo);
    $textTwo = str_replace('<tr>', '<tr><td align="center" width="30"></td>', $textTwo);
    $textTwo = str_replace('</tr>', '<td align="center" width="30"></td></tr>', $textTwo);

    //Get terms color
    preg_match('/"emailBackground": "(.*)"/', $template, $matches, PREG_OFFSET_CAPTURE);
    $color = $matches[1][0];
    $textColor = textColor($color);

    //Build terms
    $terms = termsBuilder($welcomeRows[8]);
    $styleInsert = 'style="font-size: 11px; color: ' . $textColor . '"';
    $terms = preg_replace('/<td valign="top">/', '<td valign="top" align="center" ' . $styleInsert . '>', $terms);

    $insert = $image . $largeSpacer . $heading . $emptySpacer . $textOne .  $emptySpacer . $lineSpacer . $emptySpacer . $textTwo . $largeSpacer;
    $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
    $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

    $search = "/<!-- terms insert -->/";
    $output = preg_replace($search, $terms, $output);

    $append ="welcome_7_days_scot";
    $path = "pre_made";

    $save = $saveToFile;

    sendToFile($output, $path, $append, $brand, '.html', $save);

    // print_r($output);
    $returnString .= $output;
  }
}

echo $returnString;
 ?>
