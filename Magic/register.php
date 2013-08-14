<?php
include_once "sql.php";

$link	= bdd_connect();

if (isset($_POST["register"]))
{
	$sql		= "SELECT * FROM `magic_accounts` WHERE `login` = \"". addslashes($_POST["login"]) ."\"";
	if (mysql_num_rows(mysql_query($sql)))
		$error	= "Pseudo déjà utilisé";
	
	$sql		= "SELECT * FROM `magic_accounts` WHERE `email` = \"". addslashes($_POST["email"]). "\"";
	if (mysql_num_rows(mysql_query($sql)))
	{
		$error 	.= (isset($error)) ? ", " : "";
		$error	.= "Email déjà utilisé";
	}
	
	if (!isset($error))
	{
		$sql 	= "INSERT INTO `magic_accounts` (login, password, email) VALUES (\"". addslashes($_POST["login"]) ."\", \"". addslashes($_POST["password"]) ."\", \"". addslashes($_POST["email"]) ."\")";
		mysql_query($sql);
		header('Location: index.php');
	}
}
?>


<head>
	<title>FiBoot</title>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="register.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{
		$("form").submit(function()
		{
			var error	= "";
			
			var	reg		= /^[A-Z][a-zA-Z]{2,15}$/;
			if (!reg.test($("input[name=login]").val()))
				error	+= "- Pseudo incorrect (3 characteres minimum - commençant par une majuscule)";
				
			var	reg	= /^[a-zA-Z0-9]{3,15}$/;
			if (!reg.test($("input[name=password]").val()))
				error	+= (error.length > 0) ? "\n\n- Mot de passe incorrect (3 characteres minimum - minuscules, majuscules ou chiffres)" : "- Mot de passe incorrect (3 characteres minimum - minuscules, majuscules ou chiffres)";
				
			var	reg	= /^[a-z0-9.]{3,28}@[a-z]{3,28}.[a-z]{2,6}$/;
			if (!reg.test($("input[name=email]").val()))
				error	+= (error.length > 0) ? "\n\n- Email incorrect" : "- Email incorrect";
			
			if (error.length > 0)
			{
				alert(error);
				return false;
			}
			return true;
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
			
			<?php if (isset($error)) { ?>
				<div class="error"><?=$error?></div>
			<?php } ?>
			
			<form method="POST" action="register.php">
			
			<table>
				<tr>
					<td>Pseudo</td><td><input type="text" name="login" maxlength="36" /></td>
				</tr>
				<tr>
					<td>Mot de passe</td><td><input type="password" name="password" maxlength="36" /></td>
				</tr>
				<tr>
					<td>Email</td><td><input type="text" name="email" maxlength="64" /></td>
				</tr>
				<tr>
					<td><a href="index.php"><input type="button" class="dark" value="Retour" /></a></td>
					<td><input type="submit" name="register" value="Envoyer" /></td>
				</tr>
			</table>
			
			</form>
		
		</div>
		
		<div class="footer">
			Created by <a href="mailto:fiboot89@gmail.com">FiBoot</a>
		</div>
		
	</div>

</body>