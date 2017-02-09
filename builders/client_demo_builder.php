<?php
ini_set('max_execution_time', 3000);
include 'common.php';

function getStyle($brand){
    $regular = '<style type="text/css">
                body{padding: 0; margin: 0;}
                #outlook a{padding: 0;}
                .ReadMsgBody{width: 100%;}
                .ExternalClass{width: 100%;}
                .ExternalClass, .ExternalClass div, .ExternalClass font, .ExternalClass p, .ExternalClass span, .ExternalClass td{line-height: 100%;}
                a, body, table, td{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
                table, td{mso-table-lspace: 0; mso-table-rspace: 0;}
                img{-ms-interpolation-mode: bicubic;}
                body{margin: 0;padding: 0;}
                img{border: 0; height: auto; line-height: 100%; outline: 0; text-decoration: none;}
                table{border-collapse: collapse !important}
                body{height: 100%!important; margin: 0; padding: 0; width: 100% !important;}
                .appleBody a{color: #68440a; text-decoration: none;}
                .appleFooter a{color: #999; text-decoration: none;}
                .img-max-ninety{max-width: 90% !important;}
                body{font-size: inherit; color: #000; font-weight: 400; line-height: normal;}
                h1, h2, h3, h4{font-weight: 700; font-size: 100%;}
                p{margin: 0; padding: 0;}
                ol, p, ul{margin: 0; padding: 0;}
                ol{list-style-type: square; list-style-position: inside; margin-left: 10px;}
                @media screen and (max-width: 625px){
                    .wrapper{width: 100% !important;}
                    .mob-center{text-align: center !important}
                    .mob-left{text-align: left !important;}
                    .mob-right{text-align: right !important;}
                    .mobile-hide { display: none !important; height: 0px !important; overflow: hidden;}
                    .desk-hide { display: block !important;}
                    .desk-footer-hide{display: block !important;}
                    td[class=logo]{text-align: left; padding: 20px 0 20px 0 !important;}
                    td[class=logo] img{margin: 0 auto !important;}
                    td[class=mobile-hide]{display: none;}
                    img[class=mobile-hide]{display: none!important;}
                    img[class=img-max]{max-width: 100% !important; width: 100% !important; height: auto !important;}
                    .responsive-table{width: 100% !important;}
                    td[class=padding]{padding: 10px 5% 15px 5% !important;}
                    td[class=padding-copy]{padding: 10px 5% 10px 5% !important; text-align: center;}
                    td[class=padding-meta]{padding: 30px 5% 0 5% !important; text-align: center;}
                    td[class=no-pad]{padding: 0 0 20px 0 !important;}
                    td[class=no-padding]{padding: 0 !important;}
                    td[class=section-padding]{padding: 50px 15px 50px 15px !important}
                    td[class=section-padding-bottom-image]{padding: 50px 15px 0 15px !important}
                    td[class=mobile-wrapper]{padding: 10px 5% 15px 5% !important}
                    .mobile-button-container{margin: 0 auto; width: 100% !important}
                    a[class=mobile-button]{width: 90% !important; padding: 15px !important; border: 0 !important; font-size: 16px !important;}
                      [class=gmail-fix]{display: none!important}
                      .mob-no-border{border: 0px !important;}
                      .mob-width{width: 100% !important;}
                    }

                    p{font-family: Arial, sans-serif !important;}
                    [style*="Lato"]{font-family: "Lato", Arial, sans-serif !important;}
                    [style*="Oswald"]{font-family: "Oswald", Arial, sans-serif !important;}
                    [style*="Roboto Slab"]{font-family: "Roboto Slab", Arial, sans-serif !important;}
                    @media screen and(max-width: 600px){
                      .responsive-table{width: 100% !important; height: 100% !important;}
                      .mobile-hide{display: none !important;}
                    }
                    </style>';

    return $regular;
}

function htmlBuilder($content, $brand){
  $topInsert = '<!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link href="https://fonts.googleapis.com/css?family=lato:300,400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Oswald:300,400" rel="stylesheet">
        ' . getStyle($brand) . '
      </head>
      <body>';

    $bottomInsert = '</body>
      </html>';

  $output = $topInsert . $content . $bottomInsert;

  return $output;
}

foreach(glob("../pre_made/*/*.html") as $filename){
  $template = file_get_contents($filename);

  //Get brand and template type
  preg_match_all('/.*?\/.*?\/(.*?)\/(.*?).html/', $filename, $matches);
  $brand = $matches[1][0];
  $templateType = $matches[2][0];

  //Remove comments
  $output = preg_replace('/\{.*?\}/ms', '', $template);
  $output = preg_replace('/\<!--.*?\-->/ms', '', $output);

  //Build html around template
  $output = htmlBuilder($output, $brand);

  $append = $templateType;
  $path = "client.demo";
  $save = false;

  sendToFile($output, $path, $append, $brand, '.html', $save);

  print_r($output);
}

 ?>
