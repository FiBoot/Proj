<?php
session_start();
include_once "sql.php";

if (!isset($_SESSION["id"]) || !isset($_POST["game_id"])) { header('Location: index.php'); }

$link	= bdd_connect();

if (!isset($_POST["spectator"]))
{
	$sql 	= ($_POST["game_id"] > 0) ?
		"UPDATE `magic_games` SET `oppenent_account_id` = ". $_SESSION["id"] .", `oppenent_deck_id` = ". $_POST["deck_id"] ." WHERE `id` = ". $_POST["game_id"] .";":
		"INSERT INTO `magic_games` (`creator_account_id`, `creator_deck_id`, `active`) VALUES (". $_SESSION["id"] .", ". $_POST["deck_id"] .", 1);";
	query($sql);
}

close($link);
?>

<head>
	<title>Play</title>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="game.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{
		var sending			= false;
		var account_id		= <?=$_SESSION["id"]?>;
		var	game_id			= <?=$_POST["game_id"]?>;
		
		var gameInterval	= setInterval(function() { gameUpdate() }, 3000);
		var logInterval		= setInterval(function() { logUpdate() }, 1500);
		
		sendLog("game", account_id +" a rejoint la partie", 1);
		
		// update function
		function gameUpdate()
		{
			$.post("jpost.php", {
				action: 	"update_game"
			}).done(function(data)
			{
				//alert(data);
			});
		}
		
		function logUpdate()
		{
			$.post("jpost.php", {
				action: 	"update_log",
				game_id:	game_id
			}).done(function(data)
			{
				$("ul#log").html(data);
				$("input[name=chat]").removeClass("dark");
				sending		= false;
			});
		}
		
		$("form").submit(function()
		{
			if ($("input[name=chat]").val().length > 0 && !sending)
			{
				sendLog("chat", $("input[name=chat]").val(), 0);
				$("input[name=chat]").val("");
			}
			return false;
		});
		
		function sendLog(log_type, log, first)
		{
			sending		= true;
			$("input[name=chat]").addClass("dark");
			
			$.post("jpost.php", {
				action: 	"send_log",
				game_id:	game_id,
				log_type:	log_type,
				log:		log,
				first:		first
			}).done(function(data)
			{
				//? data -> error
				clearInterval(logInterval);
				logUpdate();
				logInterval		= setInterval(function() { logUpdate() }, 1500);
			});
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
			
			<ul id="log"></ul>
			
			<form method="post" action="game.php">
				<input type="text" name="chat" />
				<input type="submit" value="Envoyer" />
			</form>
		</div>
	
		<div class="footer">
			Created by <a href="mailto:fiboot89@gmail.com">FiBoot</a>
		</div>
		
	</div>
	
</body>