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
if($_SERVER["REQUEST_METHOD"] == "POST"){
	try {
	$sql_create_ticket='INSERT INTO Ticket (ticket_start_date, ticket_end_date,ticket_description, show_ticket) VALUES (:ticket_start_date,:ticket_end_date,:ticket_description,False)';
	$statement=$pdo->prepare($sql_create_ticket);
	$statement->bindParam(':ticket_start_date',$_REQUEST['start_date']);
	$statement->bindParam(':ticket_end_date',$_REQUEST['end_date']);
	$statement->bindParam(':ticket_description',$_REQUEST['description']);
	$statement->execute();
	}
	catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_create_ticket. ' . $e->getMessage());
	}
}
$ticket_list='';
try{
	$sql_ticket_list='SELECT id, ticket_start_date, ticket_end_date FROM Ticket';
	$result_ticket_list=$pdo->query($sql_ticket_list);
	if ($result_ticket_list->rowCount()>0){
		while ($row=$result_ticket_list->fetch()){
			$ticket_list=$ticket_list.'<tr><td>'.$row['ticket_start_date'].'</td><td>'.$row['ticket_end_date'].'</td><td><a href="ticketdetails.php?detailid='.$row['id'].'">Details</a>  <a href="delete.php?deleteticket='.$row['id'].'">Delete</a></td></tr>';
		}
	} else {
		$ticket_list='0 tickets in database';
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
											<h2>Add ticket</h2>
											<div>
											<form name="create_ticket" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST" >
											Start date
											<input type="datetime-local" name="start_date" required />
											End date
											<input type="datetime-local" name="end_date" required />
											<textarea name="description"  maxlength="50"></textarea>
											<input type="submit" value="Add ticket" />
											</form>
											</div>
										</section>
										<section>
										<table>
											<thead>
												<tr>
												<th colspan="1">List of tickets</th>
												</tr>
											</thead>
											<tbody>
											<th>Start date</th>
											<th>End date</th>
											<?php

											echo $ticket_list;

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

	</body>
</html>