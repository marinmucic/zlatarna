<?php session_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?php // Use a default page title if one wasn't provided...
        if (isset($page_title)) {
            echo $page_title;
        } else {
            echo 'Administration';
        } ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css" media="screen">
</style>
<!--[if lt IE 7]>
<script type="text/javascript" src="/js/ie_png.js"></script>
<script type="text/javascript">
ie_png.fix('.png, .logo h1, .box .left-top-corner, .box .right-top-corner, .box .left-bot-corner, .box
.right-bot-corner, .box .border-left, .box .border-right, .box .border-top, .box .border-bot, .box
.inner, .special dd, #contacts-form input, #contacts-form textarea');
</script>
<![endif]-->
</head>
<body id="page1">
<!-- header -->
<div id="header">
<div class="container">

<ul class="admin-nav">
<!-- MENU -->
<li><a href="../index.php">Perla</a>
    </li><li><a href="index.php">Admin Home</a>
    </li><li><a href="#">Products</a>
    </li><li><a href="create_sales.php">Sales</a>
    </li><li><a href="view_orders.php">Orders</a>
    </li><li><a href="#">Customers</a></li>
<!-- END MENU -->
</ul>
</div>
</div>
<!-- content -->
<div id="content">
<div class="">