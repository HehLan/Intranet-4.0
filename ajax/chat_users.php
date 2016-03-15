<?php
require_once('../common/connect.php');

if(isset($_POST['id']))
{
	$sql="INSERT INTO tchat_users (id_joueur,pseudo,lastcon)
	VALUES (:id,:pseudo,NOW())
	ON DUPLICATE KEY UPDATE  lastcon=NOW()";
	$query=$connexion->prepare($sql);
	$query->bindValue('id', $_POST['id'], PDO::PARAM_INT);
	$query->bindValue('pseudo', $_POST['pseudo'], PDO::PARAM_INT);
	if(!$query->execute()) echo 'ERREUR USERS CHAT SQL 1';
}
	
$sql="SELECT pseudo FROM tchat_users WHERE lastcon>SUBTIME(NOW(),'0 0:0:30') ORDER BY pseudo";
$query=$connexion->prepare($sql);

if($query->execute())
{
	echo '<strong>Connectés :</strong><br>';
	while( $msg = $query->fetch(PDO::FETCH_ASSOC) )
	{
		$pseudo=htmlspecialchars($msg['pseudo']);
		echo $pseudo.'<br>';
	}
}
else  {echo 'ERREUR USERS SQL 2';}
