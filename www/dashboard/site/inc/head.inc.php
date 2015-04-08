<?php
    include("Constants.php");
?>

<!doctype html>
<html xmlns:og="http://ogp.me/ns#" lang="pt-br">
<head>
	<meta charset="utf-8" />

	<title><?php echo _TITLE ?></title>

	<!-- CSS -->
    <link rel="stylesheet" type="text/css" media="print" href="dist/css/print.css">
    <link rel="stylesheet" type="text/css" media="all" href="dist/css/styles.combined.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo _OTHERCSS . 'iThing.css'?>">

	<!-- HUMANS -->
    <link type="text/plain" rel="author" href="humans.txt" />

    <!-- FAVICON E TOUCH ICON -->
    <link rel="icon" href="favicon.png" type="image/png" sizes="16x16">
    <link rel="apple-touch-icon" sizes="57x57" href="./apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./apple-touch-icon-ipad.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="./apple-touch-icon-iphone4.png" />

    <!-- OPEN GRAPH -->
    <meta property='og:title' content='Saúde Na Copa 2014 | Dashboard' />
    <meta property='og:description' content='O Aplicativo Saúde na Copa é um projeto de vigilância participativa em saúde durante a Copa do Mundo FIFA 2014' />
    <meta property='og:url' content='http://www.saudenacopa.epitrack.com.br' />
    <meta property='og:image' content='http://www.saudenacopa.epitrack.com.br/dashboard/site/dist/images/facebook.png'/>
    <meta property='og:type' content='website' />
    <meta property='og:site_name' content='Saúde Na Copa 2014' />

    <!-- VIEWPORT -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!--[if lte IE 8]>
        <link rel="stylesheet" href="dist/css/ie.css">
        <script src="libs/js/create-elements.js"></script>
    <![endif]-->

    <script>
        // inserindo tracking events
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-50378086-3']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        // google analytics
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-50378086-3', 'http://www.saudenacopa.epitrack.com.br/dashboard/site/');
        ga('send', 'pageview');
    </script>
</head>