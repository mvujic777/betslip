<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "config.php"; 
if (isset($_GET['detailid'])){
	$detailid=$_GET['detailid'];
} 

$leaderboard_date='';
$leaderboard_details='';
try {
	$sql_leaderboard_date='SELECT  l_start, l_end FROM Leaderboard WHERE IDLeaderboard='.$detailid;
	$result_leaderboard_date=$pdo->query($sql_leaderboard_date);
	if ($result_leaderboard_date->rowCount()>0) {
		while ($row=$result_leaderboard_date->fetch()){
			$l_start=$row['l_start'];
			$l_end=$row['l_end'];
			$leaderboard_date=date('M d ',strtotime($row['l_start'])).'-'.date('M d ',strtotime($row['l_end']));
		}
	} else {
		$leaderboard_date= '<tr><td>Nothing in database</td></tr>';
	}
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql_leaderboard_date. " . $e->getMessage());
	}
$dataPoints=array();

try {
	$sql_leaderboard_users='SELECT DISTINCT (username), UserTicket.IDUser FROM UserTicket INNER JOIN User ON UserTicket.IDUser=User.IDUser INNER JOIN GameDetails ON UserTicket.IDGD=GameDetails.IDGD INNER JOIN Game ON GameDetails.IDGame=Game.IDGame WHERE Game.start_date >" '.$l_start.'" AND Game.start_date <" '.$l_end.'"';
	$result_leaderboard_users=$pdo->query($sql_leaderboard_users);
	if ($result_leaderboard_users->rowCount()>0) {
		while ($row=$result_leaderboard_users->fetch()) {
			$win_points=0;
			$max_points=0;
			$sql_leaderboard_points='SELECT bv.bet_value as userbet, BetValue.bet_value AS winbet FROM UserTicket INNER JOIN GameDetails on UserTicket.IDGD=GameDetails.IDGD INNER JOIN Game ON GameDetails.IDGame=Game.IDGame INNER JOIN BetName ON GameDetails.IDBetName=BetName.IDBetName INNER JOIN BetValue AS bv ON UserTicket.User_IDBetValue=bv.IDBetValue INNER JOIN BetValue ON GameDetails.IDBetValue=BetValue.IDBetValue WHERE UserTicket.IDUser='.$row['IDUser'].' AND Game.start_date > "'.$l_start.'" AND Game.start_date < "'.$l_end.'"';
			$result_leaderboard_points=$pdo->query($sql_leaderboard_points);
			if ($result_leaderboard_points->rowCount()>0) {
				while ($row1=$result_leaderboard_points->fetch()) {
						if ($row1['userbet']==$row1['winbet']){
						$win_points=$win_points+10;
						$max_points=$max_points+10;
						} elseif ($row1['winbet']=='In play'){
						$max_points=$max_points+10;
						} else {
						$max_points=$max_points+10;
						}
				}
				
			} else {
				$leaderboard_details= '<tr><td>Nothing in database</td></tr>';
			}
			$dataPoints[]=array('username'=>$row['username'],'points'=>$win_points);
		}
	} else {
		$leaderboard_details= '<tr><td>Nothing in database</td></tr>';
	}
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql_leaderboard_users. " . $e->getMessage());
	}
$keys = array_column($dataPoints, 'points');
array_multisort($keys, SORT_DESC, $dataPoints);
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
											<h4>Rewards for three best players</h4>
											First reward 10000 BTC satoshi
											<br>Second reward 5000 BTC satoshi
											<br>Third reward 2000 BTC satoshi
											<table>
											<thead>
												<tr>
													<th colspan="2">Leaderboard details <?php echo $leaderboard_date; ?></th>
												</tr>
											</thead>
											<tbody>
											<th>Username</th>
											<th>Points</th>
											<?php 
											if (count($dataPoints)>0) {
											foreach ( $dataPoints as $element) {
											echo '<tr><td>'. $element['username'].'</td><td>'. $element['points'].'</td></tr>';
											}
											} else {
												echo $leaderboard_details;
											}
	
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