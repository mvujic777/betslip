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
		$sql_add_betname='INSERT INTO BetName (bet_name) VALUES (:bet_name)';
		$statement=$pdo->prepare($sql_add_betname);
		$statement->bindParam(':bet_name',$_REQUEST['bet_name']);
		$statement->execute();
		
	} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_add_betname. ' . $e->getMessage());
	}
}


try{
$betname_list='';
$sql_betname='SELECT id, bet_name FROM BetName';
$result_betname=$pdo->query($sql_betname);

	if ($result_betname->rowCount()>0){
	
	while ($row=$result_betname->fetch()){
		$betname_list=$betname_list.'<tr><td>'.$row['bet_name'].'</td><td><a href="delete.php?deletebetname='.$row['id'].'">Delete</a></td></tr>';
	}
	
	}	
	else {
	$betname_list= '0 betname in database';
	}
}
catch (PDOException $e){
    die('ERROR: Could not able to execute $sql_betname. ' . $e->getMessage());
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
											<h2>Add betname</h2>
											<div>
											<form name="add_bet_name" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST" >
											<input type="text" name="bet_name" required />
											<input type="submit" value="Add betname" />
											</form>
											</div>
										</section>
										<section>
										<table>
											<thead>
												<tr>
												<th colspan="2">Betname list</th>
												</tr>
											</thead>
											<tbody>

											<?php

											echo $betname_list;

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