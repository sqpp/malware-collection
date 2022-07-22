<?php
   	include('../config.php');
   	include('important/session.php');
   	include('important/information.php');
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$target_dir = "../images/uploads/";
		$target_file = $target_dir .basename($_FILES["fileToUpload"]["name"]);
		function RandomString($length) {
		    $keys = array_merge(range(0,9), range('a', 'z'));

		    $key = '';
		    for($i=0; $i < $length; $i++) {
		        $key .= $keys[mt_rand(0, count($keys) - 1)];
		    }
		    return $key;
		}
		$filename = RandomString("8");
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir.$filename.'.'.$imageFileType)) {
			echo 'File has been uploaded. Link:<br>';
			echo '<a href="'.$domain.'/images/uploads/'.$filename.'.'.$imageFileType.'">'.$domain.'/images/uploads/'.$filename.'.'.$imageFileType.'</a>';
			die();
		} else {
			echo 'An error has occured while uploading the file';
		    die();
		}
	}
?><!DOCTYPE html>
<html>
<head>
	<title>Admin Panel - <?php echo $sname ?></title>
	<link rel="icon" type="image/png" href="<?php echo $slogo ?>" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-0c38nfCMzF8w8DBI+9nTWzApOpr1z0WuyswL4y6x/2ZTtmj/Ki5TedKeUcFusC/k" crossorigin="anonymous">
	<meta name="theme-color" content="#<?php echo $scolor ?>">
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@" />
	<meta name="twitter:creator" content="@jekeltor" />
	<meta property="og:url" content="<?php echo $sdomain ?>" />
	<meta property="og:title" content="<?php echo $sname ?>" />
	<meta property="og:description" content="<?php echo $sdesc ?>" />
	<meta property="og:image" content="<?php echo $slogo ?>" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
	<style>
		body {
			display: flex;
			margin: 0;
			align-items: center;
			justify-content: center;
			font-family: 'Source Sans Pro', sans-serif;
		}

		.rotate {
			display: none;
			height: 100vh;
			width: 100%;
			position: fixed;
			background-color: #333940;
			font-size: 3vh;
			color: #fff;
			text-align: center;
			align-items: center;
			justify-content: center;
			z-index: 10;
		}

		.navbar {
			display: flex;
			position: absolute;
			left: 0;
			top: 0;
			height: 5vh;
			width: 100%;
			background-color: #333940;
			z-index: 3;
			align-items: center;
			justify-content: center;
		}

		.navbar .l {
			display: inline-flex;
			height: 5vh;
			width: 10%;
			align-items: center;
			justify-content: center;
		}

		.navbar .l h1 {
			margin: 0;
			padding: 0;
			font-size: 3vh;
			color: #fff;
			white-space: nowrap;
		}

		.navbar .r {
			display: inline-block;
			width: 90%;
			text-align: right;
			padding-right: 2.5%;
		}

		.navbar .r a {
			font-size: 3vh;
			color: #fff;
		}

		.left {
			display: inline-block;
			text-align: left;
			position: relative;
			width: 11%;
			background-color: #202429;
			padding: 7vh 2%;
			height: 86vh;
			transition: width 1s;
		}

		.left .top {
			text-align: center;
			margin-bottom: 5vh;
		}

		.left .top img {
			display: inline-block;
			height: 15vh;
			width: auto;
			transition: height 1s;
		}

		.left .top p {
			margin: 0;
			font-size: 1.75vh;
			color: #fff;
			font-weight: 700;
		}

		.left .section {
			margin-bottom: 7vh;
		}

		.left .section h1 {
			font-size: 1.75vh;
			color: #4A4D50;
			margin: 0;
			margin-bottom: 3vh;
		}

		.left .section .active {
			color: #fff !important;
		}

		.left .section a {
			display: block;
			font-size: 2.25vh;
			color: #676c70;
			text-decoration: none;
			font-weight: 700;
			transition: .1s ease;
			margin-bottom: 2vh;
		}

		.left .section a:hover {
			color: #fff;
			cursor: pointer;
		}

		.left .section a i {
			margin-right: 3%;
		}

		.left .bottom {
			position: absolute;
			bottom: 3vh;
			width: 70%;
			text-align: center;
		}

		.left .bottom #open {
			display: none;
		}

		.left .bottom a {
			font-size: 4vh;
			color: #fff;
		}

		.left .bottom a:hover {
			cursor: pointer;
		}

		.right {
			display: inline-block;
			width: 82%;
			height: 91vh;
			padding: 0 1.5%;
			padding-top: 9vh;
			overflow-y: auto;
			transition: width 1s;
		}

		.right .item h1 {
			font-size: 5vh;
			color: #000;
			margin: 0;
			margin-bottom: 1vh;
		}

		.right .item .overflow {
			width: 99.8%;
			overflow-x: auto;
			border: 3px solid #E9ECEF;
		}

		.right .item .overflow th {
			font-size: 2vh;
			border-right: 3px solid #E9ECEF;
		}

		.right .item .overflow td {
			border: 3px solid #E9ECEF;
			font-size: 1.75vh;
		}

		.right .item {
			display: none;
		}

		.activee {
			display: block !important;
		}

		@media screen and (max-width: 600px), (orientation : portrait) {
			.rotate {
				display: flex;
			}
		} 
	</style>
