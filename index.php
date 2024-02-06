<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "config.php"; 
$ticket_list='';
try{
	$sql_ticket_list='SELECT id, ticket_start_date, ticket_end_date FROM Ticket WHERE ticket_end_date> NOW() AND show_ticket=1';
	$result_ticket_list=$pdo->query($sql_ticket_list);
	if ($result_ticket_list->rowCount()>0){
		while ($row=$result_ticket_list->fetch()){
			$ticket_list=$ticket_list.'<div class="col-4 col-12-medium"><section class="box feature"><div class="inner"><header><h2>'.date('F d', strtotime($row['ticket_start_date'])).' - '.date('F d', strtotime($row['ticket_end_date'])).'</h2></header><a href="userplacebet.php?detailid='.$row['id'].'" class="button icon fa-file-alt">Place bet</a></div></section></div>';
		}
	} else {
		$ticket_list= '0 tickets in database';
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_ticket_list. '. $e->getMessage());
	}
?>
<!DOCTYPE HTML>
<!--
	Verti by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<?php include 'header.php';?> 
	<body class="is-preload homepage">
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header-wrapper">
					<header id="header" class="container">

						<!-- Logo -->
							<div id="logo">
								<h1><a href="index.php">Verti</a></h1>
								<span>by HTML5 UP</span>
							</div>

						<!-- Nav -->
							<?php include 'navigation.php';?> 

					</header>
				</div>

			<!-- Banner -->
				<?php include 'title.php';?> 

			<!-- Features -->
				<div id="features-wrapper">
					<div class="container">
						<div class="row">
						
							<?php echo $ticket_list; ?>
						</div>
						
							
							
							
						
					</div>
				</div>

			

			<!-- Footer -->
				<?php include 'footer.php';?>

			</div>

		<!-- Scripts -->

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>