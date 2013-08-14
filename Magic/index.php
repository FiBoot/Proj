<?php
session_start();
include_once "sql.php";

if (isset($_POST["login"]))
{
	$link	= bdd_connect();
	
	$sql	= "SELECT * FROM `magic_accounts` WHERE `login` = \"". addslashes($_POST["login"]) ."\" AND `password` = \"". addslashes($_POST["password"]) ."\"";
	$req	= mysql_query($sql);
	
	if (mysql_num_rows($req) == 1)
	{
		$data			= mysql_fetch_array($req);
		$_SESSION["id"]	= $data["id"];
	}
	
	mysql_close($link);
}
?>

<head>
	<title>FiBoot</title>
	<link rel="stylesheet" href="style.css" />
</head>

<body>

	<div class="page">
	
		<a class="block" href="http://letakol.free.fr/fiboot/">
			<div class="header"></div>
		</a>
	
		<div class="content">
		
			<?php if (isset($_SESSION["id"])) { ?>
			
				<a class="block" href="decklist.php"><input type="button" value="Decks" /></a>
				<a class="block" href="gamelist.php"><input type="button" value="Jouer" /></a>
				<a class="block" href="deco.php"><input type="button" class="dark" value="Deconnexion" /></a>
				
			<?php } else { ?>
			
				<form method="POST">
					<input type="text" name="login" />
					<input type="password" name="password" />
					<input type="submit" value="Connection" />
				</form>
				
				<a class="block" href="register.php"><input type="button" class="dark" value="Inscription" /> </a>
				
			<?php } ?>
			
		</div>
		
		<div class="footer">
			Created by <a href="mailto:fiboot89@gmail.com">FiBoot</a>
		</div>
	
	</div>
	

</body>
