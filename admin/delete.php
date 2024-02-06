<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../config.php"; 

if (isset($_GET["deletesport"])){
	
		try {
		$sql_delete_sport='DELETE FROM Sport WHERE id=(:id)';
		$statement=$pdo->prepare($sql_delete_sport);
		$statement->bindParam(':id',$_GET["deletesport"]);
		$statement->execute();
		echo 'Data delete <a href="sport.php">Go back</a>';
		} catch(PDOException $e){
			
		if ($e->getCode()=='23000') {
			echo 'You can not delete Sport because it assign to Game details.';
		}
		else {
			die('ERROR: Could not able to execute $sql_delete_sport. ' . $e->getMessage());
		}

		}
	}

if (isset($_GET["deletebetname"])){
	
		try {
		$sql_delete_betname='DELETE FROM BetName WHERE id=(:id)';
		$statement=$pdo->prepare($sql_delete_betname);
		$statement->bindParam(':id',$_GET["deletebetname"]);
		$statement->execute();
		echo 'Data delete <a href="betname.php">Go back</a>';
		} catch(PDOException $e){
			
		if ($e->getCode()=='23000') {
			echo 'You can not delete Betname becuase it assign to Game details.';
		}
		else {
			die('ERROR: Could not able to execute $sql_delete_betname. ' . $e->getMessage());
		}
		
		}
	}

if (isset($_GET["deletebetvalue"])){
	
		try {
		$sql_delete_betvalue='DELETE FROM BetValue WHERE id=(:id)';
		$statement=$pdo->prepare($sql_delete_betvalue);
		$statement->bindParam(':id',$_GET["deletebetvalue"]);
		$statement->execute();
		echo 'Data delete <a href="betvalue.php">Go back</a>';
		} catch(PDOException $e){
		
		if ($e->getCode()=='23000') {
			echo 'You can not delete Betvalue becuase it assign to Game details.';
		}
		else {
			die('ERROR: Could not able to execute $sql_delete_betvalue. ' . $e->getMessage());
		}
		
		}
	}

if (isset($_GET["deletegame"])){
	
		try {
		$sql_delete_gamename='DELETE FROM Game WHERE id=(:id)';
		$statement=$pdo->prepare($sql_delete_gamename);
		$statement->bindParam(':id',$_GET["deletegame"]);
		$statement->execute();
		echo 'Data delete <a href="game.php">Go back</a>';
		} catch(PDOException $e){
			
		if ($e->getCode()=='23000') {
			echo 'You can not delete Game becuase it assign to Game details.';
		}
		else {
			die('ERROR: Could not able to execute $sql_delete_gamename. ' . $e->getMessage());
		}
		
		}
	}

if (isset($_GET["ticketdetail"])){
	
		try {
		$sql_delete_ticket_detail='DELETE FROM TicketDetails WHERE id=(:id)';
		$statement=$pdo->prepare($sql_delete_ticket_detail);
		$statement->bindParam('id',$_GET["ticketdetail"]);
		$statement->execute();
		echo 'Data delete <a href="ticketdetails.php?detailid='.$_GET["detailid"].'">Go back </a>';
		} catch(PDOException $e){
			die('ERROR: Could not able to execute $sql_delete_ticket_detail. '. $e->getMessage());
		}
		

	} 

if (isset($_GET["deleteticket"])){
	
		try{
		$sql_delete_ticket='DELETE FROM Ticket WHERE id=(:id)';
		$statement=$pdo->prepare($sql_delete_ticket);
		$statement->bindParam('id',$_GET["deleteticket"]);
		$statement->execute();
		echo 'Data delete  <a href="createticket.php">Go back</a>';
		} catch(PDOException $e){
		
		if ($e->getCode()=='23000') {
			echo 'You can not delete Ticket because it have games assign.';
		}
		else {
			die('ERROR: Could not able to execute $sql_delete_ticket. '. $e->getMessage());
		}
		
		}
	}

if (isset($_GET["deletegamedetail"])){
	
		try{
		$sql_delete_game_detail='DELETE FROM GameDetails WHERE id=(:id)';
		$statement=$pdo->prepare($sql_delete_game_detail);
		$statement->bindParam('id',$_GET["deletegamedetail"]);
		$statement->execute();
		$sql_delete_bvbn='DELETE FROM BetValueBetName WHERE bvbn=(:bvbn)';
		$statement=$pdo->prepare($sql_delete_bvbn);
		$statement->bindParam('bvbn',$_GET["deletebvbn"]);
		$statement->execute();
		echo 'Data delete <a href="insertgamedetails.php">Go back</a>';
		} catch(PDOException $e){
		die('ERROR: Could not able to execute $sql_delete_game_detail. ' . $e->getMessage());
		}	
	}

if (isset($_GET["deletelboard"])){
	
		try{
		$sql_delete_lederboard='DELETE FROM Leaderboard WHERE id=(:id)';
		$statement=$pdo->prepare($sql_delete_lederboard);
		$statement->bindParam('id',$_GET["deletelboard"]);
		$statement->execute();
		echo 'Data delete <a href="insertleaderboard.php">Go back</a>';
		} catch(PDOException $e){
		die('ERROR: Could not able to execute $sql_delete_lederboard. ' . $e->getMessage());
		}
	}
?>