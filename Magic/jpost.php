<?php
include_once "sql.php";

if (isset($_POST["action"]))
{
	$link	= bdd_connect();
	
	switch ($_POST["action"])
	{
		case "get_cards":
			$sql	= "SELECT * FROM `magic_cards`;";
			$req	= mysql_query($sql);
			
			$cards	= "";
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
		
		case "update_log":
			$sql	= "SELECT * FROM `magic_logs` WHERE `game_id` = ". $_POST["game_id"] ." ORDER BY `date` DESC;";
			$req	= mysql_query($sql);
			
			$logs	= "";
			while ($data = mysql_fetch_array($req))
			{
				switch ($data["log_type"])
				{
					case "chat":
						$logs	.= "<li><span>". $data["account_id"] .": </span>". $data["log"] ."</li>";
					break;
					
					case "game":
						$logs	.= "<li class=\"game\">". $data["log"] ."</li>";
					break;
					
					default:
						$logs	.= "<li class=\"log\">". $data["log"] ."</li>";
				}
			}
			echo $logs;
		break;
		
		case "send_log":
			$sql	= "INSERT INTO `magic_logs` (`game_id`, `account_id`, `log_type`, `log`, `date`) VALUES "
					."(". $_POST["game_id"] .", ". $_POST["account_id"] .", \"". addslashes($_POST["log_type"]) ."\", \"". addslashes($_POST["log"]) ."\", \"". date('Y-m-j H:i:s') ."\");";
			$req	= mysql_query($sql);
			
			$sql	= "SELECT * FROM `magic_logs` WHERE `game_id` = ". $_POST["game_id"] .";";
			$req	= mysql_query($sql);
			//gestion d'erreur ?
		break;
		
		default:
			echo "jpost error";
	}
	
	mysql_close($link);
}

?>