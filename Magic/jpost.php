<?php
session_start();
include_once "sql.php";


if (!isset($_SESSION["id"]))
	echo 'jPost Error: invalid credential';

	
if (isset($_POST["action"]))
{
	$link	= bdd_connect();
	
	
	switch ($_POST["action"])
	{
		
		// req: 1
		case "list_games":
			$games	= "";
			
			$req	= query("SELECT * FROM `magic_games` WHERE `active` = 1");
			while ($data = mysql_fetch_array($req))
				$games	.= $data["id"] ."=". $date["creator_account_id"] ."=". $date["creator_deck_id"] ."=". $date["oppenent_account_id"] ."=". $date["oppenent_deck_id"] ."=". $date["date"] ."=". $date["active"] ."|";
				
			echo $games;
		break;
	
	
		// req: 1
		case "get_cards":
			$cards	= "";
			$bool	= false;
			
			$req	= query("SELECT * FROM `magic_cards`;");
			while ($data = mysql_fetch_array($req))
			{
				if ($bool) { $cards .= "|"; } else { $bool = true; }
				$cards	.= $data["id"] ."=". $data["name"];
			}
			echo $cards;
		break;
		
		// req: 1
		case "send_log":
			query("INSERT INTO `magic_logs` (`game_id`, `account_id`, `log_type`, `log`) VALUES (". $_POST["game_id"] .", ". $_SESSION["id"] .", \"". addslashes($_POST["log_type"]) ."\", \"". addslashes($_POST["log"]) ."\");");
		break;
		
		// req: 1
		case "get_logs":
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
		
		// req: 1-5++
		case "update_game_status":
			if (!mysql_num_rows(query("SELECT * FROM `magic_game_status` WHERE `game_id` = ". $_POST["game_id"] ." AND `account_id` = ". $_SESSION["id"] .";")))
			{
				query("INSERT INTO `magic_logs` (`game_id`, `account_id`, `log_type`, `log`) VALUES (". $_POST["game_id"] .", ". $_SESSION["id"] .", \"game\", \"". $_SESSION["id"] ." a rejoint la partie\");");
				query("INSERT INTO `magic_game_status` (`game_id`, `account_id`) VALUES (". $_POST["game_id"] .", ". $_SESSION["id"] .");");
			}
			
			$req1	= query("SELECT * FROM `magic_game_status` WHERE `game_id` = ". $_POST["game_id"] ." AND TIMESTAMPDIFF(SECOND, `date`, CURRENT_TIMESTAMP) > 3;");
			while ($data1 = mysql_fetch_array($req1))
			{
				query("INSERT INTO `magic_logs` (`game_id`, `account_id`, `log_type`, `log`) VALUES (". $data1["game_id"] .", ". $data1["account_id"] .", \"game\", \"". $data1["account_id"] ." à quitté la partie\");");
				query("DELETE FROM `magic_game_status` WHERE `id` = ". $data1["id"] .";");
				
				if ($req2 = query("SELECT * FROM `magic_games` WHERE `id` = ". $data1["game_id"] ." AND (`creator_account_id` = ". $data1["account_id"] ." OR `oppenent_account_id` = ". $data1["account_id"] .");"))
				{
					$data2	= mysql_fetch_array($req2);
					query("UPDATE `magic_games` SET `active` = 0 WHERE `id` = ". $data2["id"] .";");
					echo 'game over';
				}
			}
			
			query("UPDATE `magic_game_status` SET `date` = CURRENT_TIMESTAMP() WHERE `game_id` = ". $_POST["game_id"] ." AND `account_id` = ". $_SESSION["id"] .";");
		break;
		
		/*
		// req: 1-3+
		case "update_game":
			$req	= query("SELECT * FROM magic_games LEFT JOIN magic_game_status ON magic_games.id = magic_game_status.game_id WHERE TIMESTAMPDIFF(SECOND, magic_game_status.date, CURRENT_TIMESTAMP) > 3");
			while ($data = mysql_fetch_array($req))
			{
			}
		break;
		*/
		
		default:
			echo "jPost Error: invalid action";
			// gestion d'erreur
	}
	
	close($link);
}

?>