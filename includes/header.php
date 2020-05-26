<?php
include $_SERVER['DOCUMENT_ROOT']."/includes/db.php";
include $_SERVER['DOCUMENT_ROOT']."/includes/constants.php";
include $_SERVER['DOCUMENT_ROOT']."/includes/functions.php";
//var_dump($user_con);

###check maintenance config value if 1 show maintenance page on live. if 0 show normal###
$maintenance = getMaintVal();
if((($maintenance ==1)&&($_SERVER['REQUEST_URI']!="/interested.php"))){
    header("Location: /interested.php");
    /*$location = $_SERVER['HTTP_HOST']."/maintenance.php";
    // create a new cURL resource
    $ch = curl_init();

// set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $location);
    curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
    curl_exec($ch);

// close cURL resource, and free up system resources
    curl_close($ch);*/
}
session_start();
?>
<!DOCTYPE HTML>
<!--
	Future Imperfect by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>JonJDigital - Official Site - Just another digital community.</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
        <link rel="icon" type="image/png" href="../icon.png">

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
                                <?php
                                if(isset($_COOKIE['uname'])) {
                                    if (in_array("Content Creator", $_SESSION['roles'])) {
                                        echo "<li><a href='/post/dashboard.php'>Post Dashboard</a></li>";
                                    }
                                }
                                ?>
<!--							<li><a href="#">Ipsum</a></li>
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
                        <img src="../icon.png" alt="Logo" style="width:75%">

								<?php
                                if(isset($_COOKIE['uname'])){
                                    echo "<section>";
                                    echo "<p>Username: " . $_COOKIE['uname']."</p>";
                                    echo "<p>Roles:<ul>";
                                        foreach($_SESSION['roles'] as $role){
                                            echo "<li>".$role."</li>";
                                        }
                                    echo "</ul>";
                                    echo "</section>";
                                }
                                ?>

						<!-- Links -->
							<section>
								<?php include $_SERVER['DOCUMENT_ROOT']."/includes/navLinks.php"; ?>
							</section>

						<!-- Actions -->
							<section>
                                <?php if(!isset($_COOKIE['user_id'])){?>
                                    <ul class="actions stacked">
                                        <li><a href="/login.php" class="button large fit">Log In</a></li>
                                    </ul>
                                    <ul class="actions stacked">
                                        <li><a href="/user/create.php" class="button large fit">Register</a></li>
                                    </ul>
                                <?php }else{?>
                                    <ul class="actions stacked">
                                        <li><a href="/logout.php" class="button large fit">Log Out</a></li>
                                    </ul>
                                <?php }
                                if($ip == "127.0.0.1"){?>
								<ul class="actions stacked">
									<li><a href="/session_debugging.php" class="button large fit">Session Details</a></li>
								</ul>
                                <?php } ?>
							</section>

					</section>

				<!-- Main -->
					<div id="main">
