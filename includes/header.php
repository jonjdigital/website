<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/db.php";
include $_SERVER['DOCUMENT_ROOT']."/includes/constants.php";
include $_SERVER['DOCUMENT_ROOT']."/includes/functions.php";
//var_dump($user_con);

###check maintenance config value if 1 show maintenance page on live. if 0 show normal###
$maintenance = getMaintVal();
if(($maintenance ==1) && ($ip != "127.0.0.1")){
    header($_SERVER['HTTP_ORIGIN']."/maintenance.php");
}
?>
<!DOCTYPE HTML>
<!--
	Future Imperfect by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>JonJDigital - Official Site</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />



	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
<!--						<h1><a href="/">JonJDigital</a></h1>-->
						<nav class="links">
							<ul>
                                                            <li><a href="/">Home</a></li>
<!--								<li><a href="#">Ipsum</a></li>
								<li><a href="#">Feugiat</a></li>
								<li><a href="#">Tempus</a></li>
								<li><a href="#">Adipiscing</a></li>-->
							</ul>
						</nav>
						<nav class="main">
							<ul>
<!--								<li class="search">
									<a class="fa-search" href="#search">Search</a>
									<form id="search" method="get" action="#">
										<input type="text" name="query" placeholder="Search" />
									</form>
								</li>-->
								<li class="menu">
									<a class="fa-bars" href="#menu">Menu</a>
								</li>
							</ul>
						</nav>
					</header>

				<!-- Menu -->
					<section id="menu">
                                            <hr>
<!--						 Search 
							<section>
								<form class="search" method="get" action="#">
									<input type="text" name="query" placeholder="Search" />
								</form>
							</section>-->

						<!-- Links -->
							<section>
								<?php include $_SERVER['DOCUMENT_ROOT']."/includes/navLinks.php"; ?>
							</section>

						<!-- Actions -->
							<section>
								<ul class="actions stacked">
									<li><a href="/login.php" class="button large fit">Log In</a></li>
								</ul>
								<ul class="actions stacked">
									<li><a href="/user/create.php" class="button large fit">Register</a></li>
								</ul>
                                <?php if($ip = "127.0.0.1"){?>
								<ul class="actions stacked">
									<li><a href="/session_debugging.php" class="button large fit">Session Details</a></li>
								</ul>
                                <?php } ?>
							</section>

					</section>

				<!-- Main -->
					<div id="main">
