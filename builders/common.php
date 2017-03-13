<?php

/*........................*/
/*Send To output file*/
/*........................*/
function sendToFile($output, $path, $append, $serverName, $fileType, $send){
  //$output     => string for output file data
  //$path       => folder path to output location
  //$append     => filename base
  //$serverName => server version of brand name
  //$fileType   => file extension

  if($send == true){
    $dirName = '../' . $path;
    if(!is_dir($dirName)){
      mkdir($dirName, 0755);
    }

    $dirName = '../' . $path . '/' . $serverName;
    if(!is_dir($dirName)){
      mkdir($dirName, 0755);
    }

    file_put_contents(('../'. $path . '/' . $serverName . '/' . $append . $fileType), $output);
  }
}

/*........................*/
/*Query Database Returns array*/
/*........................*/
function databaseQuery($query){
  //Define Connection
  static $connection;

  //Attempt to connect to the database, if connection is yet to be established.
  if(!isset($connection)){
    //Load congig file
    $config = parse_ini_file('../config.ini');
    $connection = mysqli_connect('localhost', $config['username'], $config['password'], $config['dbname']);
  }

  //Arrays to store all retrieved records
  $rows = array();
  $result = null;

  //Connection error handle
  if($connection === false){
    print('Connection Error');
    return false;
  } else{
    //Query the database
    $result = mysqli_query($connection, $query);

    //IF query failed, return 'false'
    if($result === false){
      print('Query Failed');
      return false;
    }

    //Fetch all the rows in the Array
    while($row = mysqli_fetch_row($result)){
      $rows[] = $row;
    }
    return $rows;
  }
}

/*........................*/
/*Detect text color*/
/*........................*/
function textColor($color){
  $r = hexdec(substr($color,1,2));
  $g = hexdec(substr($color,3,2));
  $b = hexdec(substr($color,5,2));

  if ($r + $g + $b > 500){
    //return $r + $g + $b;
      return '#000';
    } else {
      //return $r + $g + $b;
      return '#fff';
  }
}

/*........................*/
/*Retrieve URL for promo image*/
/*........................*/
function getURL($serverName, $image){
  $urlStart = 'http://img2.email2inbox.co.uk/2016/stonegate/templates/';
  $urlEnd = '/' . $image;

  return $urlStart . $serverName . $urlEnd;
}

/*........................*/
/*Build Margins around element*/
/*........................*/
function marginBuilder($block){
  $blockStart = '<table border="0" cellpadding="0" cellspacing="0" width="600" class="block block1Column structureBlock wrapper" data-id="block1Column" style="width:600px;width:600px;width:600px;">
      <tr>
          <td align="center" valign="0">
              <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table blockArea block" data-id="blockArea" style="width:600px;width:600px;width:600px;">
                  <tr><td align="center" width="30"></td>
                      <td valign="top">';
  $blockEnd = '</td><td align="center" width="30"></td>
  </tr>
  </table>
  </td>
  </tr>
  </table>';

  $block = $blockStart . $block .$blockEnd;
  return $block;
}

/*........................*/
/*Build Spacer With dotted line within*/
/*........................*/
function lineSpacerBuild($parentFolder){
  $spacer = file_get_contents('../sites/_defaults/spacer.html');

  $template = file_get_contents('../sites/' . $parentFolder . '/templates/' . $parentFolder . '_branded.html');
  preg_match('/"emailBackground": "(.*)"/', $template, $matches);
  $color = $matches[1];

  if($parentFolder === 'TPK'){
    $color= "#474843";
  }

  $style='border-top: 1px dotted ' . $color . ';';

  $spacer = preg_replace('/background-color:.*?;/', '', $spacer);
  $spacer = preg_replace('/border-top:.*?;/', $style, $spacer);

  return $spacer;
}

/*........................*/
/*T&C's builder*/
/*........................*/
function termsBuilder($terms){
  $blockStart = '<table border="0" cellpadding="0" cellspacing="0" width="600" class="block block1Column structureBlock wrapper" data-id="block1Column" style="width:600px;">
      <tr>
          <td align="center" valign="0">
              <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table blockArea block" data-id="blockArea" style="width:600px;">
                  <tr><td align="center" width="30"></td>
                      <td valign="top">';
  $blockEnd = '</td><td align="center" width="30"></td>
  </tr><tr><td height="30" valign="0"></td></tr>
  </table>
  </td>
  </tr>
  </table>';

  $block = $blockStart . $terms .$blockEnd;
  return $block;
}

/*........................*/
/*Get Hero Image for template*/
/*........................*/
function getHeroImageURL($brand){
  $urlStart = 'http://img2.email2inbox.co.uk/2016/stonegate/templates/';
  $urlEnd = '/hero.jpg';

  return $urlStart . $brand . $urlEnd;
}

/*........................*/
/*Voucher Builder*/
/*........................*/
function buildVoucher($contentRows, $brand){
  if(($contentRows[10] !== null) && ($contentRows[10] !== '')){
    $voucherInstructions = $contentRows[9];
    $voucher = file_get_contents('../sites/' . $brand . '/bespoke_blocks/' . $brand . '_voucher.html');
    $voucherSearch = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
    $voucher = str_replace($voucherSearch, $voucherInstructions, $voucher);
    $voucher = str_replace('$vouchercode$', $contentRows[10], $voucher);
    $search = '/<!--valid_from_start-->\s*.*\s*.*\s*<!--valid_from_end-->/';
    $voucher = preg_replace($search, '', $voucher);
    $search = '/<!--customer_start-->\s*.*\s*.*\s*<!--customer_end-->/';
    $voucher = preg_replace($search, '', $voucher);
    // $voucher = marginBuilder($voucher);

    return $voucher;
  } else{
    $voucherInstructions = $contentRows[9];
    $voucher = file_get_contents('../sites/' . $brand . '/bespoke_blocks/' . $brand . '_voucher.html');
    $voucherSearch = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
    $voucher = str_replace($voucherSearch, $voucherInstructions, $voucher);
    // $voucher = marginBuilder($voucher);

    return $voucher;
  }
}

 ?>
