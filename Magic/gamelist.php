<?php
session_start();

if (!isset($_SESSION["id"])) { header('Location: index.php'); }

?>

<head>
	<title>Play</title>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="list.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{
		var statusInterval	= setInterval(function() { updateStatus() }, 1500);
		var gameInterval	= setInterval(function() { listGames() }, 1500);
		updateStatus();
		listGames();
	
	
		function updateStatus()
		{
			$.post("jpost.php", {
				action:	"update_status"
			}).done(function(data)
			{
				// ping
			});
		}
	
		function listGames()
		{
			$.post("jpost.php", {
				action: 	"list_games"
			}).done(function(data)
			{
				var html	= "";
				var games	= data.split("|");
				$.each(games, function(index, value) { games[index]	= value.split("-"); });
				
				for (var i = 0; i < games.length - 1; i++)
				{
					html	+= "<tr><td class=\"title\">"+ games[i][1] +" ("+ games[i][2] +")";
					html	+= (games[i][3].length > 0) ?
						"<span style=\"color:#F93\"> VS </span>"+ games[i][3] +" ("+ games[i][4] +")</td>":
						"<td><form method=\"post\" action=\"deckchose.php\"><input type=\"hidden\" name=\"game_id\" value=\""+ games[i][0] +"\" /><input type=\"submit\" value=\"Rejoindre cette partie\" /></form></td>";
					html	+= "<td><form method=\"post\" action=\"game.php\"><input type=\"hidden\" name=\"game_id\" value=\""+ games[i][0] +"\" /><input type=\"hidden\" name=\"spectator\" /><input type=\"submit\" value=\"Rejoindre en spectateur\" /></form></td></tr>";
				}
				$("table#list").html(html);
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
		
			<a href="deckchose.php"><input type="submit" name="new" value="Nouvelle partie" /></a>
			<a href="index.php"><input type="submit" name="new" class="dark" value="Retour" /></a>
			
			<fieldset>
				<legend>Parties en cours</legend>
				
				<table id="list"></table>
					
			</fieldset>
		
		</div>
	
		<div class="footer">
			Created by <a href="mailto:fiboot89@gmail.com">FiBoot</a>
		</div>
		
	</div>

</body>