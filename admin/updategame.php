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

if (isset($_GET["updategame"])){
	$id_game=$_GET['updategame'];
} else{
	$id_game=$_POST['updategame'];
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	try { 
	$sql_update_game='UPDATE Game SET game_name=:game_name, start_date=:start_date, end_date=:end_date WHERE IDGame='.$_REQUEST['updategame'];
	$statement=$pdo->prepare($sql_update_game);
	$statement->bindParam(':game_name',$_REQUEST['game_name']);
	$statement->bindParam(':start_date',$_REQUEST['start_date']);
	$statement->bindParam(':end_date',$_REQUEST['end_date']);
	$statement->execute();
	echo 'Data updated';
	} catch (PDOException $e){
    die('ERROR: Could not able to execute $sql_update_game. ' . $e->getMessage());
	
	}
	
}


$gamename_list='';

try{

	$sql_gamename='SELECT game_name, start_date, end_date FROM Game WHERE IDGame='.$id_game;
	$result_gamename=$pdo->query($sql_gamename);

	if ($result_gamename->rowCount()>0){
	
		while ($row=$result_gamename->fetch()){
			$gamename_list=$gamename_list.'<input  name="game_name" value="'.$row['game_name'].'" /></br> <input type="datetime-local" name="start_date" value="'.$row['start_date'].'" /> </br> <input type="datetime-local" name="end_date" value="'.$row['end_date'].'"/>';
		}
	}	
	else {
		$gamename_list= '0 games ticket';
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
											<h2>Add sport</h2>
											<div>
											<form name="update_gamename" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"  method="POST"> 

											<?php echo $gamename_list; ?>
											<input name="updategame" type="hidden" value="<?php echo $id_game ?>" />
											</br><input type="submit" value="Update game" />
											</form>
											<a href="game.php">Go back</a>
											</div>
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