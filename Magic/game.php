<?php
session_start();
include_once "sql.php";

if (!isset($_SESSION["id"]) || !isset($_POST["game_id"])) { header('Location: index.php'); }

$link	= bdd_connect();

if (!isset($_POST["spectator"]))
{
	query(($_POST["game_id"] > 0) ?
		"UPDATE `magic_games` SET `oppenent_account_id` = ". $_SESSION["id"] .", `oppenent_deck_id` = ". $_POST["deck_id"] ." WHERE `id` = ". $_POST["game_id"] .";":
		"INSERT INTO `magic_games` (`creator_account_id`, `creator_deck_id`, `active`) VALUES (". $_SESSION["id"] .", ". $_POST["deck_id"] .", 1);"
	);
	
	$req	= query("SELECT `id` FROM `magic_games` WHERE (`creator_account_id` = ". $_SESSION["id"] ." OR `oppenent_account_id` = ". $_SESSION["id"] .")AND `active` = 1;");
	$data	= mysql_fetch_array($req);
	$_POST["game_id"]	= $data["id"];
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
		var sending			= true;
		var account_id		= <?=$_SESSION["id"]?>;
		var	game_id			= <?=$_POST["game_id"]?>;
		var logInterval;
		var statusInterval;
		
		
		updateLoop();
		
		function updateLoop()
		{
			clearInterval(statusInterval);
			clearInterval(logInterval);
			statusInterval	= setInterval(function() { updateStatus() }, 1500);
			logInterval		= setInterval(function() { getLogs() }, 1500);
			updateStatus();
			getLogs();
		}
		
		function getLogs()
		{
			$.post("jpost.php", {
				action: 	"get_logs",
				game_id:	game_id
			}).done(function(data)
			{
				$("ul#log").html(data);
				$("input[name=chat]").removeClass("dark");
				sending		= false;
			});
		}
		
		function updateStatus()
		{
			var	startTime 		= new Date().getTime();
			var elapsedTime 	= 0;
			
			$.post("jpost.php", {
				action: 	"update_game_status",
				game_id:	game_id
			}).done(function(data)
			{
				elapsedTime 	= new Date().getTime() - startTime;
				$("#requestime").removeClass("good").removeClass("normal").removeClass("bad")
					
				if (elapsedTime <= 50)
					$("#requestime").addClass("good");
				if (elapsedTime > 50 && elapsedTime <= 100)
					$("#requestime").addClass("normal");
				if (elapsedTime > 100)
					$("#requestime").addClass("bad");
				$("#requestime").html(elapsedTime);
				
				if (data == "game over")
				{
					clearInterval(statusInterval);
					clearInterval(logInterval);
					sending		= true;
					$("input[name=chat]").addClass("dark");
					alert("Partie termin\u00e9e");
				}
			});
		}
		
		function sendLog(log_type, log)
		{
			sending		= true;
			$("input[name=chat]").addClass("dark");
			
			$.post("jpost.php", {
				action: 	"send_log",
				game_id:	game_id,
				log_type:	log_type,
				log:		log
			}).done(function(data)
			{
				//? data -> error
				updateLoop();
			});
		}
		
		
		$("form").submit(function()
		{
			if ($("input[name=chat]").val().length > 0 && !sending)
			{
				sendLog("chat", $("input[name=chat]").val());
				$("input[name=chat]").val("");
			}
			return false;
		});
		
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
			
			<div class="ping">ping: </span><span id="requestime"></div>
			
		</div>
	
		<div class="footer">
			Created by <a href="mailto:fiboot89@gmail.com">FiBoot</a>
		</div>
		
	</div>
	
</body>