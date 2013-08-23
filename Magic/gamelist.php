<?php
session_start();
include_once "sql.php";

if (!isset($_SESSION["id"])) { header('Location: index.php'); }

$link		= bdd_connect();

$req 		= query("SELECT * FROM `magic_games` WHERE `active` = 1;");

close($link);
?>

<head>
	<title>Play</title>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="list.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{
	
		var logInterval		= setInterval(function() { logUpdate() }, 1500);
	
			$.post("jpost.php", {
				action: 	"update_status",
	
	});
	</script>
</head>

<body>

	<div class="page">
	
		<a class="block" href="http://letakol.free.fr/fiboot/">
			<div class="header"></div>
		</a>
		
		<div class="content">
		
			<a href="deckchose.php"><input type="submit" name="new" value="Nouvelle partie" /></a>
			<a href="index.php"><input type="submit" name="new" class="dark" value="Retour" /></a>
			
			<fieldset>
				<legend>Parties en cours</legend>
				
				<table>
				
					<?php while ($data = mysql_fetch_array($req)) { ?>
					
						<tr>
							<td class="title"><?=$data["creator_account_id"] ." (". $data["creator_deck_id"] .")".
								(($data["oppenent_account_id"] > 0) ? " <span style=\"color:#F93\">VS</span> ". $data["oppenent_account_id"] ." (". $data["oppenent_deck_id"] .")" : "")?></td>
							
							<?php if ($data["oppenent_account_id"] > 0) { ?><td></td><?php } else { ?>
								<td>
									<form method="post" action="deckchose.php">
										<input type="hidden" name="game_id" value="<?=$data["id"]?>" />
										<input type="submit" value="Rejoindre cette partie" />
									</form>
								</td>
							<?php } ?>
							
							<td>
								<form method="post" action="game.php">
									<input type="hidden" name="game_id" value="<?=$data["id"]?>" />
									<input type="hidden" name="spectator" />
									<input type="submit" value="Rejoindre en spectateur" />
								</form>
							</td>
						</tr>
						
					<?php } ?>
					
				</table>
					
			</fieldset>
		
		</div>
	
		<div class="footer">
			Created by <a href="mailto:fiboot89@gmail.com">FiBoot</a>
		</div>
		
	</div>

</body>