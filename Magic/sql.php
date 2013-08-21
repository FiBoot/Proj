<?php
function bdd_connect()
{
	$link	= mysql_connect("localhost", "letakol", "283669");
	mysql_select_db("letakol");
	
	return ($link);
}

function query($sql)
{
	$result	= mysql_query($sql);
	if (!$result)
		die("Sql error within: <span style='color:#555;'>$sql</span><br /><span style='color:#C00;'>". mysql_error() ."</span>");
		
	return $result;
}

function close($link)
{
	mysql_close($link);
}
?>