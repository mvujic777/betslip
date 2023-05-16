<?php
if(!isset($_SESSION['rolenumber'])){
	$_SESSION['rolenumber']=0;
}
if(!isset($_SESSION['username'])){
	$_SESSION['username']='';
}
?>
<nav id="nav">
								<ul>
									<li><a href="/index.php">Home</a></li>
									<li>
									<a href="#">Results</a>
									<ul>
									<li><a href="/oldtickets.php">Tickets</a></li>
									<li><a href="/leaderboard.php">Leaderboards</a></li>
									</ul>
									</li>
									<?php
									if (htmlspecialchars($_SESSION['rolenumber'])==15) {
										echo '
										<li>
										<a href="#">Add</a>
										<ul>
										<li><a href="/admin/sport.php">Sport</a></li>
										<li><a href="/admin/betname.php">Betname</a></li>
										<li><a href="/admin/betvalue.php">Betvalue</a></li>
										<li><a href="/admin/game.php">Game</a></li>
										<li><a href="/admin/insertgamedetails.php">Insert game details</a></li>
										<li><a href="/admin/createticket.php">Create ticket</a></li>
										<li><a href="/admin/insertleaderboard.php">Insert leaderboard</a></li>
										</ul>
										</li>
										<li><a href="/admin/stats.php">Stats</a></li>
										<li><a href="/logout.php">Logout</a></li>
										';
										
									} else {
										 echo '<li><a href="/login.php">Login</a></li>';
									} 
									echo '<li><a href="profile.php">'.htmlspecialchars($_SESSION["username"]).'</a></li>';
									?>
									
								</ul>
							</nav>