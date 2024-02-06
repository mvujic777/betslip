<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /login.php");
    exit;
}
require_once "../config.php"; 
if($_SERVER["REQUEST_METHOD"] == "POST"){
	try {
		$sql_add_gamename='INSERT INTO Game (game_name, game_start_date) VALUES (:game_name, :start_date)';
		$statement=$pdo->prepare($sql_add_gamename);
		$statement->bindParam(':game_name',$_REQUEST['game_name']);
		$statement->bindParam(':start_date',$_REQUEST['start_date']);
		$statement->execute();	
	} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_add_gamename. ' . $e->getMessage());
	}
}

try{
$gamename_list='';
$sql_gamename='SELECT id, game_name, game_start_date FROM Game';
$result_gamename=$pdo->query($sql_gamename);

	if ($result_gamename->rowCount()>0){
	
	while ($row=$result_gamename->fetch()){
		$gamename_list=$gamename_list.'<tr><td>'.$row['game_name'].'</td><td>'.$row['game_start_date'].'</td><td><a href="delete.php?deletegame='.$row['id'].'">Delete</a> <a href="updategame.php?updategame='.$row['id'].'">Update</a></td></tr>';
	}
	
	}	
	else {
	$gamename_list= '0 game in database';
	}
}
catch (PDOException $e){
    die('ERROR: Could not able to execute $sql_gamename. ' . $e->getMessage());
}

?>
<!DOCTYPE HTML>
<!--
	Verti by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<?php include '../header.php';?> 
	<body class="is-preload homepage">
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header-wrapper">
					<header id="header" class="container">

						<!-- Logo -->
							<div id="logo">
								<h1><a href="index.html">Verti</a></h1>
								<span>by HTML5 UP</span>
							</div>

						<!-- Nav -->
							<?php include '../navigation.php';?> 

					</header>
				</div>

			<!-- Banner -->
				<?php include '../title.php';?> 

			<!-- Features -->
				<div id="features-wrapper">
					
				</div>

			<!-- Main -->
				<div id="main-wrapper">
					<div class="container">
						<div class="row gtr-200">
							<div class="col-4 col-12-medium">

								<!-- Sidebar -->
									<?php include '../left_ad.php';?> 

							</div>
							<div class="col-8 col-12-medium imp-medium">

								<!-- Content -->
									<div id="content">
										<section>
											<h2>Add game</h2>
											<div>
											<form name="add_game_name" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST" >
											<input type="text" name="game_name" required />
											<input type="datetime-local" name="start_date" />
											<input type="submit" value="Add game" />
											</form>
											</div>
										</section>
										<section>
										<table>
											<thead>
												<tr>
												<th colspan="2">Game list</th>
												</tr>
											</thead>
											<tbody>
											<th>Game</th>
											<th>Start date</th>
											<?php

											echo $gamename_list;

											?>

											</tbody>
										</table>
										</section>
									</div>

							</div>
						</div>
					</div>
				</div>

			<!-- Footer -->
				<?php include '../footer.php';?>

			</div>

		<!-- Scripts -->

			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>