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

if (isset($_GET["updatewbet"])){
	$idgd=$_GET['updatewbet'];
} else{
	$idgd=$_POST['updatewbet'];
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	try { 
	$sql_update_wbet='UPDATE GameDetails SET IDBetValue=:IDBetValue WHERE IDGD='.$_REQUEST['updatewbet'];
	$statement=$pdo->prepare($sql_update_wbet);
	# you take post method instead of request when you receive data from checkbox since checkbox works as array
	$statement->bindParam('IDBetValue', ($_POST['betvalue'][0]));
	$statement->execute();
	echo 'Data updated';
	} catch (PDOException $e){
    die('ERROR: Could not able to execute $sql_update_wbet. ' . $e->getMessage());
	
	}
}


$game_details_list='';
$bvbn_detail_list='';
try {
	$sql_game_details='SELECT game_name, bet_name, BVBN FROM GameDetails INNER JOIN Game ON GameDetails.IDGame=Game.IDGame INNER JOIN BetName ON GameDetails.IDBetName=BetName.IDBetName WHERE IDGD='.$idgd;
	$result=$pdo->query($sql_game_details);
	if ($result->rowCount()>0) {
		
		while($row=$result->fetch()){
			$game_details_list=$game_details_list.''.$row['game_name'].'   '.$row['bet_name'];
			
			$bvbn_detail_list='';
			$sql_bvb_details='SELECT BVBN.IDBetValue, bet_value FROM BVBN  INNER JOIN BetValue ON BVBN.IDBetValue=BetValue.IDBetValue WHERE BVBN='.$row['BVBN'];
			$result_bvbn_details=$pdo->query($sql_bvb_details);
		
			while($row=$result_bvbn_details->fetch()){
				$bvbn_detail_list=$bvbn_detail_list.'<input type="checkbox" name="betvalue[]" value="'.$row['IDBetValue'].'">'.$row['bet_value'];
			}
			
			$game_details_list=$game_details_list.''.$bvbn_detail_list;
		}
		
	} else {
		echo '0 bets in database';
	}
}  catch (PDOException $e){
    die('ERROR: Could not able to execute $sql_game_details. ' . $e->getMessage());
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
											<h2>Update winning bet</h2>
											<div>
											
											</div>
										</section>
										<section>
										<form name="updatewbet" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"  method="POST">
										<?php echo $game_details_list; ?>
										<input name="updatewbet" type="hidden" value="<?php echo $idgd ?>" />
										</br><input type="submit" value="Update winning bet" />
										</form>
										<a href="insertgamedetails.php">Go back</a>
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