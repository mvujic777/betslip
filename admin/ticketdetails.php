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
if (isset($_GET['detailid'])){
	$detailid=$_GET['detailid'];
} else{
	$detailid=$_POST['detailid'];
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	 if (isset($_POST['insert'])){
		
		try{
		$sql_add_game='INSERT INTO TicketDetails (id_ticket, id_game_details) VALUES (:id_ticket, :id_game_details)';
		$statement=$pdo->prepare($sql_add_game);
		$statement->bindParam(':id_ticket',$_POST['detailid']);
		$statement->bindParam(':id_game_details',$_REQUEST['game']);
		$statement->execute();
		} catch(PDOException $e){
		die('ERROR: Could not able to execute $sql_add_game. ' . $e->getMessage());
		}
	 }
	 elseif (isset($_POST['update'])){
		 
		try{
		$sql_show_ticket='UPDATE Ticket SET show_ticket=:show_ticket WHERE id='.$_REQUEST['detailid'];
		$statement=$pdo->prepare($sql_show_ticket);
		$statement->bindParam('show_ticket', ($_POST['show_ticket'][0]));
		$statement->execute();
		
		} catch(PDOException $e){
		die('ERROR: Could not able to execute $sql_show_ticket. ' . $e->getMessage());
		}
	 }
	 else {
		 echo 'You did not click any button';
	 }
	
}


$ticket_start_date='';
$ticket_end_date='';
$show_ticket='';
try {
		$sql_ticket_date='SELECT ticket_start_date, ticket_end_date,show_ticket FROM Ticket WHERE id='.$detailid;
		$result_ticket_date=$pdo->query($sql_ticket_date);
		if ($result_ticket_date->rowCount()>0) {
			while ($row=$result_ticket_date->fetch()){
				$ticket_start_date=date('D d H:i ',strtotime($row['ticket_start_date']));
				$ticket_end_date=date('D d H:i ',strtotime($row['ticket_end_date']));
				if ($row['show_ticket']==False){
					$show_ticket=$show_ticket.'<input type="checkbox" name="show_ticket[]" value="0" checked> False <input type="checkbox" name="show_ticket[]" value="1" > True';
				} else {
					$show_ticket=$show_ticket.'<input type="checkbox" name="show_ticket[]" value="0"> False <input type="checkbox" name="show_ticket[]" value="1" checked> True';
				}
			}
		} else {
			$ticket_start_date= 'No such ticket in database';
		}
		
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_ticket_date. ' . $e->getMessage());
	}

$gamename_list='';
try {
	$sql_game='SELECT GameDetails.id, game_name, game_start_date, sport_name FROM GameDetails INNER JOIN Game ON GameDetails.id_game=Game.id INNER JOIN Sport ON GameDetails.id_sport=Sport.id';
	$result_list=$pdo->query($sql_game);
	if ($result_list->rowCount()>0) {	
	 while($row=$result_list->fetch()){
		 $gamename_list=$gamename_list.'<option value="'.$row['id'].'">'.$row['game_name'].'('.$row['sport_name'].$row['game_start_date'].')</option>';
		}	 
	}
	else {
	 $gamename_list= '0 games in database';	 
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $gamename_list. ' . $e->getMessage());
	}
# list of games in ticket
$sql_list='';

try {
	# select game name, sport and row
	$sql_ticket_list='SELECT TicketDetails.id, id_ticket, game_name, sport_name, bet_name, bvbn FROM TicketDetails INNER JOIN GameDetails ON TicketDetails.id_game_details = GameDetails.id INNER JOIN Sport ON GameDetails.id_sport = Sport.id INNER JOIN Game ON GameDetails.id_game = Game.id INNER JOIN BetName ON GameDetails.id_bet_name = BetName.id WHERE id_ticket='.$detailid;
	$result_ticket_list=$pdo->query($sql_ticket_list);
	if ($result_ticket_list->rowCount()>0){
		while($row=$result_ticket_list->fetch()){
			
			$sql_list=$sql_list.'<tr><td>'.$row['game_name'].'</td><td>'.$row['sport_name'].'</td><td>'.$row['bet_name'].'</td>';
			# here we add bvbn from table BVBN 
			$bvbn_detail_list='';
			$sql_bvb_details='SELECT bet_value FROM BetValueBetName  INNER JOIN BetValue ON BetValueBetName.id_bet_value=BetValue.id WHERE bvbn='.$row['bvbn'];
			$result_bvbn_details=$pdo->query($sql_bvb_details);
		
			while($row1=$result_bvbn_details->fetch()){
				$bvbn_detail_list=$bvbn_detail_list.'<td>'.$row1['bet_value'].'</td>';
			}
			# here we connect two about query into one so we can print everything
			$sql_list=$sql_list.''.$bvbn_detail_list.'<td><a href="delete.php?ticketdetail='.$row['id'].'&detailid='.$detailid.'">Delete</a></td></tr>';
		}
	}
	else {
		
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_list. ' . $e->getMessage());
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
											<h3>Ticket details for ticket</h3> <h2><?php echo $ticket_start_date.'  - '.$ticket_end_date; ?></h2>
											<div>
											<form name="add_game" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST">
											<select name="game" required>
											<option value="">Select game</option>
											<?php echo $gamename_list; ?>
											</select>
											<input type="hidden" name="detailid" value="<?php echo $detailid ?>" />
											<input type="submit" value="Insert game details" name="insert" />
											</form>
											</div>
											</br>
											<div>
											<form name="show_ticket" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST">
											<?php echo $show_ticket; ?> </br>
											<input type="hidden" name="detailid" value="<?php echo $detailid ?>" />
											<input type="submit" value="Show ticket" name="update" />
											</form>
											</div>
										</section>
										<section>
										<table>
											<thead>
												<tr>
												<th colspan="2">List of sports</th>
												</tr>
											</thead>
											<tbody>
											<th>Game</th>
											<th>Sport</th>
											<th>Betname</th>
											<th colspan="3" >Bets in ticket</th>
											<?php

											echo $sql_list;

											?>

											</tbody>
										</table>
										<a href="createticket.php">Go back</a>
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