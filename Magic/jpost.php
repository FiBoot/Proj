<?php
include_once "sql.php";

if (isset($_POST["action"]))
{
	switch ($_POST["action"])
	{
		case "get_cards":
			$link	= bdd_connect();
			$cards	= "";
			
			$sql	= "SELECT * FROM `magic_cards`";
			$req	= mysql_query($sql);
			
			$bool	= false;
			while ($data = mysql_fetch_array($req))
			{
				if ($bool) { $cards .= "|"; } else { $bool = true; }
				$cards	.= $data["id"] ."=". $data["name"];
			}
			
			echo $cards;
		break;
		
		case "update_game":
			echo "ok";
		break;
		
		default:
			echo "jpost error";
	}
	
	mysql_close($link);
}
?>