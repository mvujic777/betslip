<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "config.php"; 
$ticket_list='';
try{
	$sql_ticket_list='SELECT id, ticket_start_date, ticket_end_date FROM Ticket';
	$result_ticket_list=$pdo->query($sql_ticket_list);
	if ($result_ticket_list->rowCount()>0){
		while ($row=$result_ticket_list->fetch()){
			$ticket_list=$ticket_list.'<tr><td>'.date('M d H:i ',strtotime($row['ticket_start_date'])).'</td><td>'.date('M d H:i ',strtotime($row['ticket_end_date'])).'</td><td><a href="ticketresults.php?detailid='.$row['id'].'">Details</a> </td></tr>';
		}
	} else {
		$ticket_list='0 tickets in database';
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_ticket_list. ' . $e->getMessage());
	}

$user_list='';
try {
	$sql_user_list='SELECT username, id FROM Member';
	$result_user_list=$pdo->query($sql_user_list);
	if ($result_user_list->rowCount()>0){
		while($row=$result_user_list->fetch()){
			if ($row['username'] != 'admin') {
			$user_list=$user_list.'<tr><td>'.$row['username'].'</td><td><a href="userresult.php?detailid='.$row['id'].'">Details</a></td></tr>';
			}
		}
	} else {
		$user_list= '0 users in database';
	}
}	catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_user_list. ' . $e->getMessage());
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
								<h1><a href="index.html">Verti</a></h1>
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
					
				</div>

			<!-- Main -->
				<div id="main-wrapper">
					<div class="container">
						<div class="row gtr-200">
							<div class="col-4 col-12-medium">

								<!-- Sidebar -->
									<?php include 'left_ad.php';?> 

							</div>
							<div class="col-8 col-12-medium imp-medium">

								<!-- Content -->
									<div id="content">
										<section class="last">
											<table>
										<thead>
											<tr>
												<th colspan="2">List of tickets - on details you can read players, userbets and winbets</th>
											</tr>
										</thead>
										<tbody>
										<th>Start date</th>
										<th>End date</th>
										<?php echo $ticket_list; ?>
										</tbody>
										</table>
											
										</section>
										<section>
										<table>
										<thead>
											<tr>
												<th colspan="2">List of tickets - on details you can read players winning and losing score</th>
											</tr>
										</thead>
										<tbody>
										<?php echo $user_list; ?>
										</tbody>
										</table>
										</section>
									</div>

							</div>
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