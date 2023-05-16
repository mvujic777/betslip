<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "config.php"; 

if (isset($_GET["detailid"])){
	$detailid=$_GET['detailid'];
} else{
	$detailid=$_POST['IDTicket'];
}
$message='';
$login_IDUser='';
if($_SERVER["REQUEST_METHOD"] == "POST"){
	#check user id or retrive if you have it in database
	try{
		#statement is in double quotation mark since use values in select statement like email='emailvalue'
		$sql_login="SELECT IDUser FROM User WHERE email='".$_REQUEST['email']."' AND username='".$_REQUEST['username']."'";
		$result_sql_login=$pdo->query($sql_login);
		if ($result_sql_login->rowCount()==1) {
			
			while($row=$result_sql_login->fetch()){
				$login_IDUser=$row['IDUser'];
			}
				
		}	
		else {
		$sql_insertuser='INSERT INTO User (username, email, wallet) VALUES (:username, :email, :wallet)';
		$statement=$pdo->prepare($sql_insertuser);
		$statement->bindParam(':username',$_REQUEST['username']);
		$statement->bindParam(':email',$_REQUEST['email']);
		$statement->bindParam(':wallet',$_REQUEST['wallet']);
		$statement->execute();
		$login_IDUser=$pdo->lastInsertID();
		}	
	} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_login. ' . $e->getMessage());
	}
	
	#block double posting, check did user already posted, does his id is conencted with user ticket
	try {
	$sql_check_user='SELECT IDUser FROM UserTicket WHERE IDTicket='.$detailid.' AND IDUser='.$login_IDUser;
	$result_sql_check_user=$pdo->query($sql_check_user);
	
		if ($result_sql_check_user->rowCount()>1) {
			$message='You already place bets in this ticket';
		}
		else {
		
			# here we going to use try catch since from select we create insert into statement
			try{
			#creating insert into statement from two parts
			$part1='INSERT INTO UserTicket (IDUser, IDTicket, IDGD, User_IDBetValue) VALUES ';
			$part2='';
			for ($x=0; $x<count($_POST['betvalue']);$x++){
			$part2=$part2.'(:IDUser, :IDTicket, :IDGD'.$x.',:User_IDBetValue'.$x.'),';
			}
			#removing last coma sign with substr function
			$sql_insert_user_bet=substr($part1.' '.$part2,0,-1);
			$statement=$pdo->prepare($sql_insert_user_bet);
			$statement->bindParam(':IDUser',$login_IDUser);
			$statement->bindParam(':IDTicket',$_REQUEST['IDTicket']);
			#checkbox send values in array so here we print all values from array and add it into bindParam statement
			for ($x=0; $x<count($_POST['betvalue']);$x++){
			$statement->bindParam((':IDGD'.$x),($_POST['IDGD'][$x]));
			$statement->bindParam((':User_IDBetValue'.$x),($_POST['betvalue'][$x]));
			}
			$statement->execute();
			} catch(PDOException $e){
			die('ERROR: Could not able to execute $sql_insert_user_bet. ' . $e->getMessage());
			}
		}
	}
	
	catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_check_user. ' . $e->getMessage());
	}
	
			

	
	
	
}
$ticket_start_date='';
try {
		$sql_ticket_date='SELECT ticket_start_date, ticket_end_date FROM Ticket WHERE IDTicket='.$detailid;
		$result_ticket_date=$pdo->query($sql_ticket_date);
		if ($result_ticket_date->rowCount()>0) {
			while ($row=$result_ticket_date->fetch()){
				$ticket_start_date=date('D d H:i ',strtotime($row['ticket_start_date']));
				$ticket_end_date=date('D d H:i ',strtotime($row['ticket_end_date']));
			}
		} else {
			$ticket_start_date= 'No such ticket';
		}
		
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql_ticket_date. " . $e->getMessage());
	}

$sql_list='';
try {
	$sql_ticket_list='SELECT ITD, IDTicket, game_name, sport, bet_name, TicketDetails.IDGD, BVBN FROM TicketDetails INNER JOIN GameDetails ON TicketDetails.IDGD=GameDetails.IDGD INNER JOIN Sport ON GameDetails.IDSport=Sport.IDSport INNER JOIN Game ON GameDetails.IDGame=Game.IDGame INNER JOIN BetName ON GameDetails.IDBetName=BetName.IDBetName WHERE IDTicket='.$detailid;
	$result_ticket_list=$pdo->query($sql_ticket_list);
	if ($result_ticket_list->rowCount()>0){
		while($row=$result_ticket_list->fetch()){
			
			$sql_list=$sql_list.'<tr><td>'.$row['game_name'].'</td><td>'.$row['sport'].'</td><td>'.$row['bet_name'].'<input type="hidden" name="IDGD[]" value="'.$row['IDGD'].'"/><input type="hidden" name="IDTicket" value="'.$row['IDTicket'].'"></td>';
			
			$bvbn_detail_list='';
			$sql_bvb_details='SELECT BVBN.IDBetValue, bet_value FROM BVBN  INNER JOIN BetValue ON BVBN.IDBetValue=BetValue.IDBetValue WHERE BVBN='.$row['BVBN'];
			$result_bvbn_details=$pdo->query($sql_bvb_details);
		
			while($row1=$result_bvbn_details->fetch()){
				$bvbn_detail_list=$bvbn_detail_list.'<td><input type="checkbox" name="betvalue[]" value="'.$row1['IDBetValue'].'">'.$row1['bet_value'].'</td>';
			}
			
			$sql_list=$sql_list.''.$bvbn_detail_list.'</tr>';
		}
	}
} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_ticket_list. ' . $e->getMessage());
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
										<h2> <?php echo $ticket_start_date.'  - '.$ticket_end_date; ?></h2>	
										<div>
										<form name="userplacebet" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST" >
										<table>
											<thead>
												<tr>
													<th colspan="2">Ticket list</th>
												</tr>
											</thead>
											<tbody>
											<th>Game</th>
											<th>Sport</th>
											<th>Betname</th>
										<?php echo $sql_list; ?>
										</tbody>
										</table>
										Username: <input type="text" name="username" required /> Email: <input type="email" name="email" required /> Faucetpay wallet: <input type="text" name="wallet" required />
										<input type="submit" value="Place bet"/>
										</form>
										</div>
										</section>
										<?php echo $message; ?>
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