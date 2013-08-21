<?php
session_start();
include_once "sql.php";

if (!isset($_SESSION["id"]))
	echo 'invalid credential';

if (isset($_POST["action"]))
{
	$link	= bdd_connect();
	
	
	switch ($_POST["action"])
	{
		case "get_cards":
			$req	= query("SELECT * FROM `magic_cards`;");
			
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
			query("UPDATE `magic_game_status` SET `date` = CURRENT_TIMESTAMP() WHERE `account_id` = ". $_SESSION["id"] .";");
			
			$req	= query("SELECT * FROM `magic_game_status` WHERE `game_id` = ". $_POST["game_id"] ." AND TIMESTAMPDIFF(SECOND, `date`, CURRENT_TIMESTAMP) > 3;");
			echo "SELECT * FROM `magic_game_status` WHERE `game_id` = ". $_POST["game_id"] ." AND TIMESTAMPDIFF(SECOND, `date`, CURRENT_TIMESTAMP) > 3;";
			
			while ($data = mysql_fetch_array($req))
			{
				query("INSERT INTO `magic_logs` (`game_id`, `account_id`, `log_type`, `log`) VALUES (". $data["game_id"] .", ". $data["account_id"] .", game, ". $data["account_id"] ." à quitter la partie);");
				query("DELETE FROM `magic_game_status` WHERE `account_id` = ". $data["account_id"] .";");
			}

			$logs	= "";
			$req	= query("SELECT * FROM `magic_logs` WHERE `game_id` = ". $_POST["game_id"] ." ORDER BY `date` DESC;");
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
			query("INSERT INTO `magic_logs` (`game_id`, `account_id`, `log_type`, `log`) VALUES (". $_POST["game_id"] .", ". $_SESSION["id"] .", \"". addslashes($_POST["log_type"]) ."\", \"". addslashes($_POST["log"]) ."\");");
			
			if ($_POST["first"] > 0)
				query("INSERT INTO `magic_game_status` (`game_id`, `account_id`) VALUES (". $_POST["game_id"] .", ". $_SESSION["id"] .");");
		break;
		
		default:
			echo "jpost error";
	}
	
	close($link);
}

?>