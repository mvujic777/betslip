<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "config.php"; 

if (isset($_GET["detailid"])){
	$detailid=$_GET['detailid'];
} else{
	$detailid=$_POST['id_ticket'];
}

$modal_title='';
$modal_body='';
$message='';
$login_IDUser='';
if($_SERVER["REQUEST_METHOD"] == "POST"){
	#check user id or retrive if you already have it in database
	try{
		#statement is in double quotation mark since use values in select statement like email='emailvalue'
		$sql_login="SELECT id FROM Member WHERE email='".$_REQUEST['email']."' AND username='".$_REQUEST['username']."'";
		$result_sql_login=$pdo->query($sql_login);
		if ($result_sql_login->rowCount()==1) {
			
			while($row=$result_sql_login->fetch()){
				$login_IDUser=$row['id'];
			}
				
		}	
		else {
		$sql_insertuser='INSERT INTO Member (username, email, wallet) VALUES (:username, :email, :wallet)';
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
	$sql_check_user='SELECT id_member, id_ticket FROM MemberTicket INNER JOIN TicketDetails ON MemberTicket.id_game_details=TicketDetails.id_game_details WHERE id_ticket='.$detailid.' AND id_member='.$login_IDUser;
	$result_sql_check_user=$pdo->query($sql_check_user);
	
		if ($result_sql_check_user->rowCount()>=1) {
			$message="$(window).on('load', function() {
			$('#myModal').modal('show');
			});";
			$modal_title='Warning!';
			$modal_body='You already placed bets on this ticket';
		}
		
		else {
		
			# here we going to use try catch since from select we create insert into statement
			try{
			#creating insert into statement from two parts
			$part1='INSERT INTO MemberTicket (id_member, id_game_details, id_bet_value) VALUES ';
			$part2='';
			for ($x=0; $x<count($_POST['betvalue']);$x++){
			$part2=$part2.'(:id_member, :id_game_details'.$x.',:id_bet_value'.$x.'),';
			}
			#removing last coma sign with substr function
			$sql_insert_user_bet=substr($part1.' '.$part2,0,-1);
			$statement=$pdo->prepare($sql_insert_user_bet);
			$statement->bindParam(':id_member',$login_IDUser);
			#checkbox send values in array so here we print all values from array and add it into bindParam statement
			for ($x=0; $x<count($_POST['betvalue']);$x++){
			$statement->bindParam((':id_game_details'.$x),($_POST['gamedetails'][$x]));
			$statement->bindParam((':id_bet_value'.$x),($_POST['betvalue'][$x]));
			}
			$statement->execute();
			$message="$(window).on('load', function() {
			$('#myModal').modal('show');
			});";
			$modal_title='Thank you!';
			$modal_body='Good luck and follow results and others <a href="ticketresults.php?detailid='.$detailid.'">here!</a></br>';
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
		$sql_ticket_date='SELECT ticket_start_date, ticket_end_date FROM Ticket WHERE id='.$detailid;
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
    die('ERROR: Could not able to execute $sql_ticket_date. ' . $e->getMessage());
	}

$sql_list='';
$row_number=0; //* Variable have number of games in ticket and later we echo into javascript so we can use it in javascript check before users post bets, check are all bets in ticket placed
try {
	$sql_ticket_list='SELECT TicketDetails.id, id_ticket, game_name, game_start_date, sport_name, bet_name, TicketDetails.id_game_details, bvbn FROM TicketDetails INNER JOIN GameDetails ON TicketDetails.id_game_details = GameDetails.id INNER JOIN Sport ON GameDetails.id_sport = Sport.id INNER JOIN Game ON GameDetails.id_game = Game.id INNER JOIN BetName ON GameDetails.id_bet_name = BetName.id WHERE (date_sub(game_start_date, INTERVAL 15 MINUTE)) > NOW() AND id_ticket='.$detailid;
	$result_ticket_list=$pdo->query($sql_ticket_list);
	if ($result_ticket_list->rowCount()>0){
		$row_number=$result_ticket_list->rowCount();
		while($row=$result_ticket_list->fetch()){
			
			$sql_list=$sql_list.'<tr><td>'.$row['game_name'].'</td><td>'.date('d.m H:i',strtotime($row['game_start_date'])).'</td><td>'.$row['sport_name'].'</td><td>'.$row['bet_name'].'<input type="hidden" name="gamedetails[]" value="'.$row['id_game_details'].'"/></td>';
			
			$bvbn_detail_list='';
			$sql_bvb_details='SELECT BetValueBetName.id_bet_value, bet_value FROM BetValueBetName  INNER JOIN BetValue ON BetValueBetName.id_bet_value=BetValue.id WHERE bvbn='.$row['bvbn'];
			$result_bvbn_details=$pdo->query($sql_bvb_details);
		
			while($row1=$result_bvbn_details->fetch()){
				$bvbn_detail_list=$bvbn_detail_list.'<td><input type="checkbox" name="betvalue[]" value="'.$row1['id_bet_value'].'"> '.$row1['bet_value'].'</td>';
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
										<input type="hidden" value="<?php echo $detailid; ?>" name="id_ticket">
										<div style="overflow-x:auto;">
										<table id="tbl">
											<thead>
												<tr>
													<th colspan="2">Ticket list</th>
												</tr>
											</thead>
											<tbody>
											<th>Game</th>
											<th>Start date</th>
											<th>Sport</th>
											<th>Betname</th>
										<?php echo $sql_list; ?>
										</tbody>
										</table>
										</div>
										Username: <input type="text" name="username" required /><span id="response"></span> Email: <input type="email" name="email" required /> Wallet: <input type="text" name="wallet" required />
										<input type="submit" name="submit" value="Place bet"/>
										</form>
										
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
			<!---<script src="assets/js/checkusername.js"></script>  -->
			<script text="javascript"> var row_number= <?php echo json_encode($row_number); ?>;</script>
			<script src="assets/js/checkbet.js"></script>
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $modal_title; ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <?php echo $modal_body; ?>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div> 
	</body>
</html>