<?php
function bdd_connect()
{
	$link = mysql_connect("localhost", "letakol", "283669");
	mysql_select_db("letakol");
	return ($link);
}
?>