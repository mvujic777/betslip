<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "config.php"; 
if (isset($_GET['detailid'])) {
	$detailid=$_GET['detailid'];
}
$user_result_list='';
$w=0;
$l=0;
$inplay=0;
try {
	$sql_user_result='SELECT username, sport, game_name, bet_name, bv.bet_value as userbet, BetValue.bet_value AS winbet FROM UserTicket INNER JOIN User ON UserTicket.IDUser=User.IDUser INNER JOIN GameDetails ON UserTicket.IDGD=GameDetails.IDGD INNER JOIN Sport ON GameDetails.IDSport=Sport.IDSport INNER JOIN Game ON GameDetails.IDGame=Game.IDGame INNER JOIN BetName ON GameDetails.IDBetName=BetName.IDBetName INNER JOIN BetValue AS bv ON UserTicket.User_IDBetValue=bv.IDBetValue INNER JOIN BetValue ON GameDetails.IDBetValue=BetValue.IDBetValue WHERE UserTicket.IDUser='.$detailid;
	$result_user_result=$pdo->query($sql_user_result);
	if ($result_user_result->rowCount()>0){
		while ($row=$result_user_result->fetch()){
			$user=$row['username'];
			$user_result_list=$user_result_list.'<tr><td>'.$row['sport'].'</td><td>'.$row['game_name'].'</td><td>'.$row['bet_name'].'</td><td>'.$row['userbet'].'</td><td>'.$row['winbet'].'</td>';
			if ($row['userbet']==$row['winbet']){
				$user_result_list=$user_result_list.'<td>WIN</td></tr>';
				$w=$w+1;
			} elseif ($row['winbet']=='In play'){
				$user_result_list=$user_result_list.'<td>in play</td></tr>';
				$inplay=$inplay+1;
			} else {
				$user_result_list=$user_result_list.'<td>LOSE</td></tr>';
				$l=$l+1;
			}
		}
	} else {
		$user_result_list= '0 results in database';
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_user_result. ' . $e->getMessage());
	}

$dataPoints=array();
try {
	$sql_user_sport='SELECT sport, COUNT(GameDetails.IDSport) as number FROM UserTicket INNER JOIN GameDetails ON UserTicket.IDGD=GameDetails.IDGD INNER JOIN Sport ON GameDetails.IDSport=Sport.IDSport WHERE UserTicket.IDUser='.$detailid.' GROUP BY GameDetails.IDSport';
	$result_user_sport=$pdo->query($sql_user_sport);
	if ($result_user_sport->rowCount()>0) {
		while ($row=$result_user_sport->fetch()){
			$dataPoints[]=array("label"=>$row['sport'],"y"=>$row['number']);
		}
	}
	
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_user_sport. ' . $e->getMessage());
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
	<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title: {
		text: "User bets per sport"
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
												<th colspan="2">User results for <?php echo $user; ?></th>
											</tr>
										</thead>
										<tbody>
										<th>Sport</th>
										<th>Game</th>
										<th>Betname</th>
										<th>User bet</th>
										<th>Winning bet</th>
										<?php echo $user_result_list; ?>
										</tbody>
										</table>
										<?php echo 'total'.($w+$l+$inplay).'  win '.$w.' lose '.$l.' in play '.$inplay; ?>
										</section>
										<section>
										<div>
										<div id="chartContainer" style="height: 370px; width: 100%;"></div>
										<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
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