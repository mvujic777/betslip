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

$num_per_ticket='';
try {
	$sql_num_per_ticket='SELECT ticket_start_date, ticket_end_date, TicketDetails.id_ticket, COUNT(DISTINCT MemberTicket.id_member) AS num_players FROM MemberTicket iNNER JOIN TicketDetails ON MemberTicket.id_game_details=TicketDetails.id_game_details INNER JOIN Ticket ON TicketDetails.id_ticket = Ticket.id GROUP BY TicketDetails.id_ticket';
	$result_num_per_ticket=$pdo->query($sql_num_per_ticket);
	if ($result_num_per_ticket->rowCount()>0){
		while($row=$result_num_per_ticket->fetch()){
			$num_per_ticket=$num_per_ticket.'<tr><td>'.date('D d H:i ',strtotime($row['ticket_start_date'])).'</td><td>'.date('D d H:i ',strtotime($row['ticket_end_date'])).'</td><td>'.$row['num_players'].'</td><td><a href="ticketdetails.php?detailid='.$row['id_ticket'].'">Details</a></td></tr>';
		}
	}
	else {
		$num_per_ticket= 'O tickets in database';
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_num_per_ticket. ' . $e->getMessage());
	}


#array for number users tickets per sport
$tickets_per_user='';
$dataPoints=array();
 try {
	 $sql_tickets_per_user='SELECT username, COUNT(DISTINCT TicketDetails.id_ticket) as number FROM MemberTicket INNER JOIN TicketDetails ON MemberTicket.id_game_details= TicketDetails.id_game_details INNER JOIN Member ON MemberTicket.id_member = Member.id GROUP BY MemberTicket.id_member';
	 $result_tickets_per_user=$pdo->query($sql_tickets_per_user);
	 if ($result_tickets_per_user->rowCount()>0) {
		 while ($row=$result_tickets_per_user->fetch()) {
			 $dataPoints[]=array("label"=>$row['username'],"y"=>$row['number']);
		 }
	 }
	 else {
		 $tickets_per_user='0 players tickets per sport';
	 }
 } catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_tickets_per_user. ' . $e->getMessage());
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
	<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title: {
		text: "User tickets per sport"
	},
	axisY: {
		suffix: "%",
		scaleBreaks: {
			autoCalculate: true
		}
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0\"\"",
		indexLabel: "{y}",
		indexLabelPlacement: "inside",
		indexLabelFontColor: "white",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
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
											<h2>Statistic</h2>
											<div>
											
											</div>
										</section>
										<section>
										<table>
										<thead>
											<tr>
												<th colspan="2">Number of players per ticket</th>
											</tr>
										</thead>
										<tbody>
										<?php echo $num_per_ticket;  ?>
										</tbody>
										</table>
										</section>
										
										
										<section>
										<div>
										<div id="chartContainer" style="height: 370px; width: 100%;"></div>
										<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
										<?php $tickets_per_user; ?>
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