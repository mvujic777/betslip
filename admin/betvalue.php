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
		$sql_add_betvalue='INSERT INTO BetValue (bet_value) VALUES (:bet_value)';
		$statement=$pdo->prepare($sql_add_betvalue);
		$statement->bindParam(':bet_value',$_REQUEST['bet_value']);
		$statement->execute();
		
	} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_add_betvalue. ' . $e->getMessage());
	}
}

try{
$betvalue_list='';
$sql_betvalue='SELECT IDBetValue, bet_value FROM BetValue ';
$result_betvalue=$pdo->query($sql_betvalue);

	if ($result_betvalue->rowCount()>0){
	
	while ($row=$result_betvalue->fetch()){
		$betvalue_list=$betvalue_list.'<tr><td>'.$row['bet_value'].'</td><td><a href="delete.php?deletebetvalue='.$row['IDBetValue'].'">Delete</a<</td><tr>';
	}
	
	}	
	else {
	$betvalue_list= '0 betvalue in database';
	}
}
catch (PDOException $e){
    die('ERROR: Could not able to execute $sql_betvalue. ' . $e->getMessage());
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
											<h2>Add betvalue</h2>
											<div>
											<form name="add_bet_value" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST" >
											<input type="text" name="bet_value" required />
											<input type="submit" value="Add betvalue" />
											</form>
											</div>
										</section>
										<section>
										<table>
											<thead>
												<tr>
												<th colspan="2">Betvalue list</th>
												</tr>
											</thead>
											<tbody>

											<?php

											echo $betvalue_list;

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