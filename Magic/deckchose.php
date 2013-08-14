<?php
session_start();
include_once "sql.php";

if (!isset($_SESSION["id"])) { header('Location: index.php'); }


$link		= bdd_connect();

$sql		= "SELECT * FROM `magic_decks` WHERE `account_id` = ". $_SESSION["id"];
$req 		= mysql_query($sql);

$game_id	= ($_POST["game_id"] > 0) ? $_POST["game_id"] : 0;

mysql_close($link);
?>

<head>
	<title>FiBoot</title>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="list.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
</head>

<body>

	<div class="page">
	
		<a class="block" href="http://letakol.free.fr/fiboot/">
			<div class="header"></div>
		</a>
		
		<div class="content">
		
			<a href="deck.php"><input type="submit" name="new" value="Créer un nouveau deck" /></a>
			<a href="index.php"><input type="submit" name="new" class="dark" value="Retour" /></a>
		
			<fieldset>
				<legend>Mes decks</legend>
				
				<table>
					<?php while ($data = mysql_fetch_array($req)) { ?>
						<tr>
							<td class="title"><?=$data["title"]?></td>
							
							<td>
								<form method="post" action="game.php">
									<input type="hidden" name="game_id" value="<?=$game_id?>" />
									<input type="hidden" name="deck_id" value="<?=$data["id"]?>" />
									<input type="submit" value="Choisir ce deck" />
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