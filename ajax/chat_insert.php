<?php
require_once('../common/connect.php');

if(isset($_POST['id']))
{
	$sql="INSERT INTO tchat(id_joueur,pseudo,quand,message)
	VALUES (:id,:pseudo,NOW(),:msg)";
	$query=$connexion->prepare($sql);
	$query->bindValue('id', $_POST['id'], PDO::PARAM_INT);
	$query->bindValue('msg', $_POST['msg'], PDO::PARAM_STR);
	$query->bindValue('pseudo', $_POST['pseudo'], PDO::PARAM_STR);
	if(!$query->execute()) echo 'ERREUR INSERT CHAT SQL';
}
	