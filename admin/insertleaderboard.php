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
	$sql_add_leaderboard='INSERT INTO Leaderboard (l_start, l_end) VALUES (:l_start,:l_end)';
	$statement=$pdo->prepare($sql_add_leaderboard);
	$statement->bindParam(':l_start',$_REQUEST['l_start']);
	$statement->bindParam(':l_end',$_REQUEST['l_end']);
	$statement->execute();
	}
	catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_add_leaderboard. ' . $e->getMessage());
	}
}
$leaderboard_list='';
try {
	$sql_leaderboard_list='SELECT IDLeaderboard, l_start, l_end FROM Leaderboard';
	$result_leaderboard_list=$pdo->query($sql_leaderboard_list);
	if ($result_leaderboard_list->rowCount()>0) {
		while ($row=$result_leaderboard_list->fetch()){
			$leaderboard_list=$leaderboard_list.'<tr><td>'.$row['l_start'].'</td><td>'.$row['l_end'].'</td><td><a href="/leaderboarddetails.php?detailid='.$row['IDLeaderboard'].'">Details</a></td><td><a href="delete.php?deletelboard='.$row['IDLeaderboard'].'">Delete</a>';
		}
	} else {
		$leaderboard_list= '<tr><td>Nothing in database</td></tr>';
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_leaderboard_list. '. $e->getMessage());
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
											<h2>Add leaderboard</h2>
											<div>
											<form name="add_leaderboard" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST" >
											Start date
											<input type="datetime-local" name="l_start" required />
											End date
											<input type="datetime-local" name="l_end" required />
											<input type="submit" value="Add leaderboard" />
</form>
											</div>
										</section>
										<section>
										<table>
											<thead>
												<tr>
												<th colspan="2">Leaderboard list</th>
												</tr>
											</thead>
											<tbody>
											<th>Start date</th>
											<th>End date</th>
											<?php

											echo $leaderboard_list ;

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