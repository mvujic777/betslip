<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "config.php";
session_start(); 
if (isset($_GET['detailid'])) {
	$detailid=$_GET['detailid'];
}

$ticket_users='';
try {
	$sql_ticket_users='SELECT DISTINCT (username), id_member FROM MemberTicket INNER JOIN Member ON MemberTicket.id_member = Member.id INNER JOIN TicketDetails ON MemberTicket.id_game_details = TicketDetails.id_game_details WHERE id_ticket='.$detailid;
	$result_ticket_users=$pdo->query($sql_ticket_users);
	if ($result_ticket_users->rowCount()>0) {
		while ($row=$result_ticket_users->fetch()){
			$ticket_users=$ticket_users.'<tr><td><a href="userresult.php?detailid='.$row['id_member'].'">'.$row['username'].'</a></td></tr>';
				$ticket_details='';
				$win_points=0;
				$max_points=0;
				$sql_ticket_details='SELECT game_name,bet_name, BetValue.bet_value as winbet, bv.bet_value as userbet FROM MemberTicket INNER JOIN TicketDetails ON MemberTicket.id_game_details=TicketDetails.id_game_details INNER JOIN GameDetails ON TicketDetails.id_game_details=GameDetails.id INNER JOIN Game ON GameDetails.id_game=Game.id INNER JOIN BetName ON GameDetails.id_bet_name=BetName.id INNER JOIN BetValue ON GameDetails.id_bet_value=BetValue.id INNER JOIN BetValue as bv ON MemberTicket.id_bet_value=bv.id WHERE id_ticket='.$detailid.' AND id_member='.$row['id_member'];
				$result_ticket_details=$pdo->query($sql_ticket_details);
				
					while($row1=$result_ticket_details->fetch()){
						
						$ticket_details=$ticket_details.'<tr><td>'.$row1['game_name'].'</td><td>'.$row1['bet_name'].'</td><td>'.$row1['userbet'].'</td><td>'.$row1['winbet'].'</td>';
						if ($row1['userbet']==$row1['winbet']){
						$ticket_details=$ticket_details.'<td>WIN</td></tr>';
						$win_points=$win_points+10;
						$max_points=$max_points+10;
						} elseif ($row1['winbet']=='in play'){
						$ticket_details=$ticket_details.'<td>in play</td></tr>';
						$max_points=$max_points+10;
						} else {
						$ticket_details=$ticket_details.'<td>LOSE</td></tr>';
						$max_points=$max_points+10;
						}
					}
				$ticket_users=$ticket_users.''.$ticket_details.'<tr><td>number of points: </td><td>'.$win_points.'/'.$max_points.'<td></tr>';
					
				
				
			
		}
	}	
	
	else {
		echo '0 results in ticket';
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_ticket_users. ' . $e->getMessage());
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
										<div style="overflow-x:auto;">
											<table>
											<thead>
												<tr>
													<th colspan="2">Ticket results</th>
												</tr>
											</thead>
											<tbody>
											<th>Game</th>
											<th>Betname</th>
											<th>User bet</th>
											<th>Winning bet</th>
											<?php echo $ticket_users; ?>
											</tbody>
											</table>
										</div>
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