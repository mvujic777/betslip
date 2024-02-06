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
try{
$sport_list='';	
$sql_list='SELECT id, sport_name FROM Sport';						
$result_list=$pdo->query($sql_list);
if ($result_list->rowCount()>0) {	
	 while($row=$result_list->fetch()){
		 $sport_list=$sport_list.'<option value="'.$row['id'].'">'.$row['sport_name'].'</option>';
	 }	 
}
 else {
	 $sport_list= '0 sport in database';	 
 }

$gamename_list='';
$sql_list='SELECT id, game_name, game_start_date FROM Game';
$result_list=$pdo->query($sql_list);
if ($result_list->rowCount()>0) {	
	 while($row=$result_list->fetch()){
		 $gamename_list=$gamename_list.'<option value="'.$row['id'].'">'.$row['game_name'].'('.$row['game_start_date'].')</option>';
	 }	 
}
 else {
	 $gamename_list= '0 game in database';	 
 }

$betname_list='';
$sql_list='SELECT id, bet_name FROM BetName';
$result_list=$pdo->query($sql_list);
if ($result_list->rowCount()>0) {	
	 while($row=$result_list->fetch()){
		 $betname_list=$betname_list.'<option value="'.$row['id'].'">'.$row['bet_name'].'</option>';
	 }	 
}
 else {
	 $betname_list= '0 betname in database';	 
 }
 
$betvalue_list='';
$sql_list='SELECT id, bet_value FROM BetValue';
$result_list=$pdo->query($sql_list);
if ($result_list->rowCount()>0) {	
	 while($row=$result_list->fetch()){
		 $betvalue_list=$betvalue_list.'<input type="checkbox" name="betvalue[]" value="'.$row['id'].'"> '.$row['bet_value'].' ';
	 }	 
}
 else {
	 $betvalue_list= '0 betvalue in database';	 
 }
 
} catch(PDOException $e){
    die("ERROR: Could not able to execute some sql statement above. " . $e->getMessage());
}
#bvn is variable add so we can add multiple betvalue to one game
if($_SERVER["REQUEST_METHOD"] == "POST"){
	try {
		#select last bvbn in table
		$sql_bvbn='SELECT bvbn FROM BetValueBetName ORDER BY id DESC LIMIT 1';
		$result_bvbn=$pdo->query($sql_bvbn);
		
		if ($result_bvbn->rowCount()>0) {
	
			while($row=$result_bvbn->fetch()){
			$bvbn=($row['bvbn']+1);
			}	 
		}
		else {
			$bvbn=1;
		
		}
		#inserting game options in table BVBN
		# here we generate insert into statement from two parts, part1 is fixed part, part2 are values for each row
		$part1='INSERT INTO BetValueBetName (id_bet_value, bvbn) VALUES';
		$part2='';
		for ($x = 0; $x < count($_POST['betvalue']); $x++) {
		$part2=$part2. '(:id_bet_value'.$x.',:bvbn),';
			}
		#delete last coma sign in part 2
		$sql_add_bvbn=substr($part1.' '.$part2,0,-1);
		$statement=$pdo->prepare($sql_add_bvbn);
		$statement->bindParam('bvbn',$bvbn);
		#count post_betvalue counts how many betvalue options one game have example 1,x,2 are three values and ve generate this many statement bindParam
		# post method used since it is array
		for ($x = 0; $x < count($_POST['betvalue']); $x++) {
		$statement->bindParam((':id_bet_value'.$x),($_POST['betvalue'][$x]));	
		}
		$statement->execute();
		#inserting game rest of games details, In play value check in database and change it at end of line
		#1 is default value for inplay
		$sql_add_gamedetails='INSERT INTO GameDetails (id_game, id_sport, bvbn, id_bet_name, id_bet_value) VALUES (:id_game, :id_sport, :bvbn, :id_bet_name, 1)';
		$statement=$pdo->prepare($sql_add_gamedetails);
		$statement->bindParam(':id_game',$_REQUEST['game']);
		$statement->bindParam(':id_sport',$_REQUEST['sport']);
		$statement->bindParam('bvbn',$bvbn);
		$statement->bindParam('id_bet_name',$_REQUEST['betname']);
		$statement->execute();
	
	} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_add_gamedetails. ' . $e->getMessage());
	}
}
$game_details_list='';
$bvbn_detail_list='';

try {
	$sql_game_details='SELECT GameDetails.id, game_name, sport_name, bvbn, bet_name, bet_value FROM GameDetails INNER JOIN Game ON GameDetails.id_game=Game.id INNER JOIN Sport ON GameDetails.id_sport=Sport.id INNER JOIN BetName ON GameDetails.id_bet_name=BetName.id INNER JOIN BetValue ON GameDetails.id_bet_value=BetValue.id';
	$result=$pdo->query($sql_game_details);
	if ($result->rowCount()>0) {
	
	 while($row=$result->fetch()){
				
		$game_details_list=$game_details_list.'<tr><td>'.$row['game_name'].'</td><td>'.$row['sport_name'].'</td><td>'.$row['bet_name'].'</td><td>'.$row['bet_value'].'</td>';	
		$idgd='<td><a href="delete.php?deletegamedetail='.$row['id'].'&deletebvbn='.$row['bvbn'].'">Delete</a><a href="updatewbet.php?updatewbet='.$row['id'].'">Update</a></td>';
		$bvbn_detail_list='';
		$sql_bvb_details='SELECT bet_value FROM BetValueBetName  INNER JOIN BetValue ON BetValueBetName.id_bet_value=BetValue.id WHERE bvbn='.$row['bvbn'];
		$result_bvbn_details=$pdo->query($sql_bvb_details);
		
			while($row=$result_bvbn_details->fetch()){
				$bvbn_detail_list=$bvbn_detail_list.'<td>'.$row['bet_value'].'</td>';
			}
		
		$game_details_list=$game_details_list.''.$bvbn_detail_list.''.$idgd.'</tr>';
	 }
	 
	}
	else {
	 $game_details_list= '0 game details in database'; 
	}
} catch(PDOException $e){
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
											<h2>Insert game details</h2>
											<div>
											<form name="insert_game_details" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST" >
											<select name="game" required>
											<option value="">Select game</option>
											<?php echo $gamename_list; ?>
											</select>
											<select name="sport" required>
											<option value="">Select sport</option>
											<?php echo $sport_list; ?>
											</select>
											<select name="betname" required>
											<option value="">Select betname</option>
											<?php echo $betname_list; ?>
											</select>
											<span id="choose_betvalue">
											<?php echo $betvalue_list; ?>
											</br><span class="message">Choose at least one bet value</span>
											</br></span>
											<input type="submit" value="Insert game details" />
											</form>
											</div>
										</section>
										<section>
										<table>
											<thead>
												<tr>
												<th colspan="2">Game details</th>
												</tr>
												
											</thead>
											<tbody>
											<th>Game</th>
											<th>Sport</th>
											<th>Betname</th>
											<th>Winning bet</th>
											<th colspan="3" >Bets in ticket</th>
											<?php

											echo $game_details_list;

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
			<script type="text/javascript">
	var choose_betvalue = new Spry.Widget.ValidationCheckbox("choose_betvalue");
</script>
	</body>
</html>