<?php
session_start();
include_once "sql.php";

if (!isset($_SESSION["id"])) { header('Location: index.php'); }

$link	= bdd_connect();


if (isset($_POST["save"]))
{
	$sql	= ($_POST["id"] > 0) ?
		"UPDATE `magic_decks` SET "
		."`account_id` = ". $_SESSION["id"] .", `title` = \"". addslashes($_POST["deckname"]) ."\", `cards` = \"". addslashes($_POST["deck"]) ."\""
		." WHERE `id` = ". $_POST["id"]
		: // or
		"INSERT INTO `magic_decks` (`account_id`, `title`, `cards`)"
		." VALUES (". $_SESSION["id"] .", \"". addslashes($_POST["deckname"]) ."\", \"". addslashes($_POST["deck"]) ."\")";
	
	$req 	= mysql_query($sql);
}

if (isset($_POST["delete"]) && $_POST["id"] > 0)
{
	$sql	= "SELECT * FROM `magic_decks` WHERE `id` = ". $_POST["id"]
			." AND `account_id` = ". $_SESSION["id"];
			
	if (mysql_query($sql))
	{
		$sql	= "DELETE FROM `magic_decks` WHERE `id` = ". $_POST["id"];
		$req 	= mysql_query($sql);
	} else {
		echo 'error';
	}
}


$sql	= "SELECT * FROM `magic_decks` WHERE `account_id` = ". $_SESSION["id"];
$req 	= mysql_query($sql);

mysql_close($link);
?>



<head>
	<title>Deck List</title>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="list.css" />
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
								<form method="post" action="deck.php">
									<input type="hidden" name="id" value="<?=$data["id"]?>" />
									<input type="submit" name="edit" value="Modifier" />
								</form>
							</td>
							
							<td>
								<form method="post">
									<input type="hidden" name="id" value="<?=$data["id"]?>" />
									<input type="submit" name="delete" value="Supprimer" />
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
<!--