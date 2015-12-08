<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_US">
<html>
    <head>
        <title>WeSupport Chat - Supporting Clients</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT" />
        <meta http-equiv="Last-Modified" content="<?=gmdate("D, d M Y H:i:s") . " GMT";?>" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Cache" content="no-cache" />
        <meta http-equiv="imagetoolbar" content="no" />
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10,chrome=1" />
        <meta name="rating" content="general" />
        <meta name="author" content="Sandro Alves Peres" />
        <meta name="title" content="WeSupport Chat - Supporting Clients" />
        <meta name="owner" content="http://www.zend.com/en/yellow-pages/ZEND022656" />
        <meta name="robots" content="noindex,nofollow" />
        <meta name="googlebot" content="noindex,nofollow" />

        <!-- Mobile device meta tags -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=4" />
        <meta name="x-blackberry-defaultHoverEffect" content="false" />
        <meta name="HandheldFriendly" content="true" />
        <meta name="MobileOptimized" content="240" />

        <base href="<?=URL::baseUrl();?>" />
        <link rel="shortcut icon" href="<?=URL::baseUrl();?>/public/images/chat.png" type="image/png" />
        <link rel="apple-touch-icon" href="<?=URL::baseUrl();?>/public/images/logo.png" type="image/png" />
        <link rel="stylesheet" href="<?=URL::baseUrl();?>/public/css/fields.css" type="text/css" media="all" />
        <link rel="stylesheet" href="<?=URL::baseUrl();?>/public/css/chat.css" type="text/css" media="all" />
        <link rel="stylesheet" href="<?=URL::baseUrl();?>/public/css/message-ui.css" type="text/css" media="all" />
        <script src="<?=URL::baseUrl();?>/public/js/jquery-1.8.3.min.js" type="text/javascript"></script>
        <script src="<?=URL::baseUrl();?>/public/js/common.js" type="text/javascript"></script>

        <? if( Config::CHECK_SUPPORT_INACTIVITY ){ ?>

            <script src="<?=URL::baseUrl();?>/public/js/check-support-inactivity.js" type="text/javascript"></script>

        <? } ?>

        <?=@$widgets;?>

        <!--[if lt IE 8]>
        <link rel="stylesheet" href="<?=URL::baseUrl();?>/public/css/fuck-ie.css" type="text/css" media="all" />
        <style type="text/css">.clearfix { display: inline-block; }</style>
        <![endif]-->

        <!--[if lte IE 6]>
        <style type="text/css">.clearfix { height: 1%; }</style>
        <![endif]-->

        <?=@$css;?>
        <?=@$js;?>
    </head>

    <? flush(); ?>

    <noscript>
        <div class="noscript">
            This application requires JavaScript enabled to work properly<br />
            Your browser doesn't have this function or it's unavailable
        </div>
    </noscript>

    <body class="body">

        <div id="loading">
            <div class="loading-bg"></div>
            <div class="loading-img"></div>
        </div>

        <div class="header">

            <div class="logo">
                <img src="<?=URL::baseUrl();?>/public/images/logo.png" alt="" />
            </div>

            <h1>Your company support</h1>
            <p>How can we help you?</p>

        </div>

        <div class="body">

            <?=@$body;?>

        </div>

        <div class="footer">
            <span>&reg; WeSupport Chat - Supporting Clients</span>
        </div>

    </body>
</html>