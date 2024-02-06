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
		$sql_add_sport='INSERT INTO Sport (sport_name) VALUES (:sport)';
		$statement=$pdo->prepare($sql_add_sport);
		$statement->bindParam(':sport',$_REQUEST['sport']);
		$statement->execute();
		
	} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_add_sport. ' . $e->getMessage());
	}
}

try{
$sport_list='';	
$sql_sport='SELECT id, sport_name FROM Sport';
$result_sport=$pdo->query($sql_sport);

	if ($result_sport->rowCount()>0) {
	
	 while($row=$result_sport->fetch()){
		 $sport_list=$sport_list.'<tr><td>'. $row['sport_name'].'</td><td><a href="delete.php?deletesport='.$row['id'].'">Delete</a></td></tr>';
	 }
	 
	}
	else {
	 $sport_list='0 sport in database';
	 
	}

} catch(PDOException $e){
    die('ERROR: Could not able to execute $sql_sport. ' . $e->getMessage());
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
											<h2>Add sport</h2>
											<div>
											<form name="add_sport" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>"  method="POST" >
											<input type="text" name="sport" required />
											<input type="submit" value="Add sport" />
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

											<?php

											echo $sport_list;

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