</head>
<body>
	<section class="rotate">
		To use this panel, please rotate your device.
	</section>
	<section class="navbar">
		<div class="l">
			<h1></h1>
		</div>
		<div class="r">
			<a href="<?php echo $sadminurl ?>/logout.php"><i class="fas fa-sign-out-alt"></i></a>
		</div>
	</section>
	<section class="left" id="left">
		<div class="top">
			<img src="<?php echo $slogo ?>" id="pfp" />
			<p id="welcome">Welcome, <?php echo ucfirst($login_session) ?></p>
		</div>
		<div class="section">
			<h1>CORE</h1>
			<a class="itemnav active onee" onclick="toggle_visibility('one');" href="?name=one"><i class="fas fa-cogs"></i> <span class="span">Site Settings</span></a>
			<a class="itemnav twoe" onclick="toggle_visibility('two');" href="?name=two"><i class="fas fa-users-cog"></i> <span class="span">Admin Users</span></a>
		</div>
		<div class="section">
			<h1>PAGES</h1>
			<a class="itemnav threee" onclick="toggle_visibility('three');" href="?name=three"><i class="fas fa-store"></i> <span class="span">Shop</span></a>
			<a class="itemnav foure" onclick="toggle_visibility('four');" href="?name=four"><i class="fas fa-user-shield"></i> <span class="span">Team</span></a>
			<a class="itemnav fivee" onclick="toggle_visibility('five');" href="?name=five"><i class="fas fa-star"></i> <span class="span">Reviews</span></a>
			<a class="itemnav sixe" onclick="toggle_visibility('six');" href="?name=six"><i class="far fa-envelope"></i> <span class="span">Contact</span></a>
			<a class="itemnav sevene" onclick="toggle_visibility('seven');" href="?name=seven"><i class="fas fa-percentage"></i> <span class="span">Discount Codes</span></a>
			<a class="itemnav eighte" onclick="toggle_visibility('eight');" href="?name=eight"><i class="fas fa-image"></i> <span class="span">Image Uploader</span></a>
		</div>
		<div class="bottom" id="bottom">
			<a onclick="myResize()" id="close"><i class="fas fa-chevron-circle-left"></i></a>
			<a onclick="myResize()" id="open"><i class="fas fa-chevron-circle-right"></i></a>
		</div>
	</section>
	<section class="right" id="right">
		<section class="item one activee">
			<h1>Site Information</h1>
			<div class="overflow"><?php
				// connect to the database
				include('../config.php');

				// get the records from the database
				if ($result = $mysqli->query("SELECT * FROM siteinfo"))
				{
				// display records if there are records to display
				if ($result->num_rows > 0)
				{
				// display records in a table
				echo "<table cellpadding='10' style='border-collapse:collapse'>";

				// set table headers
				echo "<tr style='border-bottom: 3px solid #E9ECEF;'><th>ID</th><th>Name</th><th>Domain</th><th>Logo</th><th>Color</th><th>Description</th><th>Twitter</th><th>Background Image</th><th>ReCaptcha Site Key</th><th>ReCaptcha Secret Key</th><th style='border-right: none'></th></tr>";

				while ($row = $result->fetch_object())
				{
				// set up a row for each record
				echo "<tr>";
				echo "<td style='border-left: none;'>" . $row->id . "</td>";
				echo "<td>" . $row->name . "</td>";
				echo "<td>" . $row->domain . "</td>";
				echo "<td>" . $row->logo . "</td>";
				echo "<td>" . $row->color . "</td>";
				echo "<td>" . $row->description . "</td>";
				echo "<td>" . $row->twitter . "</td>";
				echo "<td>" . $row->backgroundimage . "</td>";
				echo "<td>" . $row->sitekey . "</td>";
				echo "<td>" . $row->secretkey . "</td>";
				echo "<td style='border-right: none;'><a href='edit/record1.php?id=" . $row->id . "'>Edit</a></td>";
				echo "</tr>";
				}

				echo "</table>";
				}
				// if there are no records in the database, display an alert message
				else
				{
				echo "No results to display!";
				}
				}
				// show an error if there is an issue with the database query
				else
				{
				echo "Error: " . $mysqli->error;
				}

				// close database connection
				$mysqli->close();
			?></div>
		</section>
		<section class="item two">
			<h1>Admin Users</h1>
			<div class="overflow"><?php
				// connect to the database
				include('../config.php');

				// get the records from the database
				if ($result = $mysqli->query("SELECT * FROM admin"))
				{
				// display records if there are records to display
				if ($result->num_rows > 0)
				{
				// display records in a table
				echo "<table cellpadding='10' style='border-collapse:collapse; width:100%;'>";

				// set table headers
				echo "<tr><th>ID</th><th>Username</th><th style='border-right: none'></th></tr>";

				while ($row = $result->fetch_object())
				{
				// set up a row for each record
				echo "<tr>";
				echo "<td style='border-left: none;'>" . $row->id . "</td>";
				echo "<td>" . $row->username . "</td>";
				echo "<td style='border-right: none;'><a href='edit/delete.php?table=admin&id=" . $row->id . "'>Delete</a></td>";
				echo "</tr>";
				}

				echo "</table>";
				}
				// if there are no records in the database, display an alert message
				else
				{
				echo "No results to display!";
				}
				}
				// show an error if there is an issue with the database query
				else
				{
				echo "Error: " . $mysqli->error;
				}

				// close database connection
				$mysqli->close();

				?>
			</div>
		</section>
		<section class="item three">
			<h1>Shop</h1>
			<div class="overflow"><?php
				// connect to the database
				include('../config.php');

				// get the records from the database
				if ($result = $mysqli->query("SELECT * FROM shop"))
				{
				// display records if there are records to display
				if ($result->num_rows > 0)
				{
				// display records in a table
				echo "<table cellpadding='10' style='border-collapse:collapse;'>";

				// set table headers
				echo "<tr><th>ID</th><th>Order</th><th>Name</th><th>Price</th><th>Images</th><th>Features</th><th></th><th style='border-right: none'></th></tr>";

				while ($row = $result->fetch_object())
				{
				// set up a row for each record
				echo "<tr>";
				echo "<td style='border-left: none;'>" . $row->id . "</td>";
				echo "<td>" . $row->order1 . "</td>";
				echo "<td>" . $row->name . "</td>";
				echo "<td>" . $row->price . "</td>";
				echo "<td>" . $row->images . "</td>";
				echo "<td>" . $row->features . "</td>";
				echo "<td><a href='edit/record3.php?id=" . $row->id . "'>Edit</a></td>";
				echo "<td style='border-right: none;'><a href='edit/delete.php?table=shop&id=" . $row->id . "'>Delete</a></td>";
				echo "</tr>";
				}

				echo "</table>";
				}
				// if there are no records in the database, display an alert message
				else
				{
				echo "No results to display!";
				}
				}
				// show an error if there is an issue with the database query
				else
				{
				echo "Error: " . $mysqli->error;
				}

				// close database connection
				$mysqli->close();

				?>
			</div>
			<a href="edit/record3.php">Add New Record</a>
		</section>
		<section class="item four">
			<h1>Team</h1>
			<div class="overflow"><?php
				// connect to the database
				include('../config.php');

				// get the records from the database
				if ($result = $mysqli->query("SELECT * FROM team"))
				{
				// display records if there are records to display
				if ($result->num_rows > 0)
				{
				// display records in a table
				echo "<table cellpadding='10' style='border-collapse:collapse; width:100%;'>";

				// set table headers
				echo "<tr><th>ID</th><th>Order</th><th>Name</th><th>Logo</th><th>Rank</th><th>About</th><th></th><th style='border-right: none'></th></tr>";

				while ($row = $result->fetch_object())
				{
				// set up a row for each record
				echo "<tr>";
				echo "<td style='border-left: none;'>" . $row->id . "</td>";
				echo "<td>" . $row->order1 . "</td>";
				echo "<td>" . $row->name . "</td>";
				echo "<td>" . $row->logo . "</td>";
				echo "<td>" . $row->rank . "</td>";
				echo "<td>" . $row->about . "</td>";
				echo "<td><a href='edit/record4.php?id=" . $row->id . "'>Edit</a></td>";
				echo "<td style='border-right: none;'><a href='edit/delete.php?table=team&id=" . $row->id . "'>Delete</a></td>";
				echo "</tr>";
				}

				echo "</table>";
				}
				// if there are no records in the database, display an alert message
				else
				{
				echo "No results to display!";
				}
				}
				// show an error if there is an issue with the database query
				else
				{
				echo "Error: " . $mysqli->error;
				}

				// close database connection
				$mysqli->close();

				?>
			</div>
			<a href="edit/record4.php">Add New Record</a>
		</section>
		<section class="item five">
			<h1>Reviews</h1>
			<div class="overflow"><?php
				// connect to the database
				include('../config.php');

				// get the records from the database
				if ($result = $mysqli->query("SELECT * FROM reviews"))
				{
				// display records if there are records to display
				if ($result->num_rows > 0)
				{
				// display records in a table
				echo "<table cellpadding='10' style='border-collapse:collapse; width:100%;'>";

				// set table headers
				echo "<tr><th>ID</th><th>Order</th><th>Name</th><th>Logo</th><th>Review</th><th>Rating</th><th></th><th style='border-right: none'></th></tr>";

				while ($row = $result->fetch_object())
				{
				// set up a row for each record
				echo "<tr>";
				echo "<td style='border-left: none;'>" . $row->id . "</td>";
				echo "<td>" . $row->order1 . "</td>";
				echo "<td>" . $row->name . "</td>";
				echo "<td>" . $row->logo . "</td>";
				echo "<td>" . $row->review . "</td>";
				echo "<td>" . $row->rating . "</td>";
				echo "<td><a href='edit/record5.php?id=" . $row->id . "'>Edit</a></td>";
				echo "<td style='border-right: none;'><a href='edit/delete.php?table=reviews&id=" . $row->id . "'>Delete</a></td>";
				echo "</tr>";
				}

				echo "</table>";
				}
				// if there are no records in the database, display an alert message
				else
				{
				echo "No results to display!";
				}
				}
				// show an error if there is an issue with the database query
				else
				{
				echo "Error: " . $mysqli->error;
				}

				// close database connection
				$mysqli->close();

				?>
			</div>
			<a href="edit/record5.php">Add New Record</a>
		</section>
		<section class="item six">
			<h1>Contact Requests</h1>
			<div class="overflow"><?php
				// connect to the database
				include('../config.php');

				// get the records from the database
				if ($result = $mysqli->query("SELECT * FROM contact"))
				{
				// display records if there are records to display
				if ($result->num_rows > 0)
				{
				// display records in a table
				echo "<table cellpadding='10' style='border-collapse:collapse; width:100%;'>";

				// set table headers
				echo "<tr><th>ID</th><th>Email</th><th>Discord</th><th>Message</th><th style='border-right: none'></th></tr>";

				while ($row = $result->fetch_object())
				{
				// set up a row for each record
				echo "<tr>";
				echo "<td style='border-left: none;'>" . $row->id . "</td>";
				echo "<td>" . $row->email . "</td>";
				echo "<td>" . $row->discord . "</td>";
				echo "<td>" . $row->message . "</td>";
				echo "<td style='border-right: none;'><a href='edit/delete.php?table=contact&id=" . $row->id . "'>Delete</a></td>";
				echo "</tr>";
				}

				echo "</table>";
				}
				// if there are no records in the database, display an alert message
				else
				{
				echo "No results to display!";
				}
				}
				// show an error if there is an issue with the database query
				else
				{
				echo "Error: " . $mysqli->error;
				}

				// close database connection
				$mysqli->close();

				?>
			</div>
		</section>
		<section class="item seven">
			<h1>Discount Codes</h1>
			<div class="overflow"><?php
				// connect to the database
				include('../config.php');

				// get the records from the database
				if ($result = $mysqli->query("SELECT * FROM discountcodes"))
				{
				// display records if there are records to display
				if ($result->num_rows > 0)
				{
				// display records in a table
				echo "<table cellpadding='10' style='border-collapse:collapse; width:100%;'>";

				// set table headers
				echo "<tr><th>ID</th><th>Code</th><th>Percentage</th><th>Expiration (M/D/Y - 01/01/0001)</th><th></th><th style='border-right: none'></th></tr>";

				while ($row = $result->fetch_object())
				{
				// set up a row for each record
				echo "<tr>";
				echo "<td style='border-left: none;'>" . $row->id . "</td>";
				echo "<td>" . $row->code . "</td>";
				echo "<td>" . $row->percent . "</td>";
				echo "<td>" . $row->expiry . "</td>";
				echo "<td><a href='edit/record7.php?id=" . $row->id . "'>Edit</a></td>";
				echo "<td style='border-right: none;'><a href='edit/delete.php?table=discountcodes&id=" . $row->id . "'>Delete</a></td>";
				echo "</tr>";
				}

				echo "</table>";
				}
				// if there are no records in the database, display an alert message
				else
				{
				echo "No results to display!";
				}
				}
				// show an error if there is an issue with the database query
				else
				{
				echo "Error: " . $mysqli->error;
				}

				// close database connection
				$mysqli->close();

				?>
			</div>
			<a href="edit/record7.php">Add New Record</a>
		</section>
		<section class="item eight">
			<h1>Image Uploader</h1>
			<form method="POST" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="submit" name="submit" value="Upload Image">
			</form>
		</section>
	</section>
  	<script>
	    function toggle_visibility(id) {
	    	if (id == "") {
	    		id = "one";
	    	}
	    	var e = document.querySelector("." + id);
	    	var act = document.querySelector("." + id + 'e');
	    	var body = document.getElementsByClassName("item");
	    	var nav = document.getElementsByClassName("itemnav");
	    	var i;

	    	for (i = 0; i < body.length; i++) {
			    body[i].classList.remove("activee");
			}

			for (i = 0; i < nav.length; i++) {
			    nav[i].classList.remove("active");
			}
	    		
	    	e.classList.add("activee");
	    	act.classList.add("active");
	    }
	</script>
	<script>
		function myResize() {
			var left = document.getElementById('left');
			var right = document.getElementById('right');
			var open = document.getElementById('open');
			var bottom = document.getElementById('bottom');
			var close = document.getElementById('close');
			var pfp = document.getElementById('pfp');
			var welcome = document.getElementById('welcome');
			var span = document.getElementsByClassName("span");

			if (left.style.width == '4%') {
				left.style.width = '11%';
				left.style.padding = 'auto 2% auto 2%';
				left.style.textAlign = 'left';
				right.style.width = '82%';
				open.style.display = 'none';
				close.style.display = 'block';
				bottom.style.width = '70%'
				pfp.style.height = '15vh';
				welcome.style.display = 'inline-block';
				for (i = 0; i < span.length; i++) {
				    span[i].style.display = 'inline-block';
				}
			}

			else {
				left.style.width = '4%';
				left.style.padding = 'auto 0 auto 0';
				left.style.textAlign = 'center';
				right.style.width = '90%';
				open.style.display = 'block';
				close.style.display = 'none';
				bottom.style.width = '45%';
				pfp.style.height = '7vh';
				welcome.style.display = 'none';
				for (i = 0; i < span.length; i++) {
				    span[i].style.display = 'none';
				}
			}
		}

		$('.itemnav').click(function(event) {
			event.preventDefault();
			history.pushState(null, '', this.href);
		});

		function getQueryVariable(variable){
		    var query = window.location.search.substring(1);
		    var vars = query.split("&");
		    for (var i=0;i<vars.length;i++) {
		        var pair = vars[i].split("=");
		        if(pair[0] == variable){return pair[1];}
		    }
		    return(false);
		}

		toggle_visibility(getQueryVariable("name"));
	</script>
</body>
</html>