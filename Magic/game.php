<?php
session_start();
include_once "sql.php";

if (!isset($_SESSION["id"])) { header('Location: index.php'); }

$link		= bdd_connect();


if ($_POST["game_id"] > 0)
{
	$sql	= "UPDATE `magic_games` SET `oppenent_account_id` = ". $_SESSION["id"] .", `oppenent_deck_id` = ". $_POST["deck_id"] ." WHERE `id` = ". $_POST["game_id"] .";";
	mysql_query($sql);
} else if ($_POST["deck_id"] > 0) {
	$sql	= "INSERT INTO `magic_games` (`creator_account_id`, `creator_deck_id`, `date`, `active`) VALUES (". $_SESSION["id"] .", ". $_POST["deck_id"] .", \"". date('Y-m-j H:i:s') ."\", 1);";
	mysql_query($sql);
}

mysql_close($link);
?>

<head>
	<title>Play</title>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="game.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{
		var intervalId	= setInterval(function(){loop()}, 3000);
		
		// update function
		function loop()
		{
			$.post("jpost.php", {
				action: "update_game"
			}).done(function(data)
			{
				//alert(data);
			});
		}
		
		$("form").submit(function()
		{
			
			$.post("jpost.php", {
				action: "send_text",
				text:	$("input[name=text]").val()
			}.done(function(data)
			{
				$("#chatarea").val(data);
			});
			return false;
		}
		
	});
	</script>
</head>

<body>

	<div class="page">
	
		<a class="block" href="http://letakol.free.fr/fiboot/">
			<div class="header"></div>
		</a>
		
		<div class="content">
			<a href="gamelist.php"><input type="submit" name="new" class="dark" value="Quitter la partie" /></a>
			
			<div class="gameboard">
			
			</div>
			
			<textarea id="chatarea" readonly="1"></textarea>
			<form>
				<input type="text" name="chat" />
				<input type="sumbit" value="Envoyer" />
			</form>
		</div>
	
		<div class="footer">
			Created by <a href="mailto:fiboot89@gmail.com">FiBoot</a>
		</div>
		
	</div>
	
</body